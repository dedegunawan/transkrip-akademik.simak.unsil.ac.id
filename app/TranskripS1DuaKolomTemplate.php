<?php
/**
 * Created by PhpStorm.
 * User: tik_squad
 * Date: 31/03/20
 * Time: 11.00
 */

namespace App;


use DedeGunawan\TranskripAkademikUnsil\Templates\BaseTemplate;
use DedeGunawan\TranskripAkademikUnsil\TranskripAkademikUnsil;

class TranskripS1DuaKolomTemplate extends BaseTemplate
{
    public static function harusDuaKolom()
    {
        $transkrip_akademik = TranskripAkademikUnsil::getInstance();

        $krs_collection = $transkrip_akademik->getKrsCollection();

        $jumlah = $krs_collection->count();

//        if ($jumlah >= 60) return true;

        return true;
    }

    public function BuatHeaderKolom()
    {
        $p = self::getPdf();
        //garis kolom 1
        if ($this->getOption('with_header')) {
            $p->setXY(6, 90);
        } else {
            $p->setX(6);
        }
        $p->Cell(4, 176, '', "LTR", 0, 'C'); // NO
        $p->Cell(19, 176, '', "LTR", 0, 'C'); //KODE MK
        $p->Cell(54, 176, '', "LTR", 0, 'C'); //NAMA MK
        $p->Cell(5.5, 176, '', "LTR", 0, 'C'); //SKS
        $p->Cell(6.5, 176, '', "LTR", 0, 'C');
        $p->Cell(6, 176, '', "LTR", 0, 'C');
        $p->Cell(5.5, 176, '', "LTR", 0, 'C');

        $p->Cell(2, 176, '', 0, 0, 'C'); // border


        $p->Cell(4, 176, '', "LTR", 0, 'C'); // NO
        $p->Cell(19, 176, '', "LTR", 0, 'C'); //KODE MK
        $p->Cell(54, 176, '', "LTR", 0, 'C'); //NAMA MK
        $p->Cell(5.5, 176, '', "LTR", 0, 'C'); //SKS
        $p->Cell(6.5, 176, '', "LTR", 0, 'C');
        $p->Cell(6, 176, '', "LTR", 0, 'C');
        $p->Cell(5.5, 176, '', "LTR", 0, 'C');



        //garis kolom 2
        /*$p->setXY(108,100);
        $p->Cell(4, 122.75, '', 1, 0, 'C'); // NO
        $p->Cell(14.5, 122.75, '', 1, 0, 'C'); //KODE MK
        $p->Cell(63.5, 122.75, '', 1, 0, 'C'); //NAMA MK
        $p->Cell(5, 122.75, '', 1, 0, 'C'); //SKS
        $p->Cell(5.25, 122.75, '', 1, 0, 'C');  //HURUF
        $p->Cell(5, 122.75, '', 1, 0, 'C');
        $p->Cell(5, 122.75, '', 1, 0, 'C');
        */

        //<---  KOORDINAT HEADER TABEL KIRI
        if ($this->getOption('with_header')) {
            $p->setXY(6, 90);
        } else {
            $p->setX(6);
        }

        // Judul tabel [1]
        $t = 7;
        $p->SetFont('Helvetica', 'B', 6);

//        //jika versi Eng
//        $p->Cell(7, $t, 'NO', 1, 0, 'C');
//        $p->Cell(20, $t, 'KODE MK', 1, 0, 'C');
//        $p->Cell(110, $t, 'MATA KULIAH', 1, 0, 'C');
//        $p->Cell(12, $t, 'SKS', 1, 0, 'C');
//        $p->Cell(12, $t / 2, 'HURUF', 'TLR', 0, 'C');
//        $p->Cell(12, $t / 2, 'ANGKA', 'TLR', 0, 'C');
//        $p->Cell(12, $t, 'JML', 1, 0, 'C');
//        $p->Cell(0, $t / 2, '', 0, 1, 'C');
//        $p->Cell(154.5, $t, '', 0, 0, 'C');
//        $p->Cell(12, $t / 2, 'MUTU', 'BLR', 0, 'C');
//        $p->Cell(12, $t / 2, 'MUTU', 'BLR', 0, 'C');
//        $p->Ln($t);

        if ($this->getLanguage()=='en') {
            $p->Cell(4, $t, 'No.', 1, 0, 'C');
            $p->Cell(19, $t, 'Code', 1, 0, 'C');
            $p->Cell(54, $t, 'Subjects', 1, 0, 'C');
            $p->Cell(5.5, $t, 'Crd', 1, 0, 'C');
            $p->Cell(6.5, $t, 'GRD', 'TLR', 0, 'C');
            $p->Cell(6, $t, 'Scr', 'TLR', 0, 'C');
            $p->Cell(5.5, $t, 'Pnt', 1, 0, 'C');

            $p->Cell(2, 176, '', 0, 0, 'C'); // border


            $p->Cell(4, $t, 'No.', 1, 0, 'C');
            $p->Cell(19, $t, 'Code', 1, 0, 'C');
            $p->Cell(54, $t, 'Subjects', 1, 0, 'C');
            $p->Cell(5.5, $t, 'Crd', 1, 0, 'C');
            $p->Cell(6.5, $t, 'GRD', 'TLR', 0, 'C');
            $p->Cell(6, $t, 'Scr', 'TLR', 0, 'C');
            $p->Cell(5.5, $t, 'Pnt', 1, 0, 'C');

            $p->Ln($t);
        } else {

//            $p->SetFont('Helvetica', 'B', 6);
            $p->Cell(4, $t, 'No', 1, 0, 'C');
            $p->Cell(19, $t, 'Kode MK', 1, 0, 'C');
            $p->Cell(54, $t, 'Mata Kuliah', 1, 0, 'C');
            $p->Cell(5.5, $t, 'Sks', 1, 0, 'C');
            $p->Cell(6.5, $t, 'Huruf', 1, 0, 'C');
            $p->Cell(6, $t, 'Mutu', 1, 0, 'C');
            $p->Cell(5.5, $t, 'JML', 1, 0, 'C');

            $p->Cell(2, 176, '', 0, 0, 'C'); // border


            $p->Cell(4, $t, 'No', 1, 0, 'C');
            $p->Cell(19, $t, 'Kode MK', 1, 0, 'C');
            $p->Cell(54, $t, 'Mata Kuliah', 1, 0, 'C');
            $p->Cell(5.5, $t, 'Sks', 1, 0, 'C');
            $p->Cell(6.5, $t, 'Huruf', 1, 0, 'C');
            $p->Cell(6, $t, 'Mutu', 1, 0, 'C');
            $p->Cell(5.5, $t, 'JML', 1, 0, 'C');

            $p->Ln($t);
        }

    }

    public function getJumlahBarisIdeal()
    {
        $transkrip_akademik = TranskripAkademikUnsil::getInstance();
        $krs_collection = $transkrip_akademik->getKrsCollection();

        $KRSIDs = [];

        $jumlah = $krs_collection->reduce(function ($acc, $krs) use (&$KRSIDs) {
            $baris = ceil(strlen($krs['nama_matakuliah']) / 40);
            if ($baris > 1) $KRSIDs[$krs['id_matakuliah']] = $baris;
            return ($acc + $baris);
        }, 0);
        $barisTotal = ceil($jumlah/2);
        if ($barisTotal < 40) $barisTotal = 47;

        return [
            $jumlah, $barisTotal, $KRSIDs
        ];
    }

    public function BuatIsiTranskrip()
    {
        $transkrip_akademik = TranskripAkademikUnsil::getInstance();
        $krs_collection = $transkrip_akademik->getKrsCollection();

        $p = self::getPdf();


        $p->SetFont('Helvetica', '', 5.75);

        // <---  KOORDINAT ISI TABEL KIRI
        if ($this->getOption('with_header')) {
            $p->setXY(6, 97.5);
        } else {
            $p->setX(6);
        }

        $ti = $this->getTinggiBaris();
//        $jumlah = $krs_collection->count();
//        $barisTotal = ceil($jumlah/2);
//        if ($barisTotal < 40) $barisTotal = 47;

        list($jumlah, $barisTotal, $KRSIDs) = $this->getJumlahBarisIdeal();


        $lastYPosition = $p->GetY();

        $realN = 0;

        foreach ($krs_collection as $n => $krs) {

            if ($realN >= $barisTotal) break;

            $realN++;

            $kali = in_array($krs['id_matakuliah'], array_keys($KRSIDs)) ? $KRSIDs[$krs['id_matakuliah']] : 1;

            $p->setX(6);
            $p->Cell(4, $ti*$kali, $n+1, 'L', 0, 'C');
            $p->Cell(19, $ti*$kali, $krs['kode_matakuliah'], 'L', 0, 'C');
            if ($kali > 1) {
                $realN = $realN+$kali-1;
                $lastX = $p->GetX();
                $lastY = $p->GetY();
                $p->MultiCell(50, $ti, $krs['nama_matakuliah'], 'L', 'L');
                $p->SetXY($lastX+54, $lastY);
            } else {
                $p->Cell(54, $ti*$kali, $krs['nama_matakuliah'], 'L', 0);
            }

            $p->Cell(5.5, $ti*$kali, $krs['sks'], 'L', 0, 'C');
            $p->Cell(6.5, $ti*$kali, $krs['huruf_mutu'], 'L', 0, 'C');
            $p->Cell(6, $ti*$kali, number_format($krs['angka_mutu'], 2, ".", ","), 'L', 0, 'C');
            $p->Cell(5.5, $ti*$kali, $krs->getJumlahBobot(), 'LR', 0, 'C');

            $p->Ln($ti*$kali);
        }

        $p->SetY($lastYPosition);

        $barisTotal = $n;

        $realN = 0;


        foreach ($krs_collection as $n => $krs) {
            $realN++;

            if ($realN <= $barisTotal) continue;


            $kali = in_array($krs['id_matakuliah'], array_keys($KRSIDs)) ? $KRSIDs[$krs['id_matakuliah']] : 1;

            $p->setX(108.5);
            $p->Cell(4, $ti*$kali, $n+1, 'L', 0, 'C');
            $p->Cell(19, $ti*$kali, $krs['kode_matakuliah'], 'L', 0, 'C');

            if ($kali > 1) {
                $realN = $realN+$kali-1;
                $lastX = $p->GetX();
                $lastY = $p->GetY();
                $p->MultiCell(50, $ti, $krs['nama_matakuliah'], 'L', 'L');
                $p->SetXY($lastX+54, $lastY);
            } else {
                $p->Cell(54, $ti*$kali, $krs['nama_matakuliah'], 'L', 0);
            }

            $p->Cell(5.5, $ti*$kali, $krs['sks'], 'L', 0, 'C');
            $p->Cell(6.5, $ti*$kali, $krs['huruf_mutu'], 'L', 0, 'C');
            $p->Cell(6, $ti*$kali, number_format($krs['angka_mutu'], 2, ".", ","), 'L', 0, 'C');
            $p->Cell(5.5, $ti*$kali, $krs->getJumlahBobot(), 'LR', 0, 'C');

            $p->Ln($ti*$kali);
        }

        $this->BuatFooterIsiTranskrip();

    }

    public function BuatFooterIsiTranskrip()
    {
        $p = self::getPdf();
        $transkrip_akademik = TranskripAkademikUnsil::getInstance();
        $krs_collection = $transkrip_akademik->getKrsCollection();


        if ($this->getOption('with_header')) {
            $p->setXY(6, 266);
        } else {
            $p->setX(6);
        }

        // Jumlah matakuliah
        $p->SetFont('Helvetica', 'B', 6.5);
        $p->Cell(100.5, 4.5, $this->getJumlahMataKuliah() . $krs_collection->count(), 1, 0, 'C');

        $t = 4.5;

        if ($this->getOption('with_header')) {
            $p->setXY(108.5, 266);
        } else {
            $p->setX(108.5);
        }


        $p->SetFont('Helvetica', 'B', 6.5);
        $p->Cell(52.5, $t, $this->getJumlahTotal(), 'LBT', 0, 'R');

        $p->Cell(12, $t, $krs_collection->getTotalSks(), 'BT', 0, 'C');
        $p->Cell(12, $t, '', 'BT', 0);
        $p->Cell(12, $t, '', 'BT', 0);
        $p->Cell(12, $t, $krs_collection->getTotalBobot(), 'BRT', 0, 'C');
        $p->Ln(5);
    }

}
