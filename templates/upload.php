
<?php

include_once("../helper/conn.php");
include_once("../helper/function.php");

$login = cekSession();
if ($login == 0) {
    redirect("login.php");
}


if (isset($_POST["image"])) {
    $tempdir = "../uploads/profile/";
    if (!file_exists($tempdir))
        mkdir($tempdir);
    $username = $_SESSION['username'];
    $file = $_FILES['image']['name'];
    $size = $_FILES['image']['size'];
    $data = $_POST["image"];
    $image_array_1 = explode(";", $data);
    $image_array_2 = explode(",", $image_array_1[1]);
    $data = base64_decode($image_array_2[1]);

    $imageName = $tempdir . time() . '.png';
    file_put_contents($imageName, $data);
    $query = mysqli_query($koneksi, "UPDATE `account` SET `photo`='$imageName' WHERE `account`.`username` = '$username'");
    redirect('setting.php?blast=Profiles ' . $username);
}
?>