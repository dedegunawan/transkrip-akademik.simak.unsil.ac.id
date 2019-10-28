<?php
/**
 * Created by PhpStorm.
 * User: tik_squad
 * Date: 25/10/19
 * Time: 09.27
 */

namespace App\Models;


use DedeGunawan\TranskripAkademikUnsil\Entities\Tanggal;
use Illuminate\Database\Eloquent\Model;

class TugasAkhir extends Model
{
    protected $table="ta";
    protected $primaryKey="TAID";
    protected $fillable = [
        'TahunID',
        'KRSID',
        'MhswID',
        'KodeID',
        'TglDaftar',
        'TglMulai',
        'TglSelesai',
        'TglUjian',
        'TempatUjian',
        'TglUjianSeminar',
        'NilaiSeminar',
        'TempatUjianSeminar',
        'Judul',
        'Judul_en',
        'KepalaPerusahaan',
        'JenisPerusahaan',
        'NamaPerusahaan',
        'AlamatPerusahaan',
        'KotaPerusahaan',
        'TeleponPerusahaan',
        'NamaPekerjaan',
        'JalurSkripsiID',
        'Deskripsi',
        'Pembimbing',
        'Pembimbing2',
        'Penguji',
        'Pengujiseminar',
        'Reviewer',
        'Keterangan',
        'StatusLulusID',
        'Lulus',
        'GradeNilai',
        'BobotNilai',
        'SKYudisium',
        'TglSKYudisium',
        'LoginBuat',
        'TanggalBuat',
        'LoginEdit',
        'TanggalEdit',
        'NA',
        'Tgllulus',
        'TglYudisium',
        'Tglcetak',
        'nomortranskrip',
        'tahun',
        'noseri',
        'nosk',
        'tahunsk',
        'UPPersentase',
        'NilPenguji1',
        'NilPenguji2',
        'NilPenguji3',
        'NilPenguji4',
        'NilPenguji5',
        'NilRata',
        'nilaiup',
        'statusup',
        'praktikum',
        'p2spt',
        'ppbn',
        'fieldstudy',
        'dokkp',
        'up',
        'siapseminar',
        'siapsidang',
        'rangkapsem',
        'bayarsem',
        'sbayarsem',
        'rangkap',
        'konsultasi',
        'mak',
        'penilaian',
        'revisi',
        'cetak',
        'TglCetakSK',
    ];

    public $timestamps=false;

    /**
     * Get status lulus
     *
     * @return boolean
     */
    public function getSudahLulusAttribute()
    {
        return trim($this->getAttribute('Lulus'))=="Y" && trim($this->getAttribute('Tgllulus'));
    }

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class, 'MhswID', 'MhswID');
    }

    /**
     * Get status lulus
     *
     * @return boolean
     */
    public function getNiceNoSeriIjazahAttribute()
    {
        return $this->getAttribute('noseri') ?? "-";
    }

    /**
     * Get status lulus
     *
     * @return string
     */
    public function getNiceTanggalLulusAttribute()
    {
        $tanggal = Tanggal::buildUnknownFormat($this->getAttribute('Tgllulus'));
        return $tanggal->getFormatTextualMonth();
    }

}
