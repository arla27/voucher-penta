<?php
include_once("../helper/conn.php");
include_once("../helper/function.php");


$id = get("id");

$q = mysqli_query($koneksi, "DELETE FROM pesan WHERE id='$id'");
redirect("broadcast.php");
