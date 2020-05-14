<?php

namespace App\Http\Controllers;

use App\AppMysqliResolver;
use App\Exceptions\AppException;
use App\Exceptions\TugasAkhirNotFoundException;
use App\Models\Pejabat;
use App\Services\TranskripService;
use App\TranskripS1DuaKolomTemplate;
use App\TranskripS2Template;
use DedeGunawan\TranskripAkademikUnsil\Databases\Builder\MysqliBuilder;
use DedeGunawan\TranskripAkademikUnsil\Databases\ConnectionManager;
use DedeGunawan\TranskripAkademikUnsil\Entities\TandaTangan;
use DedeGunawan\TranskripAkademikUnsil\Entities\Tanggal;
use DedeGunawan\TranskripAkademikUnsil\Services\Resolver\MysqliResolver;
use DedeGunawan\TranskripAkademikUnsil\Templates\BaseTemplate;
use DedeGunawan\TranskripAkademikUnsil\TranskripAkademikUnsil;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class TranskripController extends Controller
{

    public function index(TranskripService $transkripService)
    {
        try {
            $mahasiswa = $transkripService->getMahasiswa();
            $npm = $this->checkNpm();

            return view('welcome', compact(
                'npm', 'mahasiswa'
            ));
        } catch (TugasAkhirNotFoundException $exception) {
            return $exception->render();
        } catch (\Exception $exception) {
            dd($exception);
        }
    }

    public function doFilter()
    {
        $npm = $this->checkNpm();
        return redirect()->route('transkrip_index');
    }

    public function checkNpm()
    {
        $npm = \Illuminate\Support\Facades\Request::input('npm');
        if ($npm && $npm != 'clear') {
            session()->put('npm', $npm);
        } else if ($npm == 'clear') {
            session()->forget('npm');
        }

        return session('npm');

    }

    public function generateTranskrip(TranskripService $transkripService, $preview=false)
    {
        try {

            if (!$transkripService->getNpm()) return redirect()->route('transkrip_index');
            if (!$preview && (!session('ttd_kiri') || !session('ttd_kanan'))) {
                throw new AppException("Silahkan pilih Tanda tangan terlebih dahulu");
            }

            $this->resolveTandaTangan();

            Tanggal::setLocaleIndonesia();

            $transkrip_akademik = TranskripAkademikUnsil::getInstance();
            $transkrip_akademik->setConnectionManager(ConnectionManager::getInstance());

            // enable language support
            if (session('bahasa')) $transkrip_akademik->setLanguage(session('bahasa'));

            $mysqli = new MysqliBuilder();
            $mysqli->setHost(getenv('DB_HOST'));
            $mysqli->setUsername(getenv('DB_USERNAME'));
            $mysqli->setDbname(getenv('DB_DATABASE'));
            $mysqli->setPasswd(getenv('DB_PASSWORD'));
            $mysqli->setPort(getenv('DB_PORT'));
            $mysqli->connect();

            $transkrip_akademik->getConnectionManager()->setConnection('mysqli', $mysqli);

            $transkrip_akademik->setNpm($transkripService->getNpm());


            $pasca = substr($transkrip_akademik->getNpm(), 2, 1)==8;
            if ($pasca) {
                $transkrip_akademik->setTemplate(new TranskripS2Template());
            } else {
                $transkrip_akademik->setTemplate(new BaseTemplate());
            }
            if ($preview) {
                $transkrip_akademik->getTemplate()->setOption('with_header', false);
                $transkrip_akademik->getTemplate()->setOption('with_footer', false);
            }

            $transkrip_akademik->setResolver(new AppMysqliResolver());
            $transkrip_akademik->resolve();

            $nik = (array) DB::selectOne("SELECT nik from mhsw where MhswID=?", [$transkrip_akademik->getNpm()]);
            $nik = @$nik['nik'];
            $transkrip_akademik->getMahasiswa()->setNik($nik);
            $nomor_pin = (array) DB::selectOne("SELECT nomor_pin from nomor_pin where MhswID=?", [$transkrip_akademik->getNpm()]);
            $nomor_pin = @$nomor_pin['nomor_pin'];
            $transkrip_akademik->getMahasiswa()->setPin($nomor_pin);

            if (TranskripS1DuaKolomTemplate::harusDuaKolom()) {
                $transkrip_akademik->setTemplate(new TranskripS1DuaKolomTemplate());
            }

            if (!$preview) $this->buildInformasiPrint();

            $transkrip_akademik->getTemplate()->render();


        } catch (AppException $exception) {
            $exception->addData('links', route('opsi_print'));
            return $exception->render();
        }
        catch (\Exception $exception) {
            $errors = $exception->getMessage() . " at file " .
                $exception->getFile() . " line " . $exception->getLine() . "\n";
            return new Response($errors, 500);

        }
    }

    public function buildInformasiPrint()
    {
        $tanggal = sprintf("%02d", session('tanggal'));
        $bulan = sprintf("%02d", session('bulan'));
        $tahun = sprintf("%02d", session('tahun'));
        if ("$tahun-$bulan-$tanggal" == "1930-01-01") {
            $tugas_akhir = app(TranskripService::class)->getTugasAkhir();
            $tanggalObject = Tanggal::buildUnknownFormat($tugas_akhir['Tgllulus']);
            $tanggal = $tanggalObject->getTanggal();
            $bulan = $tanggalObject->getBulan();
            $tahun = $tanggalObject->getTahun();
        }

        if ((int) $tanggal && (int) $bulan && (int) $tahun) {
            $tanggal = Tanggal::build(compact('bulan', 'tanggal', 'tahun'));
            TranskripAkademikUnsil::getInstance()->setTanggalCetak($tanggal);
        }

    }

    public function resolveTandaTangan()
    {
        $ttd_kiri = session('ttd_kiri');
        $ttd_kanan = session('ttd_kanan');
        $npm = app(TranskripService::class)->getNpm();
        $pasca = substr($npm, 2, 1)==8;

        $datas = compact('ttd_kiri', 'ttd_kanan');

        $datas = array_map(function ($ttd1) use($pasca, $npm) {
            $FakultasID = substr($npm, 2, 2);
            $ProdiID = substr($npm, 2, 4);
            $whatField = ($ttd1 == "DekanLama") ? "KodeJabatan" : "Jabatan";
            if ($ttd1 == "DirekturLama") $whatField = "KodeJabatan";
            if (($ttd1 == "Direktur") && ($pasca) ) { //jika pasca
                return Pejabat::where('KodeJabatan', 'Direktur')->first();
            } elseif (($ttd1 == "Rektor") || ($ttd1 == "RektorLama") || ($ttd1 == "Wakil Rektor I") || ($ttd1 == "Wakil Rektor II") || ($ttd1 == "Wakil Rektor III") || ($ttd1 == "Wakil Rektor IV") || ($ttd1 == "Asisten Direktur I") || ($ttd1 == "Direktur")) {
                //$s = "select * from pejabat where KodeJabatan = '$ttd1'";
                return Pejabat::where('KodeJabatan', $ttd1)->first();
            } else {
                $pejabat = Pejabat::where($whatField, $ttd1)
                    ->whereRaw("(FakultasID = '$FakultasID')")
                    ->whereRaw("((ProdiID = '$ProdiID') or (ProdiID = '-'))")
                    ->first();
                if ($pejabat) return $pejabat;
                $pejabat = Pejabat::where($whatField, $ttd1)
                    ->whereRaw("FakultasID", $FakultasID)
                    ->first();
                if ($pejabat) return $pejabat;

                return Pejabat::where($whatField, $ttd1)->first();
            }
        }, $datas);

        if ($datas['ttd_kiri']) {
            $kiri = TandaTangan::build([
                'nama_jabatan' => str_ireplace("lama", "", $datas['ttd_kiri']['Jabatan']),
                'nama_pejabat' => $datas['ttd_kiri']['Nama'],
                'nip_pejabat' => $datas['ttd_kiri']['NIP'],
            ]);
            TranskripAkademikUnsil::getInstance()->setTandaTanganKiri($kiri);
        }
        if ($datas['ttd_kanan']) {
            $kanan = TandaTangan::build([
                'nama_jabatan' => str_ireplace("lama", "", $datas['ttd_kanan']['Jabatan']),
                'nama_pejabat' => $datas['ttd_kanan']['Nama'],
                'nip_pejabat' => $datas['ttd_kanan']['NIP'],
            ]);
            TranskripAkademikUnsil::getInstance()->setTandaTanganKanan($kanan);
        }

    }


    public function generateTranskripPreview(TranskripService $transkripService)
    {
        return $this->generateTranskrip($transkripService, true);
    }

    public function opsiPrint(Request $request, TranskripService $transkripService)
    {
        $npm = $this->checkNpm();
        if (!$npm) return redirect()->route('transkrip_index');
        $mahasiswa = $transkripService->getMahasiswa();

        $pejabats = collect(\App\Models\Pejabat::getDistinctJabatan());
        $pejabats = $pejabats->map(function ($pejabat) {
            return (array) $pejabat;
        });

        $this->updateOpsiPrint();

        if (\Illuminate\Support\Facades\Request::input('do-print'))
            return redirect()->route('generate_transkrip');


        return view('opsi-print', compact(
            'npm', 'mahasiswa', 'pejabats'
        ));

    }

    public function updateOpsiPrint()
    {
        $columns = [
            'ttd_kiri', 'ttd_kanan',
            'tanggal', 'bulan', 'tahun', 'bahasa'
        ];
        foreach ($columns as $column) {
            $value = \Illuminate\Support\Facades\Request::input($column);
            if ($value) session()->put($column, $value);
        }
    }

    public function resetOpsiPrint()
    {
        $columns = [
            'ttd_kiri', 'ttd_kanan',
            'tanggal', 'bulan', 'tahun', 'bahasa'
        ];
        session()->forget($columns);
        return redirect()->route('opsi_print');
    }

    public function loadTranskrip(TranskripService $transkripService)
    {
        try {
            $npm = $this->checkNpm();
            if ($npm) $transkripService->loadTranskrip($npm);
            return [
                'status' => 1,
                'message' => "Berhasil mengambil data"
            ];
        } catch (\Exception $exception) {
            return [
                'status' => 0,
                'message' => $exception->getMessage()
            ];
        }
    }

    public function generateNomorTranskrip(TranskripService $transkripService)
    {
        if (!$transkripService->getNpm()) return redirect()->route('transkrip_index');

        $mahasiswa = $transkripService->getMahasiswa();
        $tugas_akhir = $mahasiswa['tugas_akhir'];
        $nomortranskrip = @$tugas_akhir['nomortranskrip'];
        $noseri = $tugas_akhir['noseri'];

        return view('generate-nomor-transkrip', compact('noseri', 'nomortranskrip', 'mahasiswa', 'tugas_akhir'));
    }

    public function doGenerateNomorTranskrip(Request $request, TranskripService $transkripService)
    {
        if (!$transkripService->getNpm()) return redirect()->route('transkrip_index');

        $mahasiswa = $transkripService->getMahasiswa();
        $tugas_akhir = $mahasiswa['tugas_akhir'];
        $nomortranskrip = @$tugas_akhir['nomortranskrip'];
        if ($nomortranskrip) return redirect()->route('generate_nomor_transkrip');

        $validatedData = $request->validate([
            'bulan' => 'required|between:1,12',
            'tahun' => 'required|integer',
        ]);


        $transkripService->generateNomorTranskrip(
            $request->input('tahun'),
            $request->input('bulan')
        );
        return redirect()->route('generate_nomor_transkrip');
    }

    public function doGenerateNomorTranskripByRequestNpm(Request $request, TranskripService $transkripService)
    {
        if (!$request->input('npm')) return ['status' => 0, 'message' => 'fail'];
        $transkripService->setNpm($request->input('npm'));

        $mahasiswa = $transkripService->getMahasiswa();
        $tugas_akhir = $mahasiswa['tugas_akhir'];
        $nomortranskrip = @$tugas_akhir['nomortranskrip'];
        if ($nomortranskrip) return ['status' => 1, 'message' => 'success '.$nomortranskrip];

        $validatedData = $request->validate([
            'bulan' => 'required|between:1,12',
            'tahun' => 'required|integer',
        ]);


        $noseri = $transkripService->generateNomorTranskrip(
            $request->input('tahun'),
            $request->input('bulan')
        );
        return ['status' => 1, 'message' => 'success '.$noseri];
        //return redirect()->route('generate_nomor_transkrip');
    }

}
