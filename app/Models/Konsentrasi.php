<?php
/**
 * Created by PhpStorm.
 * User: tik_squad
 * Date: 25/10/19
 * Time: 12.53
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Konsentrasi extends Model
{
    protected $table="konsentrasi";
    protected $primaryKey="KonsentrasiID";
    protected $fillable = [
        'KonsentrasiID',
        'KonsentrasiKode',
        'Nama',
        'Nama_en',
        'KodeID',
        'ProdiID',
        'Keterangan',
        'NA',
    ];

    public $timestamps=false;

}
