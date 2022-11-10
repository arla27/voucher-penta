<?php 

$conn = mysqli_connect("localhost", "root", "", "db_voucher");

function query($ambil) {
    global $conn;

    $result = mysqli_query($conn, $ambil);
    $rows = [];

    while ($row = mysqli_fetch_assoc($result)) {
        $rows[] = $row;
    }

    return $rows;
}

function cari($kode) {
    global $conn;
    $dataFound = mysqli_query($conn, "SELECT * FROM kode_voucher WHERE kode = '$kode' ");
    $countRow = mysqli_num_rows($dataFound);
    if($countRow != 0) {
        $ambil = query("SELECT * FROM kode_voucher WHERE kode = '$kode' ");
        if($ambil[0]['kode'] == $kode) {
            return $ambil[0]['id'];
        } else {
            return 0;
        }
    }
}

function cek($kode) {
    global $conn;
    $dataFound = mysqli_query($conn, "SELECT * FROM kode_voucher WHERE id = '$kode' ");
    $countRow = mysqli_num_rows($dataFound);
    if($countRow != 0) {
        $ambil = query("SELECT * FROM kode_voucher WHERE id = '$kode' ");
        if($ambil[0]['id'] == $kode) {
            return $ambil[0]['id'];
        } else {
            return 0;
        }
    }
}
// function hapus($id) {
// 	global $conn;
// 	$result = mysqli_query($conn,"DELETE FROM kode_voucher WHERE id = $id");
// 	return mysqli_affected_rows($conn);
// }

function ubah($data) {

	global $conn;
	$id = $data["id"];
    $date = date("Y-m-d");

	//ambil data
	$insert = "UPDATE kode_voucher SET stats = 'used', date_used = '$date' WHERE id = $id";
	$result = mysqli_query($conn,$insert);

	return mysqli_affected_rows($conn);
}

?>