<?php 

include 'functions.php';

if (isset($_POST["submit"])) {
    $findID = cari($_POST["kode"]);
    // $id_voucher    =cek($_GET["kode"]);
    if($findID != 0) {
        $voucher = query("SELECT * FROM kode_voucher WHERE id = '$findID'");
        // $tampil   =query("SELECT * FROM pengambil WHERE id_voucher='$id_voucher'");
        if( $voucher[0]['stats'] == "used") {
            $used = true;

        } else {
            if (ubah($voucher[0]) > 0) {
                $success = true;
            } else {
                $danger = true;           
            }
        }
        
    } else {
        $invalid = true;
    }

}


?>