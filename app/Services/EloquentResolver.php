<?php
/**
 * Created by PhpStorm.
 * User: tik_squad
 * Date: 10/14/19
 * Time: 11:25 AM
 */

namespace App\Services;


use DedeGunawan\TranskripAkademikUnsil\Collections\KrsCollection;
use DedeGunawan\TranskripAkademikUnsil\Entities\Kelulusan;
use DedeGunawan\TranskripAkademikUnsil\Entities\Konsentrasi;
use DedeGunawan\TranskripAkademikUnsil\Entities\Mahasiswa;
use DedeGunawan\TranskripAkademikUnsil\Entities\TandaTangan;
use DedeGunawan\TranskripAkademikUnsil\Entities\Tanggal;
use DedeGunawan\TranskripAkademikUnsil\Exceptions\NpmException;
use DedeGunawan\TranskripAkademikUnsil\Interfaces\ResolverInterface;
use DedeGunawan\TranskripAkademikUnsil\TranskripAkademikUnsil;
use Illuminate\Support\Facades\DB;

class EloquentResolver implements ResolverInterface
{
    /**
     * @var \App\Models\Mahasiswa
     */
    protected $mahasiswaModel;

    protected $transkripAkademikUnsil;

    public function resolve()
    {
        $this->loadMahasiswaFromTranskripService();

        $npm = $this->getTranskripAkademikUnsil()->getNpm();
        if (!$npm) throw new NpmException();

        $this->stopIfNotLulus();

        $this->setupNomorTranskrip();

        $this->setupKonsentrasi();



        $mahasiswa = Mahasiswa::build([
            'npm' => $npm,
            'nama_mahasiswa' => 'Dede Gunawan',
            'tempat_lahir' => 'Tasikmalaya',
            'tanggal_lahir' => '1994-09-12',
            'url_foto' => 'url_foto',
        ]);
        $this->getTranskripAkademikUnsil()->setMahasiswa($mahasiswa);


        $this->getTranskripAkademikUnsil()->setTanggalCetak(Tanggal::buildUnknownFormat("2019-10-20"));

        $this->getTranskripAkademikUnsil()->setKotaCetak("Tasikmalaya");

        $this->setupDefaultTandaTangan();

        $this->setupKrsCollection();

    }

    public function stopIfNotLulus()
    {
        $mahasiswaModel = $this->getMahasiswaModel();

        $mhsw = $mahasiswaModel['StatusMhswID']=="L";
        if (!$mhsw) throw new \Exception("Mahasiswa belum lulus");
    }

    public function setupNomorTranskrip()
    {
        $npm = $this->getTranskripAkademikUnsil()->getNpm();

        $nomor_transkrip = @$this->getMahasiswaModel()['ta']['noseri'];

        $this->getTranskripAkademikUnsil()->setNomorTranskrip("Nomor : ".$nomor_transkrip);
        $kelulusan = Kelulusan::build([
            'tanggal_lulus' => @$this->getMahasiswaModel()['ta']['Tgllulus'],
            'ipk' => @$this->getMahasiswaModel()['predikat'],
            'predikat' => @$this->getMahasiswaModel()['predikat'],
            'judul' => $this->getTranskripAkademikUnsil()->getLanguage() == 'en'
                ? @$this->getMahasiswaModel()['ta']['Judul_en']
                : @$this->getMahasiswaModel()['ta']['Judul']
        ]);
        $this->getTranskripAkademikUnsil()->setKelulusan($kelulusan);
    }

    public function setupKonsentrasi()
    {
        $npm = $this->getTranskripAkademikUnsil()->getNpm();

        $query = "select DISTINCT(m.KonsentrasiID) as _KonsentrasiID, COUNT(k.KRSID) as _countKID
            from krs k left outer join mk m on m.MKID=k.MKID and m.KodeID='UNSIL'
            where k.MhswID='$npm' and m.KonsentrasiID!=0 and k.KodeID='UNSIL'
            group by m.KonsentrasiID
            order by _countKID DESC";
        $KonsentrasiID = DB::select($query);
        dd($KonsentrasiID);


        if (!$KonsentrasiID) {
            $konsentrasi_kode = $database->selectField("SELECT KonsentrasiKode from mhsw where MhswID='$npm'");
        } else {
            $konsentrasi_kode = $database->selectField("SELECT KonsentrasiKode from konsentrasi where KonsentrasiID='$KonsentrasiID' and NA='N' and KodeID='UNSIL'");
        }
        $nama_konsentrasi = $database->selectField("SELECT Nama from konsentrasi where KonsentrasiKode='$konsentrasi_kode' and NA='N' and KodeID='UNSIL'");

        $mahasiswa = $this->getMahasiswaModel();
        $prodi = $mahasiswa['prodi'];
        $jenjang = $mahasiswa['jenjang'];
        $fakultas = $mahasiswa['fakultas'];

        $konsentrasi['nama_konsentrasi'] = $nama_konsentrasi;


        $konsentrasi = [
            'kode_prodi' => $prodi['ProdiID'],
            'nama_prodi' => $prodi['Nama']." (".$jenjang['Nama'].") ",
            'kode_fakultas' => $fakultas['FakultasID'],
            'nama_fakultas' => $fakultas['Nama'],
            'status_akreditasi' => $prodi['Akreditasi'],
            'nama_konsentrasi' => "",
        ];

        $konsentrasi = Konsentrasi::build($konsentrasi);
        $this->getTranskripAkademikUnsil()->setKonsentrasi($konsentrasi);
    }

    public function setupKrsCollection()
    {
        $npm = $this->getTranskripAkademikUnsil()->getNpm();
        $database = $this->getTranskripAkademikUnsil()->getConnectionManager()->getConnection();
        $ProdiID = substr($npm, 2, 4);

        $s = "select k.KRSID as id_matakuliah, k.MKKode as kode_matakuliah, k.Nama as nama_matakuliah,
        k.BobotNilai as angka_mutu, k.GradeNilai as huruf_mutu, k.SKS as sks
        from krs k
        left outer join mk mk on mk.MKID=k.MKID
        where k.MhswID = '$npm'
        and mk.ProdiID = '$ProdiID'
        and k.NA = 'N'
        and k.Tinggi = '*'
        and k.GradeNilai <> '' and k.GradeNilai <> '-'
        GROUP BY k.Nama
        order by mk.Sesi,k.MKKode";

        $krs_collection = KrsCollection::build($database->selectAll($s));

        $this->getTranskripAkademikUnsil()->setKrsCollection($krs_collection);
    }

    public function setupDefaultTandaTangan()
    {
        $npm = $this->getTranskripAkademikUnsil()->getNpm();
        $database = $this->getTranskripAkademikUnsil()->getConnectionManager()->getConnection();
        $ProdiID = substr($npm, 2, 4);
        $FakultasID = substr($npm, 2, 2);

        if (!$this->getTranskripAkademikUnsil()->getTandaTanganKanan()) {
            $kanan = $database->selectOne("
                SELECT Jabatan as nama_jabatan, Nama as nama_pejabat, NIP as nip_pejabat
                FROM pejabat where Jabatan='Rektor'
            ");
            $kanan = TandaTangan::build($kanan);
            $this->getTranskripAkademikUnsil()->setTandaTanganKanan($kanan);
        }

        if (!$this->getTranskripAkademikUnsil()->getTandaTanganKiri()) {
            $kiri = $database->selectOne("
                SELECT Jabatan as nama_jabatan, Nama as nama_pejabat, NIP as nip_pejabat
                FROM pejabat where Jabatan='Dekan'
                and FakultasID = '$FakultasID'
            ");
            $kiri = TandaTangan::build($kiri);
            $this->getTranskripAkademikUnsil()->setTandaTanganKiri($kiri);
        }
    }

    /**
     * @return TranskripAkademikUnsil
     */
    public function getTranskripAkademikUnsil()
    {
        return $this->transkripAkademikUnsil;
    }

    /**
     * @param TranskripAkademikUnsil $transkripAkademikUnsil
     */
    public function setTranskripAkademikUnsil(TranskripAkademikUnsil $transkripAkademikUnsil)
    {
        $this->transkripAkademikUnsil = $transkripAkademikUnsil;
    }

    /**
     * @return \App\Models\Mahasiswa
     */
    public function getMahasiswaModel(): \App\Models\Mahasiswa
    {
        return $this->mahasiswaModel;
    }

    /**
     * @param \App\Models\Mahasiswa $mahasiswaModel
     */
    public function setMahasiswaModel(\App\Models\Mahasiswa $mahasiswaModel): void
    {
        $this->mahasiswaModel = $mahasiswaModel;
    }

    public function loadMahasiswaFromTranskripService()
    {
        $transkripService = resolve(TranskripService::class);
        $mahasiswa = $transkripService->getMahasiswa();
        $this->setMahasiswaModel($mahasiswa);


    }


}
