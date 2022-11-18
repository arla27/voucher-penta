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
// $username ='admin';
$tanggal=date('d-m-Y H');
//Font Color
$spreadsheet->getActiveSheet()->getStyle('A3:F3')
    ->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_WHITE);

//Align
$spreadsheet->getActiveSheet()->getStyle('A1:F2')
    ->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
//Widht
$spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(5, 'px');
$spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(15, 'px');
$spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(15, 'px');
$spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(10, 'px');
$spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(15, 'px');
$spreadsheet->getActiveSheet()->getColumnDimension('F')->setWidth(10, 'px');


//Align
$spreadsheet->getActiveSheet()->getStyle('A1:F2')
    ->getAlignment()->setHorizontal('center');

//Align
$spreadsheet->getActiveSheet()->getStyle('B4:F600')
    ->getAlignment()->setHorizontal('left');


// Background color
$spreadsheet->getActiveSheet()->getStyle('A3:F3')->getFill()
    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
    ->getStartColor()->setARGB('051DFF');


// Header Tabel
$spreadsheet->setActiveSheetIndex(0)->mergeCells('A1:F2')
    ->setCellValue('A1', 'DATA VOUCHER')
    ->setCellValue('A3', 'No.')
    ->setCellValue('B3', 'Voucher')
    ->setCellValue('C3', 'Nominal')
    ->setCellValue('D3', 'Status')
    ->setCellValue('E3', 'Date Used')
    ->setCellValue('F3', 'Created by');


$i = 4;
$no = 1;
$res1 = mysqli_query($koneksi, "SELECT * FROM kode_voucher  ");
// ORDER BY id ASC
while ($row = $res1->fetch_assoc()) {
    $spreadsheet->setActiveSheetIndex(0)
        ->setCellValue('A' . $i, $no)
        ->setCellValue('B' . $i, $row['kode'])
        ->setCellValue('C' . $i, $row['nominal'])
        ->setCellValue('D' . $i, $row['stats'])
        ->setCellValue('E' . $i, $row['date_used'])
        ->setCellValue('F' . $i, $row['make_by']);

    $i++;
    $no++;
}

// Format
$spreadsheet->getActiveSheet()->getStyle('A4:F600')->getNumberFormat()
    ->setFormatCode('0');

// Rename worksheet
$spreadsheet->getActiveSheet()->setTitle('Data Voucher ' . date('d-m-Y H'));

// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$spreadsheet->setActiveSheetIndex(0);

// Redirect output to a client’s web browser (Xlsx)
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="Data Voucher - ' . $username . '.xlsx"');
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
