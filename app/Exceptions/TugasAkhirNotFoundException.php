<?php
/**
 * Created by PhpStorm.
 * User: tik_squad
 * Date: 25/10/19
 * Time: 09.37
 */

namespace App\Exceptions;


use Throwable;

class TugasAkhirNotFoundException extends AppException
{
    protected $npm;
    public function __construct($npm, Throwable $previous = null)
    {
        $message = "Status kelulusan mahasiswa dengan $npm tidak ditemukan";
        $code = 1003;
        $this->setNpm($npm);
        parent::__construct($message, $code, $previous);
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
    public function setNpm($npm): void
    {
        $this->npm = $npm;
    }




}
