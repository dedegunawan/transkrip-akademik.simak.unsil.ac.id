<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Fakultas extends Model
{
    protected $table="fakultas";
    protected $primaryKey="FakultasID";
    public $incrementing=false;
    protected $fillable = [
        'FakultasID',
        'Nama',
        'Nama_en',
        'KodeID',
        'Pejabat',
        'Jabatan',
        'StartNoFakultas',
        'NoFakultas',
        'Singkatan',
        'Urutan',
        'NA',
        'Singkatan2',
        'Kategori',
        'Telp',
        'email',
        'web',
    ];
    public $timestamps=false;

}
