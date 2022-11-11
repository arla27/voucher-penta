<?php

//membuat koneksi ke database
$koneksi = mysqli_connect("localhost", "root", "", "db_voucher");

//variabel nim yang dikirimkan form.php
$kode = $_GET['kode'];

//mengambil data
$query = mysqli_query($koneksi, "select * from kode_voucher where kode='$kode'");
$ambil = mysqli_fetch_array($query);
$data = array(

            'stats'    =>  @$ambil['stats']);

//tampil data
echo json_encode($data);
?>
