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
if (post("username")) {
    $username = post("username");
    $whatsapp = post("whatsapp");
    $password = sha1(post("password"));
    $defaultphoto = "../img/profiles1.png";
    $api_key = sha1(date("Y-m-d H:i:s") . rand(100000, 999999));
    $cek_whatsapp = mysqli_query($koneksi, "SELECT * FROM account WHERE whatsapp = '$whatsapp'");
    $cek = mysqli_query($koneksi, "SELECT * FROM account WHERE username = '$username'");
    $token = rand(10000, 99999);
    $isipesan = "Hello! " . post("username") . ".\nTerimakasih sudah mendaftar di BlastJET Whatsapp Gateway.\nKode Aktivasi Anda: " . $token ;
 if ($whatsapp > 14 || is_numeric($whatsapp) == false || $whatsapp < 6 ){
	toastr_set("error", "Nomor Whatsapp tidak valid!");
	 redirect("register.php");
	} else if ($cek->num_rows > 0) {
        toastr_set("error", "Username Sudah dipakai");
	 redirect("register.php");
    } else if ($username < 3) {
        toastr_set("error", "Username Minimal 3 Huruf");
	 redirect("register.php");
    } else if ($cek_whatsapp->num_rows > 0) {
        toastr_set("error", "Nomor Whatsapp Anda sudah terdaftar!");
	 redirect("register.php");
    } else {
        $q = mysqli_query($koneksi, "INSERT INTO `account` (`id`, `username`, `password`, `api_key`, `level`, `chunk`, `photo`, `whatsapp`, `aktif`, `token`) VALUES ('','$username','$password','$api_key','2','10','$defaultphoto','$whatsapp','0','$token')");
    }
    if ($q) {
		sendMSG($whatsapp, $isipesan, '6285162830081');
        toastr_set("success", "Registrasi berhasil");
        header("refresh:2;url=activasi.php");
    }
}

?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Pendaftaran Akun</title>
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

    <link rel="stylesheet" href="../assets/css/styles.css">
    <link rel="stylesheet" href="../assets/vendors/css/base/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/vendors/css/base/elisyam-1.5.min.css">
    <link rel="stylesheet" href="../assets/css/owl-carousel/owl.carousel.min.css">
    <link rel="stylesheet" href="../assets/css/owl-carousel/owl.theme.min.css">
    <link rel="stylesheet" href="../assets/css/datatables/datatables.min.css">
    <link href="<?= $base_url; ?>vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.css" integrity="sha512-3pIirOrwegjM6erE5gPSwkUzO+3cTjpnV9lexlNZqvupR64iZBnOOTiiLPb9M36zpMScbmUNIcHUqKD47M719g==" crossorigin="anonymous" />
    </link>
    <!-- Tweaks for older IEs-->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script><![endif]-->
</head>

<body class="bg-white">
    <!-- Begin Container -->
    <div class="container-fluid no-padding h-100">
        <div class="row flex-row h-100 bg-white">
            <!-- Begin Left Content -->
            <div class="col-xl-8 col-lg-6 col-md-5 no-padding">
                <div class="elisyam-bg" style="background: url(../assets/img/background/background-02.jpg) no-repeat;background-size: cover;">
                    <div class="elisyam-overlay overlay-04"></div>
                    <div class="authentication-col-content mx-auto">
                        <h1 class="gradient-text-01">
                            PENTA PRIMA
                        </h1>
                        <span class="description">
                            Solusi Warnaku
                    </div>
                </div>
            </div>
            <!-- End Left Content -->
            <!-- Begin Right Content -->
            <div class="col-xl-4 col-lg-6 col-md-7 my-auto no-padding">
                <!-- Begin Form -->
                <div class="authentication-form mx-auto">
                    <div class="logo-centered">
                        <a href="">
                            <img src="../assets/img/blastjet-sm.png" alt="logo">
                        </a>
                    </div>
                    <h3>Buat Akun</h3>
                    <form class="user" method="POST">
                    <div class="group material-input">
                            <input type="text" name="nik" id="nik" required>
                            <span class="highlight"></span>
                            <span class="bar"></span>
                            <label>NIK KTP</label>
                        </div>
                        <div class="group material-input">
                            <input type="text" name="name" id="name" required>
                            <span class="highlight"></span>
                            <span class="bar"></span>
                            <label>Nama Lengkap (Sesuai KTP)</label>
                        </div>
                        <div class="group material-input">
                            <input type="text" name="alamat" id="alamat" required>
                            <span class="highlight"></span>
                            <span class="bar"></span>
                            <label>Alamat (Sesuai KTP)</label>
                        </div>
                        <div class="group material-input">
                            <input type="text" name="email" id="email" required>
                            <span class="highlight"></span>
                            <span class="bar"></span>
                            <label>Email</label>
                        </div>
                        <div class="group material-input">
                            <input type="text" name="whatsapp" id="whatsapp" required>
                            <span class="highlight"></span>
                            <span class="bar"></span>
                            <label>No Whatsapp</label>
                        </div>
                        <div class="group material-input">
                            <input type="text" name="username" id="username" required>
                            <span class="highlight"></span>
                            <span class="bar"></span>
                            <label>Username</label>
                        </div>
                        <div class="group material-input">
                            <input type="password" name="password" id="password" required>
                            <span class="highlight"></span>
                            <span class="bar"></span>
                            <label>Password</label>
                        </div>
                        <div class="row">
                            <div class="col text-left">
                                <div class="styled-checkbox">
                                    <input type="checkbox" name="checkbox" id="agree">
                                    <label for="agree">I Accept <a href="#">Terms and Conditions</a></label>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-gradient-04 btn-user btn-block">
                            Register
                        </button>
                    </form>
                    <div class="register">
                        Already have an account?
                        <br>
                        <a href="login.php">Sign In</a>
                    </div>
                </div>
                <!-- End Form -->
            </div>
            <!-- End Right Content -->
        </div>
        <!-- End Row -->
    </div>
    <!-- Bootstrap core JavaScript-->
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
		swal_show();

        ?>
    </script>
</body>

</html>