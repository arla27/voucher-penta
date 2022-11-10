<?php
include_once("../helper/conn.php");
include_once("../helper/function.php");


$login = cekSession();
if ($login == 0) {
    redirect("login.php");
}

if (post("pesan")) {
    $nomor = post("nomor");
    $pesan = post("pesan");
    $sender = post("sender");
    $kuy = sendMSG($nomor, $pesan, $sender);
    if ($kuy['status'] == true) {
        toastr_set("success", "Pesan terkirim");
    } else {
        toastr_set("error", $kuy['response']);
    }
}

if (post("nomormedia")) {
    $nomor = post("nomormedia");
    $pesan = post("pesan");
    $sender = post("sender");
    $url = post("linkmedia");
    $a = explode('/', $url);
    $filename = $a[count($a) - 1];
    $a2 = explode('.', $filename);
    $namefile = $a2[count($a2) - 2];
    $filetype = $a2[count($a2) - 1];
    $kuy = sendMedia($nomor, $pesan, $sender, $filetype, $namefile, $url);
    if ($kuy['status'] == true) {
        toastr_set("success", "Pesan terkirim");
    } else {
        toastr_set("error", $kuy['message']);
    }
}

if (post("bsndr")) {
    $nomor = post("bnmr");
    $footer = post("bfooter");
    $sender = post("bsndr");
    $pesan = utf8_encode(post("bpsn"));
    $btn1 = post("btn1");
    $btn2 = post("btn2");
    $kuy = sendBTN($nomor, $pesan, $sender, $footer, $btn1, $btn2);
    if ($kuy['status'] == true) {
        toastr_set("success", "Pesan terkirim");
    } else {
        toastr_set("error", $kuy['response']);
        redirect('voucher_customer.php');
    }
}

require('../templates/header.php')
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
                        <form action="" method="post">
                            <label for="">Sender</label>
                            <select class="form-control" name="sender" style="width: 100%">
                                <?php
                                $u = $_SESSION['username'];
                                $q = mysqli_query($koneksi, "SELECT * FROM device WHERE pemilik='$u'");
                                while ($row = mysqli_fetch_assoc($q)) {
                                    echo '<option value="' . $row['nomor'] . '">' . $row['nomor'] . '</option>';
                                }
                                ?>
                            </select>
                            <label> Nomor Tujuan</label>
                            <input class="form-control" type="text" name="nomor" placeholder="08xxxxxxxx" required>
                            <br>
                            <label> Pesan </label>
                            <input name="pesan" type="text" required class="form-control">
                            <br>
                            <button class="btn btn-success" type="submit">Kirim</button>
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
                        <form action="" method="post">
                            <label for="">Sender</label>
                            <select class="form-control" name="sender" style="width: 100%">
                                <?php
                                $u = $_SESSION['username'];
                                $q = mysqli_query($koneksi, "SELECT * FROM device WHERE pemilik='$u'");
                                while ($row = mysqli_fetch_assoc($q)) {
                                    echo '<option value="' . $row['nomor'] . '">' . $row['nomor'] . '</option>';
                                }
                                ?>
                            </select>
                            <label> Nomor Tujuan</label>
                            <input class="form-control" type="text" name="nomormedia" placeholder="08xxxxxxxx" required>
                            <br>
                            <label> Pesan </label>
                            <input name="pesan" type="text" required class="form-control">
                            <br>
                            <label> Link Media </label> <em style="color:red;font-size:10px">*support png, jpg dan pdf</em>
                            <input class="form-control" type="text" name="linkmedia" required>
                            <br>
                            <!-- <label> Type File </label>
                    <input class="form-control" type="text" name="filetype" required>
                    <p class="small-text">jpg/png/jpeg/pdf</p>
                    <br> -->
                            <button class="btn btn-success" type="submit">Kirim</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-xl-6 mb-4">
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
                                    echo '<option value="' . $row['nomor'] . '">' . $row['nomor'] . '</option>';
                                }
                                ?>
                            </select>
                            <label> Nomor Tujuan</label>
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

        ?>
        $(document).ready(function() {
            $('#title').html('BLASTJET > Send Message')
        });
        document.getElementById("message-sid").classList.add("active");
    </script>
    </body>

    </html>