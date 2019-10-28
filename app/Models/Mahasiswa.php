<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mahasiswa extends Model
{
    protected $table="mhsw";
    protected $primaryKey="MhswID";
    public $incrementing=false;
    protected $fillable = [
        'MhswID',
        'Login',
        'LevelID',
        'PMBID',
        'TahunID',
        'Nama',
        'NamaWisuda',
        'Foto',
        'FotoWisuda',
        'StatusAwalID',
        'StatusMhswID',
        'ProgramID',
        'ProdiID',
        'KelasID',
        'Kelas',
        'PenasehatAkademik',
        'Kelamin',
        'TempatLahir',
        'TanggalLahir',
        'Agama',
        'nik',
    ];
    protected $hidden = [
        'Password',
        'PasswordScheme',
    ];

    public $timestamps=false;

    public function getLamaStudiAttribute()
    {
        $tanggal_lulus = $this['tugas_akhir']['Tgllulus'];
        $lulus = $this['tugas_akhir']['Lulus'];
        $angkatan = substr($this['TahunID'], 0, 4);
        $tanggal_masuk = "$angkatan-09-01";
        if ($lulus=="N") return "Aktif";

        $datetime1 = new \DateTime($tanggal_lulus);
        $datetime2 = new \DateTime($tanggal_masuk);
        $difference = $datetime1->diff($datetime2);
        return $difference->y." tahun ".$difference->m." bulan ".$difference->d." hari";

    }

    public function tugas_akhir()
    {
        return $this->hasOne(TugasAkhir::class, 'MhswID', 'MhswID');
    }

    public function prodi()
    {
        return $this->belongsTo(Prodi::class, 'ProdiID', 'ProdiID');
    }
}
