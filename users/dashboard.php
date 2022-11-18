<?php
include_once("../helper/conn.php");
include_once("../helper/function.php");
include_once("../helper/validation.php");



$login = cekSession();
if ($login == 0) {
    redirect("../auth/login.php");
}
if ($_SESSION["level"] != 2) {
    redirect("../admin/dashboard.php");
}
if (isset($_POST['tambah-data'])) {
    $nik = post("nik");
    $nama = post("nama");
    $alamat = utf8_encode(post("alamat"));
    $no_tlp = post("no_tlp");
    $email  = post("email");
    // $media = post("media");
    $cabang = post("cabang");
    $kode = post("kode");

    // $tgl_pakai = date("Y-m-d", strtotime(post("tgl_pakai")));
    $tgl_pakai = date("Y-m-d");

//     if (!empty($_FILES['media']) && $_FILES['media']['error'] == UPLOAD_ERR_OK) {
//         // Be sure we're dealing with an upload
//         if (is_uploaded_file($_FILES['media']['tmp_name']) === false) {
//             throw new \Exception('Error on upload: Invalid file definition');
//         }


//         if ($size > 1000000) {
//             toastr_set("error", "Maximal 1mb");
//             redirect("save_number.php");
//             exit;
//         }
//  // Rename the uploaded file
//  $uploadName = $_FILES['media']['name'];
//  $ext = strtolower(substr($uploadName, strripos($uploadName, '.') + 1));

//  $allow = ['png', 'jpeg', 'pdf', 'jpg'];
//  if (in_array($ext, $allow)) {
//      if ($ext == "pdf") {
//          $filename = $username . '-' . round(microtime(true)) . $_FILES['media']['name'];
//      }
//      if ($ext == "png") {
//          $filename = $username . '-' . round(microtime(true)) . mt_rand() . '.jpg';
//      }
//      if ($ext == "jpg") {
//          $filename = $username . '-' . round(microtime(true)) . $_FILES['media']['name'];
//      }

//      if ($ext == "jpeg") {
//          $filename = $username . '-' . round(microtime(true)) . $_FILES['media']['name'];
//      }
//  } else {
//      toastr_set("error", "Format png, jpg, pdf only");
//      redirect("save_data_kosan.php");
//      exit;
//  }
//  mkdir('../uploads/base');
//  move_uploaded_file($_FILES['media']['tmp_name'], '../uploads/base/' . $filename);
//  // Insert it into our tracking along with the original name
//  $media = $base_url . "uploads/base/" . $filename;
// } else {
//  $media = null;
// }

$u = $_SESSION['username'];

$cek = mysqli_query($koneksi, "SELECT * FROM user WHERE nik = '$nik' OR no_tlp = '$no_tlp' OR kode = '$kode'");
    if (mysqli_num_rows($cek) > 0) {

        toastr_set("error", "Data user di input sudah tersedia");
        redirect("dashboard.php");
    } else {
        $q = mysqli_query($koneksi, "INSERT INTO user(`nik`,`nama`,`alamat`,`no_tlp`, `email`, `cabang`,`kode`,`tgl_pakai`, `make_by`)
            VALUES('$nik','$nama','$alamat','$no_tlp','$email', '$cabang', '$kode','$tgl_pakai', '$u')");
        toastr_set("success", "Sukses input data user");
        redirect("dashboard.php");
    }

}

if (get("act") == "hapus") {
    $id = get("id");

    $q = mysqli_query($koneksi, "DELETE FROM kosan WHERE id='$id'");
    toastr_set("success", "Berhasil hapus Data Kosan");
    redirect("save_data_kosan.php");
}

if (get("act") == "delete_all") {
    $q = mysqli_query($koneksi, "DELETE FROM kosan");
    toastr_set("success", "Sukses hapus semua Data Kosan");
    redirect("save_data_kosan.php");
}


// Update voucher
// if (post("voucher")) {
//     $voucher = post("voucher");
//     $nama = post("nama");
//     $type = post("type");
//     $u = $_SESSION['username'];
//     mysqli_query($koneksi, "UPDATE `kode_voucher` SET `stats` = 'used' WHERE `kode_voucher`.`kode` = '$voucher'");
//     toastr_set("success", "Berhasil Update voucher.");
//     redirect("dashboard.php");
// }

require_once('../templates/header.php');
?>


<!-- Begin Page Content -->
<div class="content-inner">
    <div class="container-fluid">
        <!-- Begin Page Header-->
        <div class="row">
            <div class="page-header">
                <div class="d-flex align-items-center">
                    <h2 class="page-header-title">Pendataan Voucher</h2>
                    <div>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="dashboard.html"><i class="ti ti-home"></i></a></li>
                            <li class="breadcrumb-item active">Pendataan Voucher</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <!-- Sender -->
        <div class="row">
            <!-- Message -->
            <div class="col-xl-6 mb-4">
                <div class="widget has-shadow">
                    <div class="widget-header bordered no-actions d-flex align-items-center">
                    </div>
                    <div class="widget-body">   
                        <form action="" method="post" enctype="multipart/form-data" name="formInput" onsubmit="validasiEmail();">
                            <label for="">NIK KTP</label>
                            <input class="form-control" type="number" name="nik" placeholder="no. KTP" autocomplete="off" required>
                            <br>
                            <label> Nama Lengkap</label>
                            <input class="form-control" type="text" name="nama" placeholder="Nama sesuai KTP" autocomplete="off" required>
                            <br>
                            <label> Alamat</label>
                            <textarea type="text" name="alamat"  class="form-control" placeholder="alamat KTP" ></textarea>
                            <br>
                            <label> No. Tlp aktif</label>
                            <input class="form-control" type="tel" name="no_tlp" placeholder="08xxxxxxxx" autocomplete="off" required>
                            <br>
                            <label> Email</label>
                            <input class="form-control" type="email" name="email"  placeholder="xxxxx@xxx.com" autocomplete="off" >
                            <br>
                            <label for="">Lokasi cabang penukaran</label>
                            <select class="form-control" name="cabang" style="width: 100%">
                                <?php
                                // $u = $_SESSION['username'];
                                echo '<option>Pilih Cabang Terdekat</option>';
                                $q = mysqli_query($koneksi, "SELECT * FROM branch ");
                                while ($row = mysqli_fetch_assoc($q)) {
                                    echo '<option value="' . $row['branch_name'] . '">' . $row['branch_name'] . '</option>';
                                }
                                ?>
                            </select>
                            <br>
                            <h4 style="margin-left: 50px; font-size:small; color:orangered;">Cek voucher</h4>
                        <table style="margin-left: 50px;">
                            <tr><td>Voucher</td><td><input type="text" onkeyup="isi_otomatis();checkkode();" name="kode" id="kodev" autocomplete="off" style=" font-size:large; text-transform: uppercase; width: 100%" required></td></tr>
                            <tr class="table-info"><td>Status</td><td><input type="text" id="stats" disabled required></td></tr>
                        </table>
                        <br>
                        <br>
                            <button class="btn btn-success" name="tambah-data" id="submit-button" type="submit">Simpan</button>
                        </form>
                    </div>
                </div>
        <!-- Alert Success -->
        <?php if(isset($success)) : ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong >Maaf</strong> Voucher gagal digunakan.
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <!-- <span aria-hidden="true">&times;</span> -->
            </button>
        </div>
        <?php endif ?>

        <!-- Alert Danger -->
        <?php if(isset($danger)) : ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong >Maaf</strong> Voucher gagal digunakan.
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <!-- <span aria-hidden="true">&times;</span> -->
            </button>
        </div>

        <?php endif ?>

        <!-- Alert Invalid -->
        <?php if(isset($invalid)) : ?>
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <strong>Maaf</strong> Kode voucher yang anda masukkan salah.
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <!-- <span aria-hidden="true">&times;</span> -->
            </button>
        </div>

        <?php endif ?>

        <!-- Alert used voucher -->
        <?php if(isset($used)) : ?>
        <div class="alert alert-primary alert-dismissible fade show" role="alert">
            <strong>Maaf</strong> Kode voucher sudah digunakan.
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <!-- <span aria-hidden="true">&times;</span> -->
            </button>
        </div>

        <?php endif ?>
            </div>
        </div>
        <!-- /.container-fluid -->

    </div>
    <?php
    include_once('../templates/footer.php')
    ?>
    <!-- End of Main Content -->
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
    <script>
        <?php

        toastr_show();
        swal_show();

        ?>
        $(document).ready(function() {
            $('#title').html('PENTA PRIMA > Pendataan Voucher')
        });
        document.getElementById("voucher-sid").classList.add("active");
    </script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
        <script type="text/javascript">
            function isi_otomatis(){
                var kode = $("#kodev").val();
                $.ajax({
                    url: '../helper/ajax.php',
                    data:"kode="+kode ,
                }).success(function (data) {
                    var json = data,
                    obj = JSON.parse(json);
                    $('#stats').val(obj.stats);
                });
            }

            function checkkode(){
            var kode=document.getElementById( "kodev" ).value;

            if(kode)
            {
                $.ajax({
                type: 'post',
                url: '../helper/cekdata.php',
                data: {
                kode:kode,
            },
                success: function (response) {
            $( '#kode_status' ).html(response);
            if(response=="OK") {
                $('#submit-button').prop('disabled', true)
            return true;    
            } else {
                $('#submit-button').prop('disabled', false)
                
            return false;   
            }
            }
            });
            }
            else
            {
            $( '#kode_status' ).html("");
            return false;
            }
            }

        </script>

    </body>

    </html>