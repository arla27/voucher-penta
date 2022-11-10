<?php
//Menggabungkan dengan file koneksi yang telah kita buat
include '../helper/conn.php';
include_once("../helper/function.php");
// Load library phpspreadsheet
require('../lib/vendor/autoload.php');

use PhpOffice\PhpSpreadsheet\Helper\Sample;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
// End load library phpspreadsheet

$spreadsheet = new Spreadsheet();


$username = $_SESSION['username'];
//Font Color
$spreadsheet->getActiveSheet()->getStyle('A3:D3')
    ->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_WHITE);

//Align
$spreadsheet->getActiveSheet()->getStyle('A1:D2')
    ->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
//Widht
$spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(4, 'px');
$spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(15, 'px');
$spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(15, 'px');
$spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(30, 'px');


//Align
$spreadsheet->getActiveSheet()->getStyle('A1:D2')
    ->getAlignment()->setHorizontal('center');

//Align
$spreadsheet->getActiveSheet()->getStyle('B4:C300')
    ->getAlignment()->setHorizontal('left');


// Background color
$spreadsheet->getActiveSheet()->getStyle('A3:D3')->getFill()
    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
    ->getStartColor()->setARGB('051DFF');


// Header Tabel
$spreadsheet->setActiveSheetIndex(0)->mergeCells('A1:D2')
    ->setCellValue('A1', 'BLASTJET EXPORT KONTAK')
    ->setCellValue('A3', 'ID')
    ->setCellValue('B3', 'Nama')
    ->setCellValue('C3', 'Nomor')
    ->setCellValue('D3', 'Pesan (Default)');


$i = 4;
$no = 1;
$res1 = mysqli_query($koneksi, "SELECT * FROM nomor WHERE make_by = '$username' ");

while ($row = $res1->fetch_assoc()) {
    $spreadsheet->setActiveSheetIndex(0)
        ->setCellValue('A' . $i, $no)
        ->setCellValue('B' . $i, $row['nama'])
        ->setCellValue('C' . $i, $row['nomor'])
        ->setCellValue('D' . $i, utf8_decode($row['pesan']));

    $i++;
    $no++;
}

// Format
$spreadsheet->getActiveSheet()->getStyle('A4:D300')->getNumberFormat()
    ->setFormatCode('0');

// Rename worksheet
$spreadsheet->getActiveSheet()->setTitle('Kontak personal ' . date('d-m-Y H'));

// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$spreadsheet->setActiveSheetIndex(0);

// Redirect output to a client’s web browser (Xlsx)
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="BlastJET Export Contact(default) - ' . $username . '.xlsx"');
header('Cache-Control: max-age=0');
// If you're serving to IE 9, then the following may be needed
header('Cache-Control: max-age=1');

// If you're serving to IE over SSL, then the following may be needed
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
header('Pragma: public'); // HTTP/1.0

$writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
$writer->save('php://output');
