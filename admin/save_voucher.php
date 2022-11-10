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
    // $stats = utf8_encode(post("stats"));
    $u = $_SESSION['username'];
    $count = countNo("kode_voucher", "kode", $nominal, "make_by", $u);
    if ($count == 0) {
        $q = mysqli_query($koneksi, "INSERT INTO kode_voucher(`kode`, `nominal`, `make_by`)
            VALUES('$kode', '$nominal', '$u')");
        swal_set("success", "Berhasil menambahkan voucher");
        redirect("save_voucher.php");
    } else {
        swal_set("error", "Voucher yang di input sudah tersedia");
        redirect("save_voucher.php");
    }
}
// update
if (post("num")) {
    $kode = post("name");
    $nominal = post("num");
    $id = post("id");
    $stats = utf8_encode(post("stats"));
    $update = mysqli_query($koneksi, "UPDATE `kode_voucher` SET `kode` = '$kode', `nominal`='$nominal', `stats`='$stats' WHERE `kode_voucher`.`id` = '$id'");
    swal_set("success", "Berhasil update voucher" . $kode);
    redirect("save_voucher.php");
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
        <div class="alert alert-danger alert-dissmissible fade show" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"></button>
            <div class="text-center">Import Kontak langsung dengan Excel?</div>
            <div class="text-center"><a href="<?= $base_url; ?>lib/template-import-kontak.xlsx" class="btn btn-success text-center">Download Template</a></div>
        </div>
        <!-- Default -->
        <div class="row container">
            <div class="col">
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
                    <i class="ion ion-person-add"></i>Add voucher
                </button>
            </div>
        </div>
        <div class="mb-4"></div>
        <div class="row">
            <div class="col-xl-12">
                <!-- Sorting -->
                <div class="widget has-shadow">
                    <div class="widget-header bordered no-actions d-flex align-items-center">
                        <div class="col"> Saved voucher</div>
                        <a class="btn btn-info float-right" href="<?= $base_url; ?>lib/export_excel.php" style="margin:5px"><i class="ion ion-aperture"></i>Export Excel</a>
                        <button type="button" class="btn btn-success float-right" data-toggle="modal" style="margin:5px" data-target="#import"><i class="ion 
ion-archive"></i>
                            Import Excel
                        </button>
                        <a class="btn btn-danger float-right" href="save_voucher.php?act=delete_all" style="margin:5px"><i class="ion ion-trash-a"></i> Delete All</a>
                    </div>

                    <div class="widget-body">
                        <div class="table-responsive">
                            <table id="sorting-table" class="table mb-0">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Kode Voucher</th>
                                        <th>Nominal</th>
                                        <th>Status</th>
                                        <th>Date Used</th>
                                        <th>Action</th>
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
                                        echo '<td>' . $row['kode'] . '</td>';                                        
                                        echo '<td>' .'Rp. '.number_format($row['nominal'],0,"",".")  . '</td>';
                                        // echo '<td>' . $row['stats'] . '</td>';                                        
                                        if (strlen(utf8_decode($row['stats'])) >= 50) {
                                            echo '<td>' . substr(utf8_decode($row['stats']), 0, 50) . '...' . '</td>';
                                        } else {
                                            echo '<td>' . utf8_decode($row['stats']) . '</td>';
                                        }
                                        echo '<td>' . $row['date_used'] . '</td>';
                                        echo '<td class="td-actions"><a href="#" data-toggle="modal" data-target="#Update_' . $row['id'] . '"><i class="la la-edit edit"></i></a><a href="save_voucher.php?act=hapus&id=' . $row['id'] . '"><i class="la la-close delete"></i></a></td>';
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
    <div class="modal fade" id="import" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Import nominal</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="../lib/import_excel.php" method="POST" enctype="multipart/form-data">
                        <label> File Excel (.xlsx) </label>
                        <input type="file" name="file" required class="form-control">
                        <br>
                        <label> Mulai dari Baris</label>
                        <input type="text" name="a" required class="form-control" value="4">
                        <br>
                        <label> Kolom kode </label>
                        <input type="text" name="b" required class="form-control" value="1">
                        <br>
                        <label> Kolom nominal </label>
                        <input type="text" name="c" required class="form-control" value="2">
                        <br>
                        <label> Kolom stats Default </label>
                        <input type="text" name="d" required class="form-control" value="3">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
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