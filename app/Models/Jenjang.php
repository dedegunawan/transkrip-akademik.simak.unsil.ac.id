<?php
/**
 * Created by PhpStorm.
 * User: tik_squad
 * Date: 25/10/19
 * Time: 12.58
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Jenjang extends Model
{

    protected $table="jenjang";
    protected $primaryKey="JenjangID";
    public $incrementing=false;
    protected $fillable = [
        'JenjangID',
        'Nama',
        'Namanya',
        'Inggris',
        'Keterangan',
        'Def',
        'NA',
    ];

    public $timestamps=false;
}
