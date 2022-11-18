<?php
include_once("../helper/conn.php");
include_once("../helper/function.php");
$u = $_SESSION['username'];
$login = cekSession();
if ($login == 0) {
    redirect("login.php");
}
if ($_SESSION["level"] != 1) {
    redirect("../users/setting.php?blast=ApiKey");
}
$ceksql = mysqli_query($koneksi, "SELECT * FROM account WHERE username='$u'");
$row = mysqli_fetch_assoc($ceksql);

if (post("password")) {
    $password2 = post('newpassword2');
    $password = post('password');
    $password1 = $row['password'];
    // Validasi kekuatan password
    $uppercase = preg_match('@[A-Z]@', $password);
    $lowercase = preg_match('@[a-z]@', $password);
    $number    = preg_match('@[0-9]@', $password);

    if (sha1($password) != $password1) {
        toastr_set("error", "Password lama salah");
        redirect('setting.php?blast=changePassword/' . $u);
    } else if (strlen($password2) < 5) {
        toastr_set("error", "Password setidaknya harus 5 karakter");
        redirect('setting.php?blast=changePassword/' . $u);
    } else if (post("newpassword") != post("newpassword2")) {
        toastr_set("error", "Password tidak sesuai");
        redirect('setting.php?blast=changePassword/' . $u);
        //exit;
    } else {
        $p = sha1($password2);
        $q = mysqli_query($koneksi, "UPDATE account SET password = '$p' WHERE username = '$u' ");
        if ($q) {
            toastr_set("success", "Ganti password berhasil");
            redirect('setting.php?blast=changePassword/' . $u);
        }
    }
}


if (post("chunk")) {
    $chunk = post("chunk");
    if ($chunk > 100) {
        toastr_set("error", "Maximal pesan masal adalah 100 per menit");
        redirect('setting.php?blast=ApiKey/' . $u);
    } else {
        mysqli_query($koneksi, "UPDATE account SET chunk = '$chunk' WHERE username='$u'");
        toastr_set("success", "Max Sending Berhasil di Set");
        redirect('setting.php?blast=ApiKey/' . $u);
    }
}



if (post("idnomor")) {
    $id = post("idnomor");
    $url = post("urlwebhook");
    $update = mysqli_query($koneksi, "UPDATE device SET link_webhook = '$url' WHERE nomor = '$id'");
    toastr_set("success", "Webhook berhasil di pasang");
    redirect('setting.php?blast=setWebHook/' . $u);
}

require('../templates/header.php');
?>


<!-- Begin Content -->
<div class="content-inner profile">
    <div class="container-fluid">
        <!-- Begin Page Header-->
        <div class="row">
            <div class="page-header">
                <div class="d-flex align-items-center">
                    <h2 class="page-header-title">Settings</h2>
                    <div>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="db-default.html"><i class="ti ti-home"></i></a></li>
                            <li class="breadcrumb-item active">Settings</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <?php
        include_once('../settings/demo.html')
        ?>
        <!-- End Page Header -->
        <div class="row flex-row">
            <div class="col-xl-3">
                <!-- Begin Widget -->
                <div class="widget has-shadow">
                    <div class="widget-body">
                        <div class="mt-5">
                            <img src="../assets/img/blastjet-sm.png" alt="..." style="width: 120px;" class="avatar d-block mx-auto">
                        </div>
                        <h3 class="text-center mt-3 mb-1">PENTA PRIMA</h3>
                        <p class="text-center">Solusi Warnaku</p>
                        <div class="em-separator separator-dashed"></div>
                        <ul class="nav flex-column"><?php $username = $_SESSION['username']; ?>
                            <li class="nav-item">
                                <a class="nav-link" href="setting.php?blast=Profiles/<?= $username ?>"><i class="la la-user align-middle pr-2"></i>Profile</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="setting.php?blast=changePassword/<?= $username ?>"><i class="la la-lock align-middle pr-2"></i>Change Password</a>
                            </li>
                        </ul>
                    </div>
                </div>
                <!-- End Widget -->
            </div>

            <div id="uploadimageModal" class="modal" role="dialog">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" id="myModalLabel">Crop &amp; Upload Gambar</h4>
                            <button type="button" class="close" data-dismiss="modal">
                                <span aria-hidden="true">&times;</span>
                                <span class="sr-only">Close</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-12 text-center">
                                    <div id="image_demo"></div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-success crop_image">Crop &amp; Upload</button>
                        </div>
                    </div>
                </div>
            </div>

            <?php
            if (isset($_GET['blast'])) {
                $page = $_GET['blast'];

                switch ($page) {
                    case 'Profiles/' . $username:
                        $title = "Edit Profile";
                        include "../settings/profile.php";
                        break;
                    case 'changePassword/' . $username:
                        $title = "Set New Password";
                        include "../settings/account.php";
                        break;
                    default:
                        include "../settings/profile.php";
                        break;
                }
            }

            ?>
        </div>
    </div>

    <?php
    include_once('../templates/footer.php')
    ?>
</div>
<!-- Bootstrap core JavaScript-->
<script src="<?= $base_url; ?>vendor/jquery/jquery.min.js"></script>
<script src="<?= $base_url; ?>vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- Core plugin JavaScript-->
<script src="<?= $base_url; ?>vendor/jquery-easing/jquery.easing.min.js"></script>
<script src="<?= $base_url; ?>assets/vendors/js/app/app.min.js"></script>
<!-- Page level plugins -->
<script src="<?= $base_url; ?>vendor/datatables/jquery.dataTables.min.js"></script>
<script src="<?= $base_url; ?>vendor/datatables/dataTables.bootstrap4.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js" integrity="sha512-VEd+nq25CkR676O+pLBnDW09R7VQX9Mdiij052gVCp5yVH3jGtH70Ho/UUv4mJDsEdTvqRCFZg0NKGiojGnUCw==" crossorigin="anonymous"></script>
<script src="<?= $base_url; ?>assets/js/croppie.js"></script>

<script>
    <?php

    toastr_show();

    ?>
    $(document).ready(function() {
        $('#title').html('PENTA PRIMA > Settings')
    });
    document.getElementById("setting-sid").classList.add("active");
</script>
<!-- End Sizing -->
<script>
    $(document).ready(function() {
        $image_crop = $('#image_demo').croppie({
            viewport: {
                width: 200,
                height: 200,
                type: 'square' //circle

            },
            boundary: {
                width: 300,
                height: 300
            },
            maxZoomedCropWidth: 400,
            showZoomer: true,
            mouseWheelZoom: 'ctrl'
        });

        $('#upload_image').on('change', function() {
            var reader = new FileReader();
            reader.onload = function(event) {
                $image_crop.croppie('bind', {
                    url: event.target.result
                }).then(function() {
                    console.log('jQuery bind complete');
                });
            }
            reader.readAsDataURL(this.files[0]);
            $('#uploadimageModal').modal('show');
        });

        $('.crop_image').click(function(event) {
            $image_crop.croppie('result', {
                type: 'canvas',
                size: 'viewport'
            }).then(function(response) {
                $.ajax({
                    url: "../templates/upload.php",
                    type: "POST",
                    data: {
                        "image": response
                    },
                    success: function(data) {
                        $('#uploadimageModal').modal('hide');
                        $('#uploaded_image').html(data);
                    }
                });
            })
        });

    });
</script>

</body>

</html>