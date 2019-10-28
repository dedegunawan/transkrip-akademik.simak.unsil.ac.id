<?php
/**
 * Created by PhpStorm.
 * User: tik_squad
 * Date: 25/10/19
 * Time: 14.00
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Pejabat extends Model
{
    protected $table="pejabat";
    protected $primaryKey="PejabatID";
    protected $fillable = [
        'PejabatID',
        'PeriodeJabatanID',
        'JenisJabatanID',
        'KodeID',
        'Urutan',
        'Nama',
        'NamaSK',
        'KodeJabatan',
        'Jabatan',
        'NIP',
        'FakultasID',
        'ProdiID',
        'TglBuat',
        'LoginBuat',
        'TglEdit',
        'LoginEdit',
        'NA',
    ];

    public $timestamps=false;

    public static function getDistinctJabatan()
    {
        $sql = "select DISTINCT(Jabatan) as Jabatan from pejabat where ProdiID<>'' order by Urutan";
        return DB::select($sql);
    }
}
