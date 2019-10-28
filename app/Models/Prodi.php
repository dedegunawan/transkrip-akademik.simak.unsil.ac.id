<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Prodi extends Model
{
    protected $table="prodi";
    protected $primaryKey="ProdiID";
    public $incrementing=false;
    protected $fillable = [
        'ProdiID',
        'KodeID',
        'FakultasID',
        'Nama',
        'Nama_en',
        'Singkatan',
        'NamaJurnal',
        'JenjangID',
        'Gelar',
        'NamaGelar',
        'ProdiDiktiID',
        'NamaSesi',
        'AkreditasiAsli',
        'Akreditasi',
        'FrekuensiPemuktahiran',
        'PelaksanaanPemuktahiran',
        'NoSKDikti',
        'TglSKDikti',
        'TglAkhirSKDikti',
        'NoSKBAN',
        'TglSKBAN',
        'TglAkhirSKBAN',
        'SKPendirian',
        'TanggalPendirian',
        'SemesterAwalID',
        'Telepon',
        'Email',
        'PajakHonorDosen',
        'Pejabat',
        'Jabatan',
        'FormatNIM',
        'GunakanNIMSementara',
        'FormatNIMSementara',
        'DapatPindahProdi',
        'DefSKS',
        'TotalSKS',
        'DefKehadiran',
        'BatasStudi',
        'JumlahSesi',
        'CekPrasyarat',
        'PersenPMB',
        'Keterangan',
        'StartNoProdi',
        'NoProdi',
        'Denda1',
        'Denda2',
        'PilihanKompre',
        'Urutan',
        'UrutanBuku',
        'UKT1',
        'UKT2',
        'UKT3',
        'UKT4',
        'UKT5',
        'NA',
        'Forlap',
        'Jenis',
        'NamaGelar_en',
        'kode_snmptn',
        'opsi_hidden_kelas',
        'Singkatan2',
    ];

    public $timestamps=false;

    public function fakultas()
    {
        return $this->belongsTo(Fakultas::class, 'FakultasID');
    }

    public function jenjang()
    {
        return $this->belongsTo(Jenjang::class, "JenjangID", "JenjangID");
    }
}
