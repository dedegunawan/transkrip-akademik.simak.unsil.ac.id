<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Predikat extends Model
{
    protected $table="predikat";
    protected $primaryKey="PredikatID";
    protected $fillable = [
        'PredikatID',
        'KodeID',
        'ProdiID',
        'Nama',
        'Nama_en',
        'IPKMin',
        'IPKMax',
        'Keterangan',
        'NA',
        'Script',
        'TglBuat',
        'LoginBuat',
        'TglEdit',
        'LoginEdit'
    ];

    public $timestamps=false;
}
