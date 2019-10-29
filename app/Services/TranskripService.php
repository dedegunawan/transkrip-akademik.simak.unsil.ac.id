<?php
/**
 * Created by PhpStorm.
 * User: tik_squad
 * Date: 24/10/19
 * Time: 07.49
 */

namespace App\Services;

use App\Exceptions\MahasiswaNotFoundException;
use App\Exceptions\TugasAkhirNotFoundException;
use App\Models\TugasAkhir;
use DedeGunawan\TranskripAkademikUnsil\Entities\Tanggal;
use DedeGunawan\TranskripAkademikUnsil\TranskripAkademikUnsil;
use GuzzleHttp\Client;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\DB;

class TranskripService
{
    public function __construct()
    {
        $this->setNpm(session('npm'));
    }

    /**
     * @var Application
     */
    protected $app;
    protected $npm;

    public function getMahasiswa()
    {
        static $mahasiswa;
        static $last_npm;
        $npm = $this->getNpm();

        if (($mahasiswa == null || $last_npm != $npm) && $npm) {
            DB::connection()->enableQueryLog();
            $ta = TugasAkhir::where('MhswID', $npm)->first();
            if (!$ta || !trim($ta['sudah_lulus'])) throw new TugasAkhirNotFoundException($npm);

            if (!$ta['mahasiswa']) throw new MahasiswaNotFoundException($npm);

            $mahasiswa = $ta['mahasiswa'];
            $last_npm = $npm;
        }
        return $mahasiswa;

    }

    public function getTugasAkhir()
    {
        return $this->getMahasiswa()['tugas_akhir'];
    }

    public function loadTranskrip($npm)
    {
        $client = new Client([
            'verify' => false
        ]);

        $request = $client->get(
            "https://simak.unsil.ac.id/us-unsil/baa/transkripmhsw.php?gos=_CetakTranskrip&MhswID=$npm&_rnd=r7Tc3Gef&jen=2"
        );

        $response = $request->getBody();

        return $response;
    }



    public function generateNomorTranskrip($tahun, $bulan)
    {
        $noseri = null;
        try {

            DB::beginTransaction();
            $tugas_akhir = $this->getTugasAkhir();
            if (@$tugas_akhir['nomortranskrip']) return @$tugas_akhir['noseri'];
            if ($tahun=='1930'&&$bulan==1) {
                $tanggal_lulus = @$tugas_akhir['Tgllulus'];
                $tahun = substr($tanggal_lulus, 0, 4);
                $bulan = substr($tanggal_lulus, 5, 2);
            }

            $bln = Tanggal::getRomawiBulan($bulan);

            // this is a old script, i wont touch this.


            //----autonumbering
            $s = "SELECT nomortranskrip from ta where tahun='$tahun' and noseri like '%/ UN58 / PP.03.01 /%' order by nomortranskrip desc LIMIT 1";
            $w = (array) DB::selectOne($s);
            $auto     = $w['nomortranskrip'];
            // $auto = 0015;
            $gen      = $auto + 1;


            $generate = str_pad($gen, 4, '0', STR_PAD_LEFT);
            $noseri   = $generate . ' / UN58 / PP.03.01 / ' . $bln . ' / ' . $tahun;

            $tugas_akhir['nomortranskrip'] = $generate;
            $tugas_akhir['noseri'] = $noseri;
            $tugas_akhir['tahun'] = $tahun;
            $tugas_akhir->save();

            DB::commit();
        } catch (\Exception $exception) {
            dd($exception);
            DB::rollBack();
        }

        //----autonumbering
        return $noseri;

        // end old script
    }

    /**
     * @return mixed
     */
    public function getNpm()
    {
        return $this->npm;
    }

    /**
     * @param mixed $npm
     */
    public function setNpm($npm)
    {
        $this->npm = $npm;
    }

    /**
     * @return mixed
     */
    public function getApp()
    {
        return $this->app;
    }

    /**
     * @param mixed $app
     */
    public function setApp($app)
    {
        $this->app = $app;
    }




}
