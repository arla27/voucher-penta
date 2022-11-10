<?php
include_once("../helper/conn.php");
include_once("../helper/function.php");
include_once("../helper/validation.php");


$login = cekSession();
if ($login == 0) {
    redirect("../auth/login.php");
}
if ($_SESSION["level"] != 1) {
    redirect("../users/reedem.php");
}



?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title id="title"></title>
    <meta name="description" content="Penta Prima Solusi Warnaku">
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

    <!-- <link rel="stylesheet" href="<?= $base_url; ?>assets/css/style.css"> -->
    <!-- <link rel="stylesheet" href="../assets/vendors/css/base/bootstrap.min.css"> -->
    <link rel="stylesheet" href="../assets/vendors/css/base/elisyam-1.5.min.css">
    <link rel="stylesheet" href="../assets/css/owl-carousel/owl.carousel.min.css">
    <link rel="stylesheet" href="../assets/css/owl-carousel/owl.theme.min.css">
    <link rel="stylesheet" href="../assets/css/datatables/datatables.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.css" />
    <link rel="stylesheet" href="<?= $base_url; ?>assets/vendors/css/base/prism.css">
    <link rel="stylesheet" href="<?= $base_url; ?>assets/css/croppie.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/slim-select/1.27.1/slimselect.min.css" rel="stylesheet">
    </link>
        <!-- Bootstrap CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

<!-- CSS -->
<link rel="stylesheet" href="<?= $base_url; ?>assets/css/reedemstyle.css">
    <!-- Tweaks for older IEs-->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script><![endif]-->
</head>

<body>
    <div class="page">
        <!-- Begin Header -->
        <header class="header">
            <nav class="navbar fixed-top">
                <!-- Begin Search Box-->
                <div class="search-box">
                    <button class="dismiss"><i class="ion-close-round"></i></button>
                    <form id="searchForm" action="#" role="search">
                        <input type="search" placeholder="Search something ..." class="form-control">
                    </form>
                </div>
                <!-- End Search Box-->
                <!-- Begin Topbar -->
                <div class="navbar-holder d-flex align-items-center align-middle justify-content-between">
                    <!-- Begin Logo -->
                    <div class="navbar-header">
                        <a href="dashboard.php" class="navbar-brand">
                            <div class="brand-image brand-big">
                                <img src="../assets/img/blastjet-sm.png" alt="logo" class="logo-big" style="width: 50px;"> PENTA PRIMA
                            </div>
                        </a>
                        <!-- Toggle Button -->
                        <a id="toggle-btn" href="#" class="menu-btn active">
                            <span></span>
                            <span></span>
                            <span></span>
                        </a>
                        <!-- End Toggle -->
                    </div>
                    <!-- End Logo -->
                    <!-- Begin Navbar Menu -->
                    <ul class="nav-menu list-unstyled d-flex flex-md-row align-items-md-center pull-right">
                        <!-- User --> <?php
                                        $username = $_SESSION['username'];
                                        $q = mysqli_query($koneksi, "SELECT * FROM account WHERE username = '$username'");
                                        while ($row = mysqli_fetch_assoc($q)) { ?>
                            <li class="nav-item dropdown">

                                <a id="user" rel="nofollow" data-target="#" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="nav-link">
                                    <img src="<?= $row['photo']; ?>" alt="..." class="avatar rounded-circle border">
                                </a>

                                <ul aria-labelledby="user" class="user-size dropdown-menu">
                                    <li class="welcome">
                                        <a href="<?= $base_url; ?>users/setting.php?blast=ApiKey" class="edit-profil"><i class="la la-gear"></i></a>
                                        <img src="<?= $row['photo']; ?>" alt="..." class="rounded-circle border">
                                    </li>
                                    <li>
                                        <h5 class="text-center"><?= $_SESSION['username'] ?></h5>
                                    </li>
                                    <li class="separator"></li>
                                    <li><a rel="nofollow" href="#" data-target="#logoutModal" data-toggle="modal" class="dropdown-item logout text-center"><i class="ti-power-off"></i></a></li>
                                </ul>
                            </li><?php } ?>

                        <!-- End User -->
                    </ul>
                    <!-- End Navbar Menu -->
                </div>
                <!-- End Topbar -->
            </nav>
        </header>
        <!-- End Header -->
        <!-- sidebar session -->
        <?php
        $login = cekSession();
        if ($_SESSION["level"] != 2) {
            include_once("../templates/admin_sidebar.php");
        }
        if ($_SESSION["level"] != 1) {
            include_once("../templates/user_sidebar.php");
        }
        ?>
        <!-- End Sidebar -->
        <!-- Logout Modal-->
        <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Anda Yakin Ingin Logout?</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">Pilih Tombol Logout di Bawah Ini, Jika Anda Ingin Mengakhiri Sesi Anda Saat Ini..</div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                        <a class="btn btn-gradient-01" href="<?= $base_url; ?>auth/logout.php">Logout</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
<div class="center p-5 shadow bg-light">
        <h2>Masukkan Kode Voucher</h2>
        <form method="POST">
          <div class="row g-3 align-items-center mt-3">
            <div class="col-auto">
              <label for="kode" class="col-form-label">Kode Voucher</label>
            </div>
            <div class="col-auto">
              <input type="text" id="kode" class="form-control" name="kode"  style="font-width" aria-describedby="passwordHelpInline" required>
            </div>
            <div class="col-auto">
              <span id="passwordHelpInline" class="form-text">
                Kode voucher hanya bisa dipakai sekali
              </span>
            </div>
            <button type="submit" name="submit" class="btn btn-primary">Pakai Kode</button>
          </div>
        </form>
    </div>

    <!-- Alert Success -->
    <?php if(isset($success)) : ?>
      <div style="position: relative; margin-left: 250px; margin-right: 50px; margin-top: 30px;" class="alert alert-success alert-dismissible fade show" role="alert">
        <strong>Selamat!</strong> Voucher berhasil digunakan.
        <!-- <?php echo $tampil['nik']?> -->
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    <?php endif ?>

    <!-- Alert Danger -->
    <?php if(isset($danger)) : ?>
      <div  style="position: relative; margin-left: 250px; margin-right: 50px; margin-top: 30px;" class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong >Maaf</strong> Voucher gagal digunakan.
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    <?php endif ?>

    <!-- Alert Invalid -->
    <?php if(isset($invalid)) : ?>
      <div style="position: relative; margin-left: 250px; margin-right: 50px; margin-top: 30px;" class="alert alert-warning alert-dismissible fade show" role="alert">
        <strong>Maaf</strong> Kode voucher yang anda masukkan salah.
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    <?php endif ?>

    <!-- Alert used voucher -->
    <?php if(isset($used)) : ?>
      <div style="position: relative; margin-left: 250px; margin-right: 50px; margin-top: 30px;" class="alert alert-primary alert-dismissible fade show" role="alert">
        <strong>Maaf</strong> Kode voucher sudah digunakan.
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    <?php endif ?>






    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>


<!-- Bootstrap core JavaScript-->
<script src="<?= $base_url; ?>vendor/jquery/jquery.min.js"></script>
<script src="<?= $base_url; ?>vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

<!-- Core plugin JavaScript-->
<script src="<?= $base_url; ?>vendor/jquery-easing/jquery.easing.min.js"></script>
<script src="<?= $base_url; ?>assets/vendors/js/app/app.min.js"></script>
<!-- Page level plugins -->
<script src="<?= $base_url; ?>vendor/chart.js/Chart.min.js"></script>
<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/3.1.0/socket.io.js" integrity="sha512-+l9L4lMTFNy3dEglQpprf7jQBhQsQ3/WvOnjaN/+/L4i0jOstgScV0q2TjfvRF4V+ZePMDuZYIQtg5T4MKr+MQ==" crossorigin="anonymous"></script> -->
<script src="../node_modules/socket.io/client-dist/socket.io.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js" integrity="sha512-VEd+nq25CkR676O+pLBnDW09R7VQX9Mdiij052gVCp5yVH3jGtH70Ho/UUv4mJDsEdTvqRCFZg0NKGiojGnUCw==" crossorigin="anonymous">
</script>
<script>
    <?php

    toastr_show();

    ?>
    $(document).ready(function() {
        $('#title').html('PENTA PRIMA > Connected')
    });
    document.getElementById("reedem-sid").classList.add("active");
</script>

</body>

</html>