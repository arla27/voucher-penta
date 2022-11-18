<?php
include_once("../helper/conn.php");
include_once("../helper/function.php");


$login = cekSession();
if ($login == 0) {
    redirect("login.php");
}
if ($_SESSION["level"] != 1) {
    redirect("../users/save_voucher.php");
}
if (post("kode")) {
    $kode = post("kode");
    $nominal = post("nominal");
    $stats = post("stats");
    $u = $_SESSION['username'];
    $count = countNo("kode_voucher", "kode", $nominal, "make_by", $u);
    if ($count == 0) {
        $q = mysqli_query($koneksi, "INSERT INTO kode_voucher(`kode`, `nominal`,`stats`, `make_by`)
            VALUES('$kode', '$nominal','not used', '$u')");
        swal_set("success", "Berhasil menambahkan voucher");
        redirect("save_voucher.php");
    } else {
        swal_set("error", "Voucher yang di input sudah tersedia");
        redirect("save_voucher.php");
    }
}


if (get("act") == "hapus") {
    $id = get("id");

    $q = mysqli_query($koneksi, "DELETE FROM kode_voucher WHERE id='$id'");
    swal_set("success", "Berhasil menghapus nominal");
    redirect("save_voucher.php");
}

if (get("act") == "delete_all") {
    $q = mysqli_query($koneksi, "DELETE FROM kode_voucher");
    swal_set("success", "Berhasil hapus semua voucher");
    redirect("save_voucher.php");
}
require_once('../templates/header.php');
?>


<div class="content-inner">
    <div class="container-fluid">
        <!-- Begin Page Header-->
        <div class="row">
            <div class="page-header">
                <div class="d-flex align-items-center">
                    <h2 class="page-header-title">Saved voucher</h2>
                    <div>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="dashboard.html"><i class="ti ti-home"></i></a></li>
                            <li class="breadcrumb-item active">Saved voucher</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Page Header -->

        <!-- Default -->
        <div class="row container">
            <div class="col">
            <!-- <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
                    <i class="ion ion-person-add"></i>Add voucher
                </button> -->
            </div>
            <!-- <a class="btn btn-info float-right" href="<?= $base_url; ?>lib/kontakgroup.php" style="margin:5px">Export Contact Group</a> -->
            <a class="btn btn-info float-right" href="<?= $base_url; ?>lib/voucher.php" style="margin:5px">Export Voucher Excel</a>
        </div>


        <div class="mb-4"></div>
        <div class="row">
            <div class="col-xl-12">
                <!-- Sorting -->
                <div class="widget has-shadow">

                    <div class="widget-body">
                        <div class="table-responsive">
                            <table id="sorting-table" class="table mb-0">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <!-- <th>Kode Voucher</th> -->
                                        <th>Nominal</th>
                                        <th>Status</th>
                                        <th>Date Used</th>
                                        <!-- <th>Action</th> -->
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $id = 0;
                                    $u = $_SESSION['username'];
                                    $q = mysqli_query($koneksi, "SELECT * FROM kode_voucher WHERE make_by='$u'");

                                    while ($row = mysqli_fetch_assoc($q)) {
                                        $id++;
                                        echo '<tr>';
                                        echo '<td>' . $id . '</td>';
                                        // echo '<td>' . $row['kode'] . '</td>';                                        
                                        echo '<td>' .'Rp. '.number_format($row['nominal'],0,"",".")  . '</td>';
                                        // echo '<td>' . $row['stats'] . '</td>';                                        
                                        if (strlen(utf8_decode($row['stats'])) >= 50) {
                                            echo '<td>' . substr(utf8_decode($row['stats']), 0, 50) . '...' . '</td>';
                                        } else {
                                            echo '<td>' . utf8_decode($row['stats']) . '</td>';
                                        }
                                        echo '<td>' . $row['date_used'] . '</td>';
                                        // echo '<td class="td-actions"><a href="#" data-toggle="modal" data-target="#Update_' . $row['id'] . '"><i class="la la-edit edit"></i></a><a href="save_voucher.php?act=hapus&id=' . $row['id'] . '"><i class="la la-close delete"></i></a></td>';
                                        echo '</tr>';
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
    </div>

    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Contact</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="" method="POST">
                        <label>Kode Voucher </label>
                        <input type="text" name="kode" required class="form-control">
                        <br>
                        <label>Nominal</label>
                        <input type="text" name="nominal" required class="form-control">
                        <br>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <?php
    $q = mysqli_query($koneksi, "SELECT * FROM kode_voucher WHERE make_by='$username'");
    while ($row = mysqli_fetch_assoc($q)) { ?>
        <div class="modal fade" id="Update_<?= $row['id'] ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Update Contact ~ <?= $row['kode'] ?></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="" method="POST">
                            <label>Kode Voucher </label>
                            <input type="text" name="name" required value="<?= $row['kode'] ?>" class="form-control">
                            <br>
                            <label>Nominal</label>
                            <input type="text" name="num" value="<?= $row['nominal'] ?>" required class="form-control">
                            <input type="text" name="id" value="<?= $row['id'] ?>" hidden class="form-control">
                            <br>
                            <label>Status </label>
                            <textarea type="text" name="stats" required class="form-control" placeholder="stats Default"><?= $row['stats'] ?></textarea>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Update</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    <?php  } ?>

    <div style="height:70px;">

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
    <script>
        <?php

        toastr_show();
        swal_show();

        ?>
        $(document).ready(function() {
            $('#title').html('PENTA PRIMA > Saved voucher')
        });
        document.getElementById("costumer-sid").classList.add("active");
    </script>
    </body>

    </html>