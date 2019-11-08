<?php
/**
 * Created by PhpStorm.
 * User: tik_squad
 * Date: 28/10/19
 * Time: 16.00
 */

namespace App;


use DedeGunawan\TranskripAkademikUnsil\Templates\BaseTemplate;
use DedeGunawan\TranskripAkademikUnsil\TranskripAkademikUnsil;

class TranskripS2Template extends BaseTemplate
{
    public function BuatHeaderTranskrip()
    {
        $transkrip_akademik = TranskripAkademikUnsil::getInstance();
        $mahasiswa = $transkrip_akademik->getMahasiswa();
        $konsentrasi = $transkrip_akademik->getKonsentrasi();

        $p = self::getPdf();

        $lbr = 195;
        $p->SetFont('Helvetica', 'B', 12);
        $p->Ln(7.5);
        $this->getJudul();
        $p->Cell($lbr, 7, $this->getJudul(), 0, 1, 'C');

        $p->SetFont('Helvetica', '', 9);
        //$p->Cell(184,2,'Nomor : '.$nomor.' / UN58 / PP.03.01 / '.$bln.' / '.$tahun,0,1,'C');
        //$p->Cell(184,3,'',0,1,'C');
        $p->Cell($lbr, 2, $this->getNomor().$transkrip_akademik->getNomorTranskrip(), 0, 1, 'C');
        $p->Cell($lbr, 4.5, '', 0, 1, 'C');
        $p->Cell(184, 1.5, '', 0, 1, 'C');



        $arr   = array();
        $arr[] = array(
            $this->getNama(),
            ':',
            strtoupper($mahasiswa->getNamaMahasiswa())
        );
        $arr[] = array(
            $this->getTempatTanggalLahir(),
            ':',
            strtoupper($mahasiswa->getTempatTanggalLahir())
        );
        $arr[] = array(
            $this->getNpm(),
            ':',
            TranskripAkademikUnsil::getInstance()->getNpm()
        );
        $arr[] = array(
            $this->getFakultas(),
            ':',
            strtoupper($konsentrasi->getNamaFakultas())
        );
        $arr[] = array(
            $this->getProdi(),
            ':',
            strtoupper($konsentrasi->getNamaProdi())
        );

        if (!empty($konsentrasi)) {
            $arr[] = array(
                $this->getKonsentrasi(),
                ':',
                ucwords(strtolower($konsentrasi->getNamaKonsentrasi()))
            );
            $t = 5;
        } else {
            $t = 5.5;
        }
        $arr[] = array(
            $this->getStatusAkreditasi(),
            ':',
            strtoupper($konsentrasi->getStatusAkreditasi())
        );
        $arr[] = array(
            $this->getTanggalLulus(),
            ':',
            strtoupper($transkrip_akademik->getKelulusan()->getTanggalLulus()->getFormatTextualMonth())
        );



        //HEADER NAMA
        foreach ($arr as $a) {
            // Kolom 1
            $p->SetFont('Helvetica', '', 8.5);
            $p->SetX(27);
            $p->Cell(50, $t, @$a[0], 0, 0);
            $p->Cell(3, $t, @$a[1], 0, 0);
            $p->SetFont('Helvetica', 'B', 8.5);
            $p->Cell(70, $t, @$a[2], 0, 0);
            $p->Cell(10);
            // Kolom 2
            $p->SetFont('Helvetica', '', 8.5);
            $p->Cell(30, $t, @$a[3], 0, 0);
            $p->Cell(3, $t, @$a[4], 0, 0);
            $p->SetFont('Helvetica', 'B', 8.5);
            $p->Cell(50, $t, @$a[5], 0, 0);
            $p->Ln($t);
        }
    }

    public function BuatHeaderKolom()
    {
        $p = self::getPdf();
        //garis kolom 1
        if ($this->getOption('with_header')) {
            $p->setXY(15.5, 99);
        } else {
            $p->setX(15.5);
        }
        $p->Cell(7, 20, '', "LTR", 0, 'C'); // NO
        $p->Cell(20, 20, '', "LTR", 0, 'C'); //KODE MK
        $p->Cell(110, 20, '', "LTR", 0, 'C'); //NAMA MK
        $p->Cell(12, 20, '', "LTR", 0, 'C'); //SKS
        $p->Cell(12, 20, '', "LTR", 0, 'C');
        $p->Cell(12, 20, '', "LTR", 0, 'C');
        $p->Cell(12, 20, '', "LTR", 0, 'C');

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
            $p->setXY(15.5, 99);
        } else {
            $p->setX(15.5);
        }

        $t = 7;

        $p->SetFont('Helvetica', 'B', 7);
        $p->Cell(7, $t, 'NO', 1, 0, 'C');
        $p->Cell(20, $t, 'KODE MK', 1, 0, 'C');
        $p->Cell(110, $t, 'MATA KULIAH', 1, 0, 'C');
        $p->SetFont('Helvetica', 'B', 6.5);
        $p->Cell(12, $t, 'SKS', 1, 0, 'C');
        $p->Cell(12, $t / 2, 'HURUF', 'TLR', 0, 'C');
        $p->Cell(12, $t / 2, 'ANGKA', 'TLR', 0, 'C');
        $p->Cell(12, $t, 'JML', 1, 0, 'C');
        $p->Cell(0, $t / 2, '', 0, 1, 'C');
        $p->Cell(154.3, $t, '', 0, 0, 'C');
        $p->Cell(12, $t / 2, 'MUTU', 'BLR', 0, 'C');
        $p->Cell(12, $t / 2, 'MUTU', 'BLR', 0, 'C');
        $p->Ln($t);
    }

    public function BuatIsiTranskrip()
    {
        $transkrip_akademik = TranskripAkademikUnsil::getInstance();
        $krs_collection = $transkrip_akademik->getKrsCollection();

        $p = self::getPdf();


        $p->SetFont('Helvetica', '', 7);

        // <---  KOORDINAT ISI TABEL KIRI
        if ($this->getOption('with_header')) {
            $p->setXY(15.5, 106.5);
        } else {
            $p->setX(15.5);
        }

        $ti = $this->getTinggiBaris();

        foreach ($krs_collection as $n => $krs) {
            $p->setX(15.5);
            $p->Cell(7, $ti, $n+1, 'L', 0, 'C');
            $p->Cell(20, $ti, $krs['kode_matakuliah'], 'L', 0, 'C');
            $p->Cell(110, $ti, $krs['nama_matakuliah'], 'L', 0);
            $p->Cell(12, $ti, $krs['sks'], 'L', 0, 'C');
            $p->Cell(12, $ti, $krs['huruf_mutu'], 'L', 0, 'C');
            $p->Cell(12, $ti, number_format($krs['angka_mutu'], 2, ".", ","), 'L', 0, 'C');
            $p->Cell(12, $ti, $krs->getJumlahBobot(), 'LR', 0, 'C');
            $p->Ln($ti);
        }

        $this->BuatFooterIsiTranskrip();
    }

    public function getTinggiBaris()
    {
        $ju = TranskripAkademikUnsil::getInstance()->getKrsCollection()->count();
        if ($ju <= 15) {
            $ti = 7.2;
        } elseif ($ju == 16) {
            $ti = 7;
        } elseif ($ju == 17) {
            $ti = 6.8;
        } elseif ($ju == 18) {
            $ti = 6.7;
        } elseif ($ju == 19) {
            $ti = 6.5;
        } elseif ($ju == 20) {
            $ti = 6.1;
        } elseif ($ju == 31) {
            $ti = 3.95;
        } elseif ($ju == 32) {
            $ti = 3.85;
        } elseif ($ju == 33) {
            $ti = 3.75;
        } elseif ($ju == 34) {
            $ti = 3.65;
        } elseif ($ju == 35) {
            $ti = 3.55;
        } elseif ($ju == 36) {
            $ti = 3.45;
        } elseif ($ju == 37) {
            $ti = 3.35;
        } elseif ($ju == 38) {
            $ti = 3.25;
        } elseif ($ju == 39) {
            $ti = 3.18;
        } elseif ($ju >= 40) {
            $ti = 3;
        }

        return $ti;
    }

    public function BuatFooterIsiTranskrip()
    {
        $p = self::getPdf();
        $transkrip_akademik = TranskripAkademikUnsil::getInstance();
        $krs_collection = $transkrip_akademik->getKrsCollection();


        if ($this->getOption('with_header')) {
            //$p->setXY(15.5, 266);
            $p->setX(15.5);
        } else {
            $p->setX(15.5);
        }

        // Jumlah matakuliah
        $p->SetFont('Helvetica', 'B', 7);
        $p->Cell(92.5, 4.5, $this->getJumlahMataKuliah() . $krs_collection->count(), 1, 0, 'C');

        $t = 4.5;

        if ($this->getOption('with_header')) {
            $p->setX(108);
        } else {
            $p->setX(108);
        }


        $p->SetFont('Helvetica', 'B', 6.5);
        $p->Cell(44.5, $t, $this->getJumlahTotal(), 'BT', 0, 'R');

        $p->Cell(12, $t, $krs_collection->getTotalSks(), 'BT', 0, 'C');
        $p->Cell(12, $t, '', 'BT', 0);
        $p->Cell(12, $t, '', 'BT', 0);
        $p->Cell(12, $t, $krs_collection->getTotalBobot(), 'BRT', 0, 'C');
        $p->Ln(5);
    }

    public function BuatFooterTranskrip()
    {
        $p = self::getPdf();
        $transkrip_akademik = TranskripAkademikUnsil::getInstance();
        $mahasiswa = $transkrip_akademik->getMahasiswa();
        $t = 4;

        $p->SetFont('Helvetica', 'I', 7);
        $p->setX(15);
        $p->Cell(125, $t, $this->getCatatan(), 0, 1, 'L');


        $p->Ln(1);

        $p->SetFont('Helvetica', 'B', 8);
        $p->Cell(15, $t, '', 0, 0, 'L');
        //jika versi Eng
        $p->Cell(50, $t, $this->getIpk(). $transkrip_akademik->getKelulusan()->getIpk(), 0, 0, 'L');
        $p->Cell(59, $t, '', 0, 0, 'L');
        $p->Cell(50, $t, $this->getPredikatKelulusan() . $transkrip_akademik->getKelulusan()->getPredikat(), 0, 1, 'L');

        //jika versi Eng
        if ($this->getLanguage()=='en') {
            $ml  = 25;
            $js  = 'Title of Thesis :';
            $ex  = 50;
            $leb = 150;
        } else {
            $transkrip_akademik = TranskripAkademikUnsil::getInstance();
            $npm = $transkrip_akademik->getNpm();
            $isTeknik = substr($npm, 2, 2)=='70';
            $isPasca = substr($npm, 2, 1)=='8';
            $isPerbankan = substr($npm, 2, 4)=='3404';
            if ($isTeknik || $isPerbankan) {
                $ml  = 25;
                $js  = 'Judul Skripsi/Tugas Akhir :';
                $ex  = 65;
                $leb = 135;
            } else if ($isPasca) {
                $ml  = 25;
                $js  = 'Judul Tesis :';
                $ex  = 50;
                $leb = 150;
            } else {
                $ml  = 25;
                $js  = 'Judul Skripsi :';
                $ex  = 50;
                $leb = 150;
            }
        }

        $lbr = 20;
        $p->SetFont('Helvetica', '', 9);
        $p->Cell($lbr, 5, '', 0, 1, 'L');
        $p->SetXY($ml, 263);
        $p->Cell(190, $t, $js,0, 0, 'L');
        $p->Cell(190, 1, '', 0, 1, 'C');
        $p->SetXY($ex - 5, 262);

        $t = 3; //ENTER BUAT JUDUL
        $p->SetFont('Helvetica', '', 9);


        $formatJudul = function($text) {
            $text = trim($text);
            $text = strtolower($text);
            $textArray = explode(" ", $text);
            $arraySize = count($textArray);
            for($i=0;$i<$arraySize;$i++) {
                $current = $textArray[$i];
                $next = @$textArray[$i+1];
                $current = ucfirst($current);
                $textArray[$i] = $current;
            }
            $datas = implode(" ", $textArray);
            $datas = explode("++", $datas);
            $lengthDatas = count($datas);
            for ($i=0; $i < $lengthDatas; $i++) {
                if ($i%2 == 1) {
                    $datas[$i] = strtoupper($datas[$i]);
                }
            }
            $datas = implode("", $datas);
            $datas = explode("--", $datas);
            $lengthDatas = count($datas);
            for ($i=0; $i < $lengthDatas; $i++) {
                if ($i%2 == 1) {
                    $datas[$i] = strtolower($datas[$i]);
                }
            }
            return implode("", $datas);
        };

        // $judul = $formatJudul($judul);

        //$p->MultiCell($leb + 5, $t, $judul, 0, 'J');

        $enterX=$p->GetX();

        $cetakJudulIjazah = function ($text) use($enterX, $p) {
            $maxPosition = 200;
            $boldToken = "**";
            $italicToken = "__";
            $textArray = explode(" ", $text);
            $arraySize = count($textArray);
            $onBold = 0;
            $onItalic = 0;
            for ($i=0; $i < $arraySize; $i++) {
                $current = $textArray[$i];
                $isBoldToken = $current == $boldToken;
                if ($isBoldToken) $onBold = !$onBold;

                $isItalicToken = $current == $italicToken;
                if ($isItalicToken) $onItalic = !$onItalic;

                if ($isBoldToken || $isItalicToken) continue;

                $format = '';
                $format .= $onBold ? "B" : "";
                $format .= $onItalic ? "I" : "";
                $p->SetFont('Helvetica',$format,8);
                $current = $current." ";
                if ($p->GetX()+$p->GetStringWidth($current) > 199) {
                    $p->Ln();
                    $p->SetX($enterX);
                }
                $p->Cell($p->GetStringWidth($current),4,$current, 0, 'L');

            }

        };

        $cetakJudulIjazah($transkrip_akademik->getKelulusan()->getJudul());

        $p->SetAutoPageBreak(false);
        $p->SetFont('Helvetica', '', 8);
        $t = 3.5; //ENTER BUAT  SETELAH JUDUL
        $p->SetXY(144, 274);


        $p->Cell($lbr, $t, $transkrip_akademik->getKotaCetak(). ', ' . $transkrip_akademik->getTanggalCetak()->getFormatTextualMonth(), 0, 1, 'L');

        $p->Ln(1);

        $kanan = $transkrip_akademik->getTandaTanganKanan();
        $kiri = $transkrip_akademik->getTandaTanganKiri();


        $p->Cell(20, $t, '', 0, 0, 'L');
        $p->Cell(114, $t, $kiri->getNamaJabatan() . ',', 0, 0, 'L');
        $p->Cell(50, $t, $kanan->getNamaJabatan() . ',', 0, 1, 'L');
        $p->SetXY(10, 300);
        $p->SetFont('Helvetica', 'B', 8);
        $p->Cell(20, $t, '', 0, 0, 'L');
        $p->Cell(114, $t, $kiri->getNamaPejabat(), 0, 0, 'L');
        $p->Cell(50, $t, $kanan->getNamaPejabat(), 0, 1, 'L');
        $p->SetFont('Helvetica', '', 8);
        $p->Cell(19.9, $t, '', 0, 0, 'L');
        $p->Cell(114, $t, '' . $kiri->getNipPejabat(), 0, 0, 'L');
        $p->Cell(50, $t, '' . $kanan->getNipPejabat(), 0, 1, 'L');

        $p->SetXY(110, 295);
        //$p->Cell(15, 20, 'FOTO', 1, 0, 'C');
    }


}
