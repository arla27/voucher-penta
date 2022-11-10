<?php
// BLASTJET
include_once("conn.php");
include_once("function.php");
$now = strtotime(date("Y-m-d"));
$q = mysqli_query($koneksi, "SELECT * FROM `account` WHERE `aktif`='1' ORDER BY id");
while ($data = $q->fetch_assoc()) {
      $jadwal = strtotime($data['date_pro']);
       if ($now > $jadwal) {
          $nama = $data['username'];
          $nomor = $data['whatsapp'];
          $pesan = "Hay ".$nama.",
Akun kamu telah kami nonaktifkan karena sudah melampaui batas expired akun silahkan untuk perpanjang akun kamu, dan atau Anda bisa langsung membeli sourcecodenya.

Informasi pembelian source code di https://wa.me/62895353015470

Terimakasih,

Karyaped Media";
$ubah = mysqli_query($koneksi, "UPDATE `account` SET `aktif`='0' WHERE `account`.`username` = '$nama'");
          if($ubah){
                sendMSG($nomor, $pesan, 6285162830081);
              
          }
         }
        };
