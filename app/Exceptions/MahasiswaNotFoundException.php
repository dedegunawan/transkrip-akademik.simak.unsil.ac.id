<?php
/**
 * Created by PhpStorm.
 * User: tik_squad
 * Date: 25/10/19
 * Time: 09.45
 */

namespace App\Exceptions;


use Throwable;

class MahasiswaNotFoundException extends AppException
{
    protected $npm;
    public function __construct($npm, Throwable $previous = null)
    {
        $message = "Data Mahasiswa dengan $npm tidak ditemukan";
        $code = 1004;
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
