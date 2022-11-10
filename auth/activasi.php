<?php
include_once("../helper/conn.php");
include_once("../helper/function.php");
$login = cekSession();
if ($login == 1) {
    redirect("../admin/dashboard.php");
}
if ($login == 2) {
    redirect("../users/dashboard.php");
}
if(post("token")){
$token = post("token");
//$t = get("t");
$sql_cek = mysqli_query($koneksi, "SELECT * FROM `account` WHERE `token`='$token' and `aktif`='0'");
$jml_data = mysqli_num_rows($sql_cek);
if ($jml_data > 0) {
    //update data users aktif
   $s = mysqli_query($koneksi, "UPDATE `account` SET `aktif`='1' WHERE `account`.`token`='$token'");
    toastr_set("success", "Your account has been activated");
    header("refresh:4;url=login.php");
} else {
    //data tidak di temukan
    toastr_set("error", "Invalid Token");
	 redirect("activasi.php");
}
if ($s){
	$cek = mysqli_query($koneksi, "SELECT * FROM `account` WHERE `token`='$token' and `aktif`='1'");
	$row = mysqli_fetch_assoc($cek);
		$username = $row['username'];
		$whatsapp = $row['whatsapp'];
		$isipesan = "Congratulations ".$username."!\nYour account has been activated\n\nLogin : ".$base_url."auth/login.php";
	sendMSG($whatsapp, $isipesan, '6285162830081');
} 
}

?>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Register - BLASTJET</title>
    <meta name="description" content="BlastJET Whatsapp Gateway Important and much needed for business">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Google Fonts -->
    <script src="https://ajax.googleapis.com/ajax/libs/webfont/1.6.26/webfont.js"></script>
    <script>
        WebFont.load({
            google: {
                "families": ["Montserrat:400,500,600,700", "Noto+Sans:400,700"]
            },
            active: function() {
                sessionStorage.fonts = true;
            }
        });
    </script>
    <!-- Favicon -->
    <!-- Favicon -->
    <link rel="apple-touch-icon" sizes="180x180" href="../assets/img/favicon.ico">
    <link rel="icon" type="image/png" sizes="32x32" href="../assets/img/favicon.ico">
    <link rel="icon" type="image/png" sizes="16x16" href="../assets/img/favicon.ico">
    <!-- Stylesheet -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.css" integrity="sha512-3pIirOrwegjM6erE5gPSwkUzO+3cTjpnV9lexlNZqvupR64iZBnOOTiiLPb9M36zpMScbmUNIcHUqKD47M719g==" crossorigin="anonymous" />
    <link rel="stylesheet" href="../assets/vendors/css/base/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/vendors/css/base/elisyam-1.5.min.css">
    <!-- Tweaks for older IEs-->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script><![endif]-->
</head>

<body class="bg-fixed-01">
    <!-- End Preloader -->
    <!-- Begin Section -->
    <div class="container-fluid h-100 overflow-y">
        <div class="row flex-row h-100">
            <div class="col-12 my-auto">
                <div class="lock-form mx-auto">
                    <div class="photo-profil">
                        <div class="icon"><i class="la la-unlock"></i></div>
                        <img src="../assets/img/blastjet-sm.png" alt="..." class="img-fluid">
                    </div>
                    <h3>Activate Your Account</h3>
                    <form action="" method="POST">
                        <div class="group material-input">
                            <input type="text" id="token" name="token" required>
                            <span class="highlight"></span>
                            <span class="bar"></span>
                            <label>Kode Aktivasi</label>
                        </div>
                        <div class="button text-center">
                            <button type="submit" class="btn btn-lg btn-gradient-01">
                                Aktivasi
                            </button>
                        </div>
                    </form>
                    <div class="back">
                        Go
                        <a href="login.php">home</a>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Container -->
    </div>
    <!-- End Section -->
    <!-- Begin Vendor Js -->
    <script src="<?= $base_url; ?>vendor/jquery/jquery.min.js"></script>
    <script src="<?= $base_url; ?>vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="<?= $base_url; ?>vendor/jquery-easing/jquery.easing.min.js"></script>
    <!-- End Page Vendor Js -->
    <!-- Begin Page Snippets -->
    <script src="../assets/js/components/tabs/animated-tabs.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js" integrity="sha512-VEd+nq25CkR676O+pLBnDW09R7VQX9Mdiij052gVCp5yVH3jGtH70Ho/UUv4mJDsEdTvqRCFZg0NKGiojGnUCw==" crossorigin="anonymous"></script>
    <script>
        <?php

        toastr_show();

        ?>
    </script>
    <!-- End Page Vendor Js -->
</body>

</html>