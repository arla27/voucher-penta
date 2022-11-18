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
// $username = 'admin';

//Font Color
$spreadsheet->getActiveSheet()->getStyle('A3:I3')
    ->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_WHITE);

//Align
$spreadsheet->getActiveSheet()->getStyle('A1:I2')
    ->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
//Widht
$spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(5, 'px');
$spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(20, 'px');
$spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(20, 'px');
$spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(40, 'px');
$spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(15, 'px');
$spreadsheet->getActiveSheet()->getColumnDimension('F')->setWidth(15, 'px');
$spreadsheet->getActiveSheet()->getColumnDimension('G')->setWidth(10, 'px');
$spreadsheet->getActiveSheet()->getColumnDimension('H')->setWidth(15, 'px');
$spreadsheet->getActiveSheet()->getColumnDimension('I')->setWidth(15, 'px');


//Align
$spreadsheet->getActiveSheet()->getStyle('A1:I2')
    ->getAlignment()->setHorizontal('center');

//Align
$spreadsheet->getActiveSheet()->getStyle('B4:I300')
    ->getAlignment()->setHorizontal('left');


// Background color
$spreadsheet->getActiveSheet()->getStyle('A3:I3')->getFill()
    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
    ->getStartColor()->setARGB('051DFF');


// Header Tabel
$spreadsheet->setActiveSheetIndex(0)->mergeCells('A1:I2')
->setCellValue('A1', 'DATA PENERIMA VOUCHER')
->setCellValue('A3', 'No.')
->setCellValue('B3', 'NIK')
->setCellValue('C3', 'Nama')
->setCellValue('D3', 'Alamat')
->setCellValue('E3', 'No. Tlp')
->setCellValue('F3', 'Email')
->setCellValue('G3', 'Cabang')
->setCellValue('H3', 'Tgl Digunakan')
->setCellValue('I3', 'Created by');


$i = 4;
$no = 1;
$res1 = mysqli_query($koneksi, "SELECT * FROM user");

while ($row = $res1->fetch_assoc()) {
    $spreadsheet->setActiveSheetIndex(0)
    ->setCellValue('A' . $i, $no)
    ->setCellValue('B' . $i, $row['nik'])
    ->setCellValue('C' . $i, $row['nama'])
    ->setCellValue('D' . $i, $row['alamat'])
    ->setCellValue('E' . $i, $row['no_tlp'])
    ->setCellValue('F' . $i, $row['email'])
    ->setCellValue('G' . $i, $row['cabang'])
    ->setCellValue('H' . $i, $row['tgl_pakai'])
    ->setCellValue('I' . $i, $row['make_by']);

    $i++;
    $no++;
}
// Format
$spreadsheet->getActiveSheet()->getStyle('A4:I300')->getNumberFormat()
    ->setFormatCode('0');


// Rename worksheet
$spreadsheet->getActiveSheet()->setTitle('Data Penerima Voucher' . date('d-m-Y H'));

// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$spreadsheet->setActiveSheetIndex(0);

// Redirect output to a client’s web browser (Xlsx)
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="Data Penerima Voucher - ' . $username. '.xlsx"');
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
