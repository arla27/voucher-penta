<?php
include_once("../helper/conn.php");
include_once("../helper/function.php");
include_once("../helper/validation.php");


$login = cekSession();
if ($login == 0) {
    redirect("login.php");
}
if ($_SESSION["level"] != 1) {
    redirect("../users/dashboard.php");
}
if (isset($_POST['tambah-data'])) {
    $nik = post("nik");
    $nama = post("nama");
    $alamat = utf8_encode(post("alamat"));
    $no_tlp = post("no_tlp");
    $email  = post("email");
    // $media = post("media");
    $cabang = post("cabang");
    $voucher = post("voucher");

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

$cek = mysqli_query($koneksi, "SELECT * FROM user WHERE nik = '$nik' OR no_tlp = '$no_tlp'");
    if (mysqli_num_rows($cek) > 0) {

        swal_set("error", "Data Pengguna user di input sudah tersedia");
        redirect("voucher_customer.php");
    } else {
        $q = mysqli_query($koneksi, "INSERT INTO user(`nik`,`nama`,`alamat`,`no_tlp`, `email`, `cabang`,`voucher`,`tgl_pakai`, `make_by`)
            VALUES('$nik','$nama','$alamat','$no_tlp','$email', '$cabang', '$voucher','$tgl_pakai', '$u')");
        swal_set("success", "Sukses input data penghuni user");
        redirect("voucher_customer.php");
    }

}

if (get("act") == "hapus") {
    $id = get("id");

    $q = mysqli_query($koneksi, "DELETE FROM kosan WHERE id='$id'");
    swal_set("success", "Berhasil hapus Data Kosan");
    redirect("save_data_kosan.php");
}

if (get("act") == "delete_all") {
    $q = mysqli_query($koneksi, "DELETE FROM kosan");
    swal_set("success", "Sukses hapus semua Data Kosan");
    redirect("save_data_kosan.php");
}

//update data kosan
if (isset($_POST['update-kosan'])) {
    $no_tlp = post("no_tlp");
    $nama = post("nama");
    $nik = post("nik");
    $username = post("username");
    $media = post("media");
    $jenis_kamar = post("jenis_kamar");
    $harga = post("harga");
    $alamat = post("alamat");
    $tgl_pakai = date("Y-m-d", strtotime(post("tgl_pakai")));
    $u = $_SESSION['username'];
    $update = mysqli_query($koneksi, "UPDATE `kosan` SET `no_tlp` = '$no_tlp',`nama` = '$nama',`jenis_kamar` = '$jenis_kamar',`harga` = '$harga',`alamat` = '$alamat',`tgl_pakai` = '$tgl_pakai' WHERE `kosan`.`no_tlp` = '$no_tlp'");
    if ($update) {
        swal_set("success", "Berhasil update data penghuni kosan");
    } else {
        swal_set("error", "Gagal update data penghuni kosan");
    }
}

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
                        <h4>Data Penerima</h4>
                    </div>
                    <div class="widget-body">
                        <form action="" method="post" enctype="multipart/form-data">
                            <label for="">NIK KTP</label>
                            <input class="form-control" type="text" name="nik" placeholder="no. KTP" required>
                            <br>
                            <label> Nama Lengkap</label>
                            <input class="form-control" type="text" name="nama" placeholder="Nama sesuai KTP" required>
                            <br>
                            <label> Alamat</label>
                            <textarea type="text" name="alamat"  class="form-control" placeholder="alamat KTP" ></textarea>
                            <br>
                            <label> No. Tlp aktif</label>
                            <input class="form-control" type="text" name="no_tlp" placeholder="08xxxxxxxx" required>
                            <br>
                            <label> Email</label>
                            <input class="form-control" type="text" name="email" placeholder="xxxxx@xxx.com" >
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
                            <br>
                            <label> Voucher</label>
                            <input class="form-control" type="text" name="voucher" placeholder="xxxxxxxxxx" >
                            <br>
                            <button class="btn btn-success" name="tambah-data" type="submit">Simpan</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-xl-6 mb-4">
                <div class="widget has-shadow">
                    <div class="widget-header bordered no-actions d-flex align-items-center">
                        <h4>Cek Voucher</h4>
                    </div>
                <div class="widget-body">
                <form method="POST">
                    <div class="row g-3 align-items-center mt-3">
                        <div class="col-auto">
                            <label for="kode" class="col-form-label">Kode Voucher</label>
                        </div>
                        <div class="col-auto">
                            <input type="text" id="kode" class="form-control" name="kode"  style=" font-size:large; width: 100%" aria-describedby="passwordHelpInline" required>
                        </div>
                        <br>
                        <div class="col-auto">
                            <span id="passwordHelpInline" class="form-text">
                            Kode voucher hanya bisa dipakai sekali
                            </span>
                        </div>
                        <br>
                        <br>
                        <br>
                        <br>
                        <button type="submit" name="submit" style=" margin:5px;" class="btn btn-primary">Pakai Kode</button>
                    </div>
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
      <!-- <div style="position: relative; margin-left: 30px; margin-right: 30px; margin-top: 30px;" class="alert alert-success alert-dismissible fade show" role="alert">
        <strong>Selamat!</strong> Voucher berhasil digunakan.
         <?php echo $tampil['nik']?> 
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div> -->
        <?php endif ?>

        <!-- Alert Danger -->
        <?php if(isset($danger)) : ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong >Maaf</strong> Voucher gagal digunakan.
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <!-- <span aria-hidden="true">&times;</span> -->
            </button>
        </div>
      <!-- <div  style="position: relative; margin-left: 30px; margin-right: 30px; margin-top: 30px;" class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong >Maaf</strong> Voucher gagal digunakan.
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div> -->
        <?php endif ?>

        <!-- Alert Invalid -->
        <?php if(isset($invalid)) : ?>
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <strong>Maaf</strong> Kode voucher yang anda masukkan salah.
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <!-- <span aria-hidden="true">&times;</span> -->
            </button>
        </div>
      <!-- <div style="position: relative; margin-left: 30px; margin-right: 30px; margin-top: 30px;" class="alert alert-warning alert-dismissible fade show" role="alert">
        <strong>Maaf</strong> Kode voucher yang anda masukkan salah.
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div> -->
        <?php endif ?>

        <!-- Alert used voucher -->
        <?php if(isset($used)) : ?>
        <div class="alert alert-primary alert-dismissible fade show" role="alert">
            <strong>Maaf</strong> Kode voucher sudah digunakan.
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <!-- <span aria-hidden="true">&times;</span> -->
            </button>
        </div>
      <!-- <div style="position: relative; margin-left: 30px; margin-right: 30px; margin-top: 30px;" class="alert alert-primary alert-dismissible fade show" role="alert">
        <strong>Maaf</strong> Kode voucher sudah digunakan.
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div> -->
        <?php endif ?>

        </div>



            <!-- <div class="col-xl-6 mb-4">
                <div class="widget has-shadow">
                    <div class="widget-header bordered no-actions d-flex align-items-center">
                        <h4>Cek Voucher</h4>
                    </div>
                    <div class="widget-body">
                        <form action="" method="post">
                            <label for="">Kode Voucher</label><em id="stats" style="color:red;font-size:10px"><?= $row['stats']?></em>
                            <input class="form-control" type="text" name="kodev"  onkeyup="isi_otomatis()" id="kode" style=" font-size:large; width: 100%">
                            </input>
                            <label> Status</label>
                            <input class="form-control" type="text" id="stats" name="status" placeholder="" disabled >
                            <br>
                             <label> Pesan </label>
                            <input name="pesan" type="text" required class="form-control">
                            <br>
                            <label> Link Media </label> <em style="color:red;font-size:10px">*support png, jpg dan pdf</em>
                            <input class="form-control" type="text" name="linkmedia" required>
                            <br> -->
                            <!-- <label> Type File </label>
                    <input class="form-control" type="text" name="filetype" required>
                    <p class="small-text">jpg/png/jpeg/pdf</p>
                    <br> 
                            <button class="btn btn-success" type="submit">Reedem</button>
                        </form>
                    </div>
                </div>
            </div> -->


            <!-- <div class="col-xl-6 mb-4">
                <div class="widget has-shadow">
                    <div class="widget-header bordered no-actions d-flex align-items-center">
                        <h4>Send Button (new)</h4>
                    </div>
                    <div class="widget-body">
                        <form action="" method="post">
                            <label for="">Sender</label>
                            <select class="form-control" name="bsndr" style="width: 100%">
                                <?php
                                $u = $_SESSION['username'];
                                $q = mysqli_query($koneksi, "SELECT * FROM device WHERE pemilik='$u'");
                                while ($row = mysqli_fetch_assoc($q)) {
                                    echo '<option value="' . $row['nik'] . '">' . $row['nik'] . '</option>';
                                }
                                ?>
                            </select>
                            <label> nik Tujuan</label>
                            <input class="form-control" type="text" name="bnmr" placeholder="08xxxxxxxx" required>
                            <br>
                            <label> Pesan </label>
                            <input name="bpsn" type="text" required class="form-control">
                            <br>
                            <label> footer </label>
                            <input name="bfooter" type="text" required class="form-control">
                            <br>
                            <label> button1 </label>
                            <input name="btn1" type="text" required class="form-control">
                            <br>
                            <label> button2 </label>
                            <input name="btn2" type="text" required class="form-control">
                            <br>
                            <button class="btn btn-success" type="submit">Kirim</button>
                        </form>
                    </div>
                </div>
            </div> -->
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

        ?>
        $(document).ready(function() {
            $('#title').html('PENTA PRIMA > Pendataan Voucher')
        });
        document.getElementById("voucher-sid").classList.add("active");
    </script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
        <script type="text/javascript">
            function isi_otomatis(){
                var kode = $("#kode").val();
                $.ajax({
                    url: '../helper/ajax.php',
                    data:"kode="+kode ,
                }).success(function (data) {
                    var json = data,
                    obj = JSON.parse(json);
                    $('#stats').val(obj.stats);
                });
            }
        </script>
    </body>

    </html>