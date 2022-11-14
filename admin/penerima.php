<?php
include_once("../helper/conn.php");
include_once("../helper/function.php");
$login = cekSession();
if ($login == 0) {
    redirect("../auth/login.php");
}
if ($_SESSION["level"] != 1) {
    redirect("../users/broadcast.php");
}
if (post("pesan")) {
    $username = $_SESSION['username'];
    $pesan = post("pesan");
    $sender = post("device");
    // var_dump($sender); die;
    $jadwal = date("Y-m-d H:i:s", strtotime(post("tgl") . " " . post("jam")));
    if (!empty($_FILES['media']) && $_FILES['media']['error'] == UPLOAD_ERR_OK) {
        // Be sure we're dealing with an upload
        if (is_uploaded_file($_FILES['media']['tmp_name']) === false) {
            throw new \Exception('Error on upload: Invalid file definition');
        }

        // Rename the uploaded file
        $uploadName = $_FILES['media']['name'];
        $ext = strtolower(substr($uploadName, strripos($uploadName, '.') + 1));
        $size = $_FILES['media']['size'];
        if ($size > 1000000) {
            toastr_set("error", "Maximal 1 mb");
            redirect("broadcast.php");
            exit;
        }
        $allow = ['pdf', 'png', 'jpg', 'jpeg'];
        if (in_array($ext, $allow)) {
            if ($ext == "pdf") {
                $filename = round(microtime(true)) . mt_rand() . '-' . $uploadName;
            }
            if ($ext == "jpg") {
                $filename = round(microtime(true)) . mt_rand() . '-' . $uploadName;
            }

            if ($ext == "png") {
                $filename = round(microtime(true)) . mt_rand() . '-' . $uploadName;
            }
            if ($ext == "jpeg") {
                $filename = round(microtime(true)) . mt_rand() . '-' . $uploadName;
            }
        } else {
            toastr_set("error", "Hanya mendukung format Jpg png dan Pdf");
            redirect("broadcast.php");
            exit;
        }

        mkdir('../uploads/base/' . $username . '/broadcast-media');
        move_uploaded_file($_FILES['media']['tmp_name'], '../uploads/base/' . $username . '/broadcast-media' . '/' . $filename);
        // Insert it into our tracking along with the original name
        $media = $base_url . "uploads/base/" . $username . "/broadcast-media/" . $filename;
    } else {
        $media = null;
    }


    if (isset($_POST['target'])) {
        foreach ($_POST['target'] as $data) {
            $n = $data;
            $ceknomor = mysqli_query($koneksi, "SELECT * FROM contacts WHERE number = '$n' AND make_by = '$username'");
            $data2 = $ceknomor->fetch_assoc();
            $pesannya = strtr($pesan, array(
                '{nama}' => $data2['name'],
            ));
            $pesannya2 = utf8_encode($pesannya);
            if ($media == null) {
                $q = mysqli_query($koneksi, "INSERT INTO pesan(`sender`,`nomor`, `pesan`, `jadwal`, `make_by`)
              VALUES('$sender','$n', '$pesannya2', '$jadwal','$username')");
            } else {
                $q = mysqli_query($koneksi, "INSERT INTO pesan(`sender`,`nomor`, `pesan`, `media`, `jadwal`, `make_by`)
              VALUES('$sender','$n', '$pesannya2', '$media', '$jadwal', '$username')");
            }
        }
        // var_dump($n); die;

    } else {
        $username = $_SESSION['username'];
        $ceknomor = mysqli_query($koneksi, "SELECT * FROM contacts WHERE make_by = '$username'");
        while ($data = $ceknomor->fetch_assoc()) {
            $pesannya = strtr($pesan, array(
                '{nama}' => $data['name'],
            ));
            $pesannya2 = utf8_encode($pesannya);
            $n = $data['number'];
            if ($media == null) {
                $q = mysqli_query($koneksi, "INSERT INTO pesan(`sender`,`nomor`, `pesan`, `jadwal`, `make_by`)
            VALUES('$sender','$n', '$pesannya2', '$jadwal','$username')");
            } else {
                $q = mysqli_query($koneksi, "INSERT INTO pesan(`sender`,`nomor`, `pesan`, `media`, `jadwal`, `make_by`)
            VALUES('$sender','$n', '$pesannya2', '$media', '$jadwal', '$username')");
            }
            // var_dump($q);
        }
    }
    toastr_set("success", "Berhasil membuat pesan broadcast");
    redirect("broadcast.php");
}
if (post("pesan2")) {
    $sender = post("device");
    $username = $_SESSION['username'];
    //$pesan = post("pesan");
    $jadwal = date("Y-m-d H:i:s", strtotime(post("tgl") . " " . post("jam")));
    if (!empty($_FILES['media']) && $_FILES['media']['error'] == UPLOAD_ERR_OK) {
        // Be sure we're dealing with an upload
        if (is_uploaded_file($_FILES['media']['tmp_name']) === false) {
            throw new \Exception('Error on upload: Invalid file definition');
        }

        // Rename the uploaded file
        $uploadName = $_FILES['media']['name'];
        $ext = strtolower(substr($uploadName, strripos($uploadName, '.') + 1));
        $size = $_FILES['media']['size'];
        if ($size > 1000000) {
            toastr_set("error", "maximal 1 mb");
            redirect("broadcast.php");
            exit;
        }
        $allow = ['pdf', 'png', 'jpg', 'jpeg'];
        if (in_array($ext, $allow)) {
            if ($ext == "pdf") {
                $filename = round(microtime(true)) . mt_rand() . '-' . $uploadName;
            }
            if ($ext == "jpg") {
                $filename = round(microtime(true)) . mt_rand() . '-' . $uploadName;
            }

            if ($ext == "png") {
                $filename = round(microtime(true)) . mt_rand() . '-' . $uploadName;
            }
            if ($ext == "jpeg") {
                $filename = round(microtime(true)) . mt_rand() . '-' . $uploadName;
            }
        } else {
            toastr_set("error", "Hanya mendukung format Jpg dan Png");
            redirect("broadcast.php");
            exit;
        }
        mkdir('../uploads/base/' . $username . '/broadcast-media');
        move_uploaded_file($_FILES['media']['tmp_name'], '../uploads/base/' . $username . '/broadcast-media' . '/' . $filename);
        // Insert it into our tracking along with the original name
        $media = $base_url . "uploads/base/" . $username . "/broadcast-media/" . $filename;
    } else {
        $media = null;
    }


    if (isset($_POST['target'])) {
        foreach ($_POST['target'] as $data) {
            $n = $data;
            $ceknomor = mysqli_query($koneksi, "SELECT * FROM nomor WHERE nomor = '$n' AND make_by = '$username'");
            $data2 = $ceknomor->fetch_assoc();
            $pesannya = strtr($data2['pesan'], array(
                '{nama}' => $data2['nama'],
            ));

            $pesannya2 = utf8_encode($pesannya);
            if ($media == null) {
                $q = mysqli_query($koneksi, "INSERT INTO pesan(`sender`,`nomor`, `pesan`, `jadwal`, `make_by`)
              VALUES('$sender','$n', '$pesannya2', '$jadwal','$username')");
            } else {
                $q = mysqli_query($koneksi, "INSERT INTO pesan(`sender`,`nomor`, `pesan`, `media`, `jadwal`, `make_by`)
              VALUES('$sender','$n', '$pesannya2', '$media', '$jadwal', '$username')");
            }
        }
    } else {
        $username = $_SESSION['username'];
        $ceknomor = mysqli_query($koneksi, "SELECT * FROM nomor WHERE make_by = '$username'");
        while ($data = $ceknomor->fetch_assoc()) {
            $pesannya = strtr($data['pesan'], array(
                '{nama}' => $data['nama'],
            ));
            $pesannya2 = utf8_encode($pesannya);
            $n = $data['nomor'];
            if ($media == null) {
                $q = mysqli_query($koneksi, "INSERT INTO pesan(`sender`,`nomor`, `pesan`, `jadwal`, `make_by`)
            VALUES('$sender','$n', '$pesannya2', '$jadwal','$username')");
            } else {
                $q = mysqli_query($koneksi, "INSERT INTO pesan(`sender`,`nomor`, `pesan`, `media`, `jadwal`, `make_by`)
            VALUES('$sender','$n', '$pesannya2', '$media', '$jadwal', '$username')");
            }
            // var_dump($q);
        }
    }

    toastr_set("success", "Berhasil membuat pesan terjadwal");
    redirect("broadcast.php");
}

if (get("a") == "resender") {
    $id_blast = get("id");
    $q = mysqli_query($koneksi, "UPDATE `pesan` SET `status`='MENUNGGU JADWAL',`jadwal`=now()+ INTERVAL '1' MINUTE WHERE `status`='GAGAL' AND `id`='$id_blast'");
    toastr_set("success", "Berhasil Set ulang pesan");
    redirect("broadcast.php");
}

if (get("del") == "all") {
    $username = $_SESSION['username'];
    $cekgambar = mysqli_query($koneksi, "SELECT * FROM pesan WHERE `status`='TERKIRIM'");
    while ($gambar = $cekgambar->fetch_assoc()) {
        $gm = $gambar['media'];
        $e = strlen($base_url . "uploads/base/" . $username . "/broadcast-media/");
        $lis = substr($gm, $e);
        unlink("../uploads/base/" . $username . "/broadcast-media/" . $lis);
        $q = mysqli_query($koneksi, "DELETE FROM pesan WHERE `status`='TERKIRIM'");
        swal_set("success", "Berhasil menghapus pesan broadcast");
        redirect("broadcast.php");
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
                    <h2 class="page-header-title">Penerima Voucher</h2>
                    <div>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="dashboard.html"><i class="ti ti-home"></i></a></li>
                            <li class="breadcrumb-item active">Penerima Voucher</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Page Header -->
        <!-- <div class="alert alert-danger alert-dissmissible fade show" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"></button>
            Default Messages<br><i class="la la-share-alt"></i>Data Costumer -> Nomor Tersimpan.
        </div> -->
        <!-- Default -->
        <!-- <div class="row container">
            <div class="col">
                <button class="btn btn-primary mb-2" data-toggle="modal" data-target="#exampleModal"><i class="ion-paper-airplane"></i> Send Messages</button>
                <button class="btn btn-primary mb-2" data-toggle="modal" data-target="#kirimpesan2"><i class="ion-paperclip"></i>Default Messages</button>
            </div>
        </div> -->
        <div class="mb-4"></div>
        <div class="row">
            <div class="col-xl-12">
                <!-- Sorting -->
                <div class="widget has-shadow">
                    <div class="widget-header bordered no-actions d-flex align-items-center">
                        <div class="col"> Data penerima Voucher</div>
                        <!-- <a class="btn btn-danger text-end" href="broadcast.php?del=all"><i class="ion-trash-b"></i>(Terkirim)</a> -->
                    </div>
                    <div class="widget-body">
                        <div class="table-responsive">
                            <table id="sorting-table" class="table mb-0">
                                <thead>
                                    <tr>
                                        <th>NIK</th>
                                        <th>NAMA</th>
                                        <th>Alamat</th>
                                        <th>No. Tlp</th>
                                        <th>Email</th>
                                        <th>Cabang</th>
                                        <!-- <th>Voucher</th> -->
                                        <th>Tgl Terima</th>
                                        <!-- <th>Action</th> -->
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php

                                    $username = $_SESSION['username'];
                                    $q = mysqli_query($koneksi, "SELECT * FROM user WHERE make_by='$username' ORDER BY tgl_pakai DESC");
                                    while ($row = mysqli_fetch_assoc($q)) {
                                        echo '<tr>';
                                        echo '<td>' . $row['nik'] . '</td>';
                                        echo '<td>' . $row['nama'] . '</td>';
                                        echo '<td>' . $row['alamat'] . '</td>';
                                        echo '<td>' . $row['no_tlp'] . '</td>';
                                        echo '<td>' . $row['email'] . '</td>';
                                        echo '<td>' . $row['cabang'] . '</td>';
                                        // echo '<td>' . $row['kode'] . '</td>';
                                        echo '<td>' . $row['tgl_pakai'] . '</td>';

                                    }

                                    ?>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <!-- End Sorting -->
            </div>
        </div>
        <div class="row flex-row">
            <!-- Begin Facebook -->
            <div class="col-xl-4 col-md-6 col-sm-6">
                <div class="widget widget-12 has-shadow">
                    <div class="widget-body">
                        <div class="media">
                            <div class="align-self-center ml-5 mr-5">
                                <i class="ion-checkmark-circled text-success"></i>
                            </div>
                            <div class="media-body align-self-center">
                                <div class="title text-facebook">Penerima</div>
                                <div class="number"> <?= countDB("user", "make_by", $username) ?> dari <?= countDB("kode_voucher",  "make_by", "admin") ?> Total Voucher</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-md-6 col-sm-6">
                <div class="widget widget-12 has-shadow">
                    <div class="widget-body">
                        <div class="media">
                            <div class="align-self-center ml-5 mr-5">
                                <i class="ion-close-circled text-danger"></i>
                            </div>
                            <div class="media-body align-self-center">
                                <div class="title text-facebook">Belum digunakan</div>
                                <div class="number"><?= countUS("kode_voucher", "stats", "not used", "make_by", "admin") ?> Voucher</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-md-6 col-sm-6">
                <div class="widget widget-12 has-shadow">
                    <div class="widget-body">
                        <div class="media">
                            <div class="align-self-center ml-5 mr-5">
                                <i class="ion-pie-graph text-facebook"></i>
                            </div>
                            <div class="media-body align-self-center">
                                <div class="title text-facebook">Persentase</div>
                                <div class="progress progress-lg mb-3">
                                    <div class="progress-bar progress-bar-striped progress-bar-animated <?php
                                                                                                        if (countPresentase() < 40) {
                                                                                                            echo "bg-danger";
                                                                                                        } else if (countPresentase() <= 70) {
                                                                                                            echo "bg-warning";
                                                                                                        } else {
                                                                                                            echo "bg-success";
                                                                                                        } ?>" role="progressbar" style="width: <?= countPresentase() ?>%" aria-valuenow="<?= countPresentase() ?>" aria-valuemin="0" aria-valuemax="100"><?= countPresentase() ?>%</div>
                                </div>
                                <!--<div class="number"></div>-->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Row -->
        <div style="height: 200px;">
        </div>
        <!-- Modal -->
        <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Sending Messages</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="" method="POST" enctype="multipart/form-data">
                            <label>sender</label>
                            <br>
                            <select class="form-control" name="device">
                                <?php

                                $u = $_SESSION['username'];
                                $q = mysqli_query($koneksi, "SELECT * FROM device WHERE pemilik='$u'");

                                while ($row = mysqli_fetch_assoc($q)) {
                                    echo '<option value="' . $row['nomor'] . '">' . $row['nama'] . ' (' . $row['nomor'] . ')</option>';
                                }
                                ?>
                            </select>
                            <br>
                            <label> Pesan </label>
                            <textarea name="pesan" required class="form-control"></textarea>
                            <br>
                            <label> Media </label> <em style="color:red;font-size:12px">*Max 1 mb </em>
                            <input type="file" name="media" class="form-control">
                            <br>
                            <label> Tanggal Pengiriman</label>
                            <input type="date" name="tgl" class="form-control">
                            <br>
                            <label> Waktu Pengiriman</label>
                            <input type="time" name="jam" class="form-control">
                            <br>
                            <label>Target</label>
                            <br>
                            <select class="form-control multiselect" name="target[]" multiple>
                                <?php

                                $u = $_SESSION['username'];
                                $q = mysqli_query($koneksi, "SELECT * FROM contacts WHERE make_by='$u'");

                                while ($row = mysqli_fetch_assoc($q)) {
                                    echo '<option value="' . $row['number'] . '">' . $row['name'] . ' (' . $row['number'] . ')</option>';
                                }
                                ?>
                            </select>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" name="pesan1" class="btn btn-primary">Save</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="kirimpesan2" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Sending Default Messages</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="" method="POST" enctype="multipart/form-data">
                        <label>sender</label>
                        <br>
                        <select class="form-control" name="device" style="width: 100%">
                            <?php

                            $u = $_SESSION['username'];
                            $q = mysqli_query($koneksi, "SELECT * FROM device WHERE pemilik='$u'");

                            while ($row = mysqli_fetch_assoc($q)) {
                                echo '<option value="' . $row['nomor'] . '">' . $row['nama'] . ' (' . $row['nomor'] . ')</option>';
                            }
                            ?>
                        </select>
                        <br>
                        <input type="hidden" name="pesan2" value="yo">
                        <label> Media </label> <em style="color:red;font-size:12px">*Maximal 1 mb </em>
                        <input type="file" name="media" class="form-control">

                        <br>
                        <label> Tanggal Pengiriman<em style="color:red;font-size:12px">*</em> </label>
                        <input type="date" name="tgl" class="form-control">
                        <br>
                        <label> Waktu Pengiriman<em style="color:red;font-size:12px">*</em> </label>
                        <input type="time" name="jam" class="form-control">
                        <br>
                        <label>Target</label>
                        <br>
                        <select class="form-control multiselectWo" name="target[]" multiple>
                            <?php

                            $u = $_SESSION['username'];
                            $q = mysqli_query($koneksi, "SELECT * FROM nomor WHERE make_by='$u'");

                            while ($row = mysqli_fetch_assoc($q)) {
                                echo '<option value="' . $row['nomor'] . '">' . $row['nama'] . ' (' . $row['nomor'] . ')</option>';
                            }
                            ?>
                        </select>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" name="kirimpesan2" class="btn btn-primary">Save</button>
                    </form>
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
    <script src="<?= $base_url; ?>assets/vendors/js/datatables/datatables.min.js"></script>
 <script src="<?= $base_url; ?>assets/vendors/js/datatables/tables.js"></script>
    <!-- Core plugin JavaScript-->
    <script src="<?= $base_url; ?>vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="<?= $base_url; ?>assets/vendors/js/app/app.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js" integrity="sha512-VEd+nq25CkR676O+pLBnDW09R7VQX9Mdiij052gVCp5yVH3jGtH70Ho/UUv4mJDsEdTvqRCFZg0NKGiojGnUCw==" crossorigin="anonymous"></script>
     <Script>
     new SlimSelect({
         select: '.multiselect',
         placeholder: 'Pilih Target Pesan...'
     })
     new SlimSelect({
         select: '.multiselectWo',
         placeholder: 'Pilih Target Pesan...'
     })
 </script>
    <script>
    
        <?php

        toastr_show();
        swal_show();

        ?>
        $(document).ready(function() {
            $('#title').html('PENTA PRIMA> Penerima Voucher')
        });
        document.getElementById("message-sid").classList.add("active");
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js" integrity="sha512-qTXRIMyZIFb8iQcfjXWCO8+M5Tbc38Qi5WzdPOYZHIlZpzBHG3L3by84BBBOiRGiEb7KKtAOAs5qYdUiZiQNNQ==" crossorigin="anonymous"></script>
    <script>
        setInterval(sync, 4000);

        function sync() {
            let sync = localStorage.getItem('sync');
            if (sync == null) {
                sync = moment().format("YYYY-MM-DD HH:mm:ss");
                localStorage.setItem('sync', sync);
            }

            $.get("longpooling.php?lastsync=" + sync, function(data) {
                r = JSON.parse(data);

                jQuery.each(r, function(i, val) {
                    let id = val.id;
                    let id_blast = val.id_blast;
                    if (val.status == "GAGAL") {
                        $(".status-container-" + id).empty();
                        $(".status-container-" + id).html('Gagal Terkirim');
                        $(".status-container-" + id).addClass('badge-danger').removeClass('badge-warning');

                        $(".button-container-" + id).html('<a style="margin:5px" class="btn btn-success" href="broadcast.php?act=ku&id=' + id_blast + '">Kirim Ulang</a><a style="margin:5px" class="btn btn-danger" href="hapus_pesan.php?id=' + id + '">Hapus</a>');
                    }

                    if (val.status == "TERKIRIM") {
                        $(".status-container-" + id).empty();
                        $(".status-container-" + id).html('Terkirim');
                        $(".status-container-" + id).addClass('badge-success').removeClass('badge-warning');

                        $(".button-container-" + id).html('<a style="margin:5px" class="btn btn-danger" href="hapus_pesan.php?id=' + id + '">Hapus</a>');
                    }
                    console.log(id);
                });

                localStorage.setItem('sync', moment().format("YYYY-MM-DD HH:mm:ss"));

            });
        }
    </script>
    </body>

    </html>