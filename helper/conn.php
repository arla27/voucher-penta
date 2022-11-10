<?php
$host = "localhost";
$username = "root";
$password = "";
$db = "db_voucher";
error_reporting(0);
$koneksi = mysqli_connect($host, $username, $password, $db) or die("Gagal Menyambungkan dengan Database!");

$base_url = "http://localhost/voucher-penta/";
date_default_timezone_set('Asia/Jakarta');
