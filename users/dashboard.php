<?php
include_once("../helper/conn.php");
include_once("../helper/function.php");


$login = cekSession();
if ($login == 0) {
    redirect("../auth/login.php");
}
if ($_SESSION["level"] != 2) {
    redirect("../admin/dashboard.php");
}

if (get("del") == "hapus") {
    $nomor = get("nomor");
    $file = '../whatsapp-session-' . $nomor . '.json';
    $cekfile = file_exists($file);

    if ($cekfile == true) {
        toastr_set("error", "Harap Logout koneksi sebelum menghapus!");
    } else {
        $q = mysqli_query($koneksi, "DELETE FROM device WHERE nomor='$nomor'");
        toastr_set("success", "Sukses hapus user");
    }
}

if (post("nomorwhatsapp")) {
    $nomor = post("nomorwhatsapp");
    $cek = mysqli_query($koneksi, "SELECT * FROM device WHERE nomor = '$nomor' ");
    if (substr($nomor, 0, 2) != '62') {
        toastr_set("error", "Nomor harus menggunakan kode negara ");
    } else if (mysqli_num_rows($cek) > 0) {
        toastr_set("error", "Nomor sudah ada di database");
    } else {
        $username = $_SESSION['username'];
        $q = mysqli_query($koneksi, "INSERT INTO device VALUES (null,'$username','$nomor','')");
        toastr_set("success", "Nomor berhasil ditambahkan");
    }
}

require_once('../templates/header.php');
?>


<div class="content-inner">
    <div class="container-fluid">
        <!-- Begin Page Header-->
        <div class="row">
            <div class="page-header">
                <div class="d-flex align-items-center">
                    <h2 class="page-header-title">Dashboard</h2>
                </div>
            </div>
        </div>
           <?php 
     include_once('../settings/subscribe.php');
        ?>
        <!-- End Page Header -->
        <div class="row">
            <div class="col-xl-6">
                <button class="btn btn-primary" style="margin-bottom: -70px;" data-toggle="modal" data-target="#tambahNomorModal">Add Whatsapp</button>
                <!-- Basic -->
                <?php
                $username = $_SESSION['username'];
                $q = mysqli_query($koneksi, "SELECT * FROM device WHERE pemilik = '$username'");
                while ($row = mysqli_fetch_assoc($q)) { ?>
                    <div class="widget has-shadow">
                        <div class="widget-header bordered no-actions d-flex align-items-center">
                            <h4>Your Whatsapp Number</h4>
                        </div>
                        <div class="widget-body">
                            <div class="inner-addon left-addon">
                                <i class="la ion-social-whatsapp"></i>
                                <input type="text" readonly name="nomorwhatsapp" value="<?= $row['nomor']; ?>" required class="form-control">
                                <br>
                            </div>
                            <a class="btn btn-danger" href="dashboard.php?del=hapus&nomor=<?= $row['nomor']; ?>"> Hapus </a>
                        </div>
                    </div>
                    <div class="widget has-shadow">
                        <div class="widget-header bordered no-actions d-flex align-items-center">
                            <h4>Scan QR Code</h4>
                        </div>
                        <div class="widget-body text-center">
                            <div class="shadow areascanqr">
                                <div class="card">
                                    <div class="container mt-5 mb-5">
                                        <div class="row">
                                            <div class="col">
                                                <img src="../img/qrload.png" class="card-img-top" alt="cardimg" onclick="scanqr('<?= $row['nomor']; ?>')" style="height:250px; width:250px;">
                                            </div>
                                        </div>
                                        <div class="ml-2 mt-5">
                                            <img src="../assets/img/blastjet-sm.png" style="width:80px" class="mb-2">BLASTJET WA GATEWAY<br>
                                            <h4 class="text-left">Untuk menggunakan BlastJET WA Gateway Anda:</h4><br>
                                            <span style="font-size:13px;text-align:left ;">
                                                1. Buka WhatsApp di ponsel Anda<br>
                                                2. Ketuk pada bagian <i class="la la-ellipsis-v text-dark"></i> atau Pengaturan <i class="la la-cog text-dark"></i> dan pilih <span style="color:black">Perangkat Tertaut</span><br>
                                                3. Arahkan ponsel Anda ke Code QR ini untuk menangkap Kode tersebut dan menyambungkannya.</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- qr -->
                        </div>
                    </div><?php } ?>
            </div>
            <!-- Modal -->

            <!-- End Basic -->
            <div class="col-xl-6 mb-4">
                <!-- qr / apikey -->
                <div class="widget has-shadow">
                    <div class="widget-header">

                        <div class="scanqr-api">

                        </div>
                        <div class="card-header">
                            <div class="icon-api">
                                <i class="la ion-social-whatsapp"></i>
                            </div>
                        </div>
                    </div>
                    <div class="widget-body">
                        <h3 class="section-title mb-5 text-center">
                            Hi! Welcome, <?= $_SESSION['username'] ?>
                        </h3>
                        <div class="progress progress-sm mb-3">
                            <div class="progress-bar bg-info" role="progressbar" style="width: 50%" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <label>Your Api Key</label>
                        <input type="text" readonly value="<?= getSingleValDB("account", "username", "$username", "api_key") ?>" class="form-control mb-5" onclick="copyText()" style="cursor: pointer;" id="apiKey">
                        <div class="progress progress-sm mb-3">
                            <div class="progress-bar bg-danger" role="progressbar" style="width: 100%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <!-- End Row -->
        <div class="modal fade" id="tambahNomorModal" tabindex="-1" role="dialog" aria-labelledby="tambahNomorModal" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Tambah Nomor </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="" method="POST">
                            <label> Nomor Whatsapp </label>
                            <input type="number" name="nomorwhatsapp" value="62" required class="form-control">
                            <br>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" name="tambahnomor" class="btn btn-primary">Simpan</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php
    include_once('../templates/footer.php')
    ?>
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
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js" integrity="sha512-VEd+nq25CkR676O+pLBnDW09R7VQX9Mdiij052gVCp5yVH3jGtH70Ho/UUv4mJDsEdTvqRCFZg0NKGiojGnUCw==" crossorigin="anonymous">
    </script>
    <script>
        function gagal() {
            swal.fire('Gagal Memuat', 'Kesalahan!')
        }

        function copyText() {
            /* Get the text field */
            var copyText = document.getElementById("apiKey");

            /* Select the text field */
            copyText.select();

            /* Copy the text inside the text field */
            document.execCommand("copy");

            /* Alert the copied text */
            toastr["success"]("Api Key Berhasil di Salin: " + copyText.value);
        };
        <?php

        toastr_show();

        ?>
        $(document).ready(function() {
            $('#title').html('BLASTJET > Dashboard')
        });
        document.getElementById("dashboard-sid").classList.add("active");
    </script>
    <script>
        // =========================================================
        // GANTI SESUAI LETAK INSTALASI
        // =========================================================

        // Socket Hosting Web - hapus '//' dibawah ini untuk menggunakannya di hosting
        var socket = io();

        // ini socket untuk di localhost - Hapus '//' dibawah ini untuk menggunakannya di Localhost
        //var socket = io('http://localhost:3000', {
        //     transports: ['websocket',
        //         'polling',
        //           'flashsocket'
        //       ]
        //    });
        // =========================================================
        // PERHATIKAN PERHATIKAN PERHATIKAN PERHATIKAN
        // =========================================================

        function scanqr(nomor) {
            $('.areascanqr').html(`
<div class="card-body">
    <div id="cardimg-${nomor}" class="text-center ">

    </div>
</div>
`)
            $(`#cardimg-${nomor}`).html(`
            <div class="container mt-5 mb-5">
                            <div class="row">
                                <div class="col">
                                <img src="../loading.gif" class="card-img-top center" alt="cardimg" id="qrcode"
    style="height:250px; width:250px;">
                                </div>
                            </div>
                            <div class="ml-2 mt-5">
                                    <img src="../assets/img/blastjet-sm.png" style="width:80px" class="mb-2">BLASTJET WA GATEWAY<br>
                                    <h4 class="text-left">Untuk menggunakan BlastJET WA Gateway Anda:</h4><br>
                                    <span style="font-size:13px;text-align:left ;">
                                                1. Buka WhatsApp di ponsel Anda<br>
                                                2. Ketuk pada bagian <i class="la la-ellipsis-v text-dark"></i> atau Pengaturan <i class="la la-cog text-dark"></i> dan pilih <span style="color:black">Perangkat Tertaut</span><br>
                                                3. Arahkan ponsel Anda ke Code QR ini untuk menangkap Kode tersebut dan menyambungkannya.</span>
                                </div>
                    </div>
                    `);

            $('#scanModal').modal('show');
            socket.emit('create-session', {
                id: nomor
            });
        }
        // sethook
        function sethook(id) {
            $('.idnomor').val(id);
            var hook = $('.urlwebhook').val();
            $('#setHookModal').modal('show');
        }

        <?php
        $username = $_SESSION['username'];
        $q = mysqli_query($koneksi, "SELECT * FROM device WHERE pemilik = '$username'");
        while ($row = mysqli_fetch_assoc($q)) { ?>

            function logoutqr(nomor) {
                socket.emit('logout', {
                    id: nomor
                });
            }


            socket.on('message', function(msg) {
                $('.log').html(`<li>` + msg.text + `</li>`);
            })
            socket.on('qr', function(src) {
                console.log(src)
                $(`#cardimg-${src.id}`).html(`
                <div class="container mt-5 mb-5">
            <div class="row">
            <div class="col">
            <img src="` + src.src + `" class="card-img-top"  alt="cardimg" id="qrcode" style="height:250px; width:250px;"></div>
    </div>
    <div class="ml-2 mt-5">
                                    <img src="../assets/img/blastjet-sm.png" style="width:80px" class="mb-2">BLASTJET WA GATEWAY<br>
                                    <h4 class="text-left">Untuk menggunakan BlastJET WA Gateway Anda:</h4><br>
                                    <span style="font-size:13px;text-align:left ;">
                                                1. Buka WhatsApp di ponsel Anda<br>
                                                2. Ketuk pada bagian <i class="la la-ellipsis-v text-dark"></i> atau Pengaturan <i class="la la-cog text-dark"></i> dan pilih <span style="color:black">Perangkat Tertaut</span><br>
                                                3. Arahkan ponsel Anda ke Code QR ini untuk menangkap Kode tersebut dan menyambungkannya.</span>
                                </div></div>
                                `);
                var count = 0;
                var interval = setInterval(function() {
                    count++
                    $(`.info-${src.id}`).html(``);
                    if (count == 10) {
                        $(`#cardimg-${src.id}`).html(`
                                    <div class="container mt-5 mb-5">
                                        <div class="row">
                                            <div class="col">
                                                <img src="../img/qrload.png" onclick="scanqr('<?= $row['nomor']; ?>')"  class="card-img-top" alt="cardimg" style="height:250px; width:250px;"> 
                                                </div> 
                                                </div>
                                                <div class ="ml-2 mt-5">
                                    <img src = "../assets/img/blastjet-sm.png" style = "width:80px" class = "mb-2" > BLASTJET WA GATEWAY <br>
                                    <h4 class = "text-left"> Untuk menggunakan BlastJET WA Gateway Anda: </h4><br> 
                                    <span style="font-size:13px;text-align:left ;">
                                                1. Buka WhatsApp di ponsel Anda<br>
                                                2. Ketuk pada bagian <i class="la la-ellipsis-v text-dark"></i> atau Pengaturan <i class="la la-cog text-dark"></i> dan pilih <span style="color:black">Perangkat Tertaut</span><br>
                                                3. Arahkan ponsel Anda ke Code QR ini untuk menangkap Kode tersebut dan menyambungkannya.</span>
                                                 </div> 
                                                 </div>`);

                        clearInterval(interval)
                    }
                }, 1000);
            });
        <?php } ?>
        // socket.on('authenticated', function(src) {
        //     $(`#info-${src.id}`).attr('class', 'changed');
        //     $('.changed').html('')
        //     $(`#cardimg-${src.id}`).html(`<h2 class="text-center text-success mt-4">` + src.text + `<h2>`);

        // });
        // ketika terhubung
        socket.on('authenticated', function(src) {
            const nomors = src.data.jid;
            //  const nomor = src.id
            const nomor = nomors.replace(/\D/g, '');
            $(`#cardimg-${src.id}`).html(`<div style="color:green;">Whatsapp Sudah Siap Digunakan</div><br><br>
            <table class="text-sm-left">
            <tbody>
    <tr>
      <td>Name </td>
      <td>: ${src.data.name}</td>
    </tr>
     <tr>
      <td>Type Phone </td>
      <td>: ${src.data.phone.device_model}</td>
    </tr>
    <tr>
      <td>Version </td>
      <td>: ${src.data.phone.wa_version}</td>
    </tr>
  </tbody>
            </table>
            <button class="btn btn-danger scanbutton" onclick="logoutqr(${nomor})">Logout</button>
            `);
            //  $('#cardimg').html(`<h2 class="text-center text-success mt-4">Whatsapp Connected.<br>` + src + `<h2>`);

        });
        socket.on('isdelete', function(src) {
            //  $(`.info-${src.id}`).html(`<p><span class="text-danger">disconnect</span></p>`);
            $(`#cardimg-${src.id}`).html(src.text);
        });
        socket.on('close', function(src) {
            console.log(src);
            $(`#cardimg-${src.id}`).html(`<h2 class="text-center text-danger mt-4">` + src.text + `<h2>`);
        });
    </script>

    </body>

    </html>