<?php

error_reporting(E_ALL);

include_once("../helper/conn.php");

if(isset($_POST['kode']))
{
 $kodev=$_POST['kode'];

 $checkdata=" SELECT kode FROM kode_voucher WHERE kode='$kodev' AND stats='not used' ";

 $query = mysqli_query($koneksi, $checkdata);

 if(mysqli_num_rows($query)>0)
 {
  echo "Voucher already exists.";
 }
  else
 {
  echo "OK";
 }
 exit();
}
?>