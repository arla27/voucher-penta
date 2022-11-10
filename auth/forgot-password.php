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
function generate_password($len = 8)
{
    $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
    $password = substr(str_shuffle($chars), 0, $len);
    return $password;
}
if (post("username")) {
    $username = post("username");
    $whatsapp = post("whatsapp");
    $password = generate_password();
    $encpt_password = sha1($password);
    $cek_whatsapp = mysqli_query($koneksi, "SELECT * FROM account WHERE whatsapp = '$whatsapp'");
    $cek = mysqli_query($koneksi, "SELECT * FROM account WHERE username = '$username'");
    $gpass = mysqli_query($koneksi, "UPDATE `account` SET `password` = '$encpt_password' WHERE `account`.`username`='$username'");
    $isipesan = "Berhasil ganti password.\nPassword Anda sekarang: " . $password;
    if ($cek->num_rows > 0) {
        if ($cek_whatsapp->num_rows > 0) {
            toastr_set("success", "Password telah di kirim ke No Whatsapp Anda");
            $data = [
                'api_key' => '0b15165928854b92f9d20da125ed1b0d3cfb8a40',
                'sender'  => '62895353015470', //pastikan sudah scan nomor tersebut dan berhasil terhubung
                'number'  => $whatsapp,
                'message' => $isipesan
            ];

            $curl = curl_init();
            curl_setopt_array(
                $curl,
                array(
                    CURLOPT_URL => "https://api.pastiada.my.id/send-message.php",
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => "",
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => "POST",
                    CURLOPT_POSTFIELDS => json_encode($data)
                )
            );

            $response = curl_exec($curl);

            curl_close($curl);
        } else {
            toastr_set("error", "Nomor Whatsapp tidak terdaftar");
        }
    } else {
        toastr_set("error", "Username/Nomor Whatsapp tidak terdaftar");
    }
}


?>


<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Login - BLASTJET</title>
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

<body class="bg-fixed-02" style="background: linear-gradient(135deg, rgba(46, 52, 81, .4) 0%, rgba(205, 95, 109, .95) 100%), url(../assets/img/background/background-01.jpg) no-repeat center center">
    <!-- Begin Container -->
    <div class="container-fluid h-100 overflow-y">
        <div class="row flex-row h-100">
            <div class="col-12 my-auto">
                <div class="password-form mx-auto">
                    <div class="logo-centered">
                        <a href="">
                            <img src="../assets/img/blastjet-sm.png" alt="logo">
                        </a>
                    </div>
                    <h3>Password Recovery</h3>
                    <form method="POST">
                        <div class="group material-input">
                            <input type="text" id="username" name="username" required>
                            <span class="highlight"></span>
                            <span class="bar"></span>
                            <label>Username</label>
                        </div>
                        <div class="group material-input">
                            <input type="text" id="whatsapp" name="whatsapp" required>
                            <span class="highlight"></span>
                            <span class="bar"></span>
                            <label>No Whatsapp</label>
                        </div>
                        <div class="button text-center">
                            <button type="submit" name="forgot" class="btn btn-lg btn-gradient-01">
                                Lupa Password
                            </button>
                        </div>
                    </form>
                    <div class="back">
                        <a href="login.php">Sign In</a>
                    </div>
                </div>
            </div>
            <!-- End Col -->
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

        ?>
    </script>
</body>

</html>