<?php
include_once("../helper/conn.php");
include_once("../helper/function.php");


$login = cekSession();
if ($login == 0) {
    redirect("login.php");
}
if ($_SESSION["level"] != 1) {
    redirect("../users/save_cabang.php");
}
if (post("nama")) {
    $nama = post("nama");
    $nomor = post("nomor");
    $pesan = utf8_encode(post("pesan"));
    $u = $_SESSION['username'];
    $count = countNo("nomor", "nomor", $nomor, "make_by", $u);
    if ($count == 0) {
        $q = mysqli_query($koneksi, "INSERT INTO nomor(`nama`, `nomor`,`pesan`, `make_by`)
            VALUES('$nama', '$nomor','$pesan', '$u')");
        swal_set("success", "Berhasil menambahkan nomor");
        redirect("save_cabang.php");
    } else {
        swal_set("error", "Nomor yang di input sudah tersedia");
        redirect("save_cabang.php");
    }
}
// update
if (post("num")) {
    $nama = post("name");
    $nomor = post("num");
    $id = post("id");
    $pesan = utf8_encode(post("pesan"));
    $update = mysqli_query($koneksi, "UPDATE `nomor` SET `nama` = '$nama', `nomor`='$nomor', `pesan`='$pesan' WHERE `nomor`.`id` = '$id'");
    swal_set("success", "Berhasil update contact " . $nama);
    redirect("save_cabang.php");
}

if (get("act") == "hapus") {
    $id = get("id");

    $q = mysqli_query($koneksi, "DELETE FROM nomor WHERE id='$id'");
    swal_set("success", "Berhasil menghapus nomor");
    redirect("save_cabang.php");
}

if (get("act") == "delete_all") {
    $q = mysqli_query($koneksi, "DELETE FROM nomor");
    swal_set("success", "Berhasil hapus semua nomor");
    redirect("save_cabang.php");
}
require_once('../templates/header.php');
?>


<div class="content-inner">
    <div class="container-fluid">
        <!-- Begin Page Header-->
        <div class="row">
            <div class="page-header">
                <div class="d-flex align-items-center">
                    <h2 class="page-header-title">Saved cabang</h2>
                    <div>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="dashboard.html"><i class="ti ti-home"></i></a></li>
                            <li class="breadcrumb-item active">Saved cabang</li>
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
                    <i class="ion ion-person-add"></i>Add cabang
                </button>
            </div>
        </div>
        <div class="mb-4"></div>
        <div class="row">
            <div class="col-xl-12">
                <!-- Sorting -->
                <div class="widget has-shadow">
                    <div class="widget-header bordered no-actions d-flex align-items-center">
                        <div class="col"> Saved cabang</div>
                        <a class="btn btn-info float-right" href="<?= $base_url; ?>lib/export_excel.php" style="margin:5px"><i class="ion ion-aperture"></i>Export Excel</a>
                        <button type="button" class="btn btn-success float-right" data-toggle="modal" style="margin:5px" data-target="#import"><i class="ion 
ion-archive"></i>
                            Import Excel
                        </button>
                        <a class="btn btn-danger float-right" href="save_cabang.php?act=delete_all" style="margin:5px"><i class="ion ion-trash-a"></i> Delete All</a>
                    </div>

                    <div class="widget-body">
                        <div class="table-responsive">
                            <table id="sorting-table" class="table mb-0">
                                <thead>
                                    <tr>
                                        <!-- <th>No.</th> -->
                                        <th>Branch Code</th>
                                        <th>Branch Name</th>
                                        <th>Address</th>
                                        <th>City</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $id = 0;
                                    $u = $_SESSION['username'];
                                    $q = mysqli_query($koneksi, "SELECT * FROM branch ");

                                    while ($row = mysqli_fetch_assoc($q)) {
                                        $id++;
                                        echo '<tr>';
                                        // echo '<td>' . $id . '</td>';
                                        echo '<td>' . $row['cd_branch'] . '</td>';
                                        echo '<td>' . $row['branch_name'] . '</td>';
                                        echo '<td>' . $row['address'] . '</td>';
                                        echo '<td>' . $row['city'] . '</td>';
                                        echo '<td class="td-actions"><a href="#" data-toggle="modal" data-target="#Update_' . $row['cd_branch'] . '"><i class="la la-edit edit"></i></a><a href="save_cabang.php?act=hapus&id=' . $row['cd_branch'] . '"><i class="la la-close delete"></i></a></td>';
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
                        <label>Name </label>
                        <input type="text" name="nama" required class="form-control">
                        <br>
                        <label>Nomor Whatsapp </label>
                        <input type="text" name="nomor" required class="form-control">
                        <br>
                        <label>Message Default </label>
                        <textarea type="text" name="pesan" required class="form-control" placeholder="Pesan Default"></textarea>
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
    $q = mysqli_query($koneksi, "SELECT * FROM branch ");
    while ($row = mysqli_fetch_assoc($q)) { ?>
        <div class="modal fade" id="Update_<?= $row['cd_branch'] ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Update Contact ~ <?= $row['branch_name'] ?></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="" method="POST">
                            <label>Branch Code </label>
                            <input type="text" name="cd_branch" disabled value="<?= $row['cd_branch'] ?>" class="form-control">
                            <br>
                            <label>Branch Name </label>
                            <input type="text" name="branch_name" disabled value="<?= $row['branch_name'] ?>" class="form-control">
                            <br>
                            <label>Address </label>
                            <textarea type="text" name="address" required class="form-control" placeholder="alamat"><?= $row['address'] ?></textarea>
                            <label>City</label>
                            <input type="text" name="city" value="<?= $row['city'] ?>" required class="form-control">
                            <br>
                            
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
                    <h5 class="modal-title" id="exampleModalLabel">Import Nomor</h5>
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
                        <label> Kolom Nama </label>
                        <input type="text" name="b" required class="form-control" value="1">
                        <br>
                        <label> Kolom Nomor </label>
                        <input type="text" name="c" required class="form-control" value="2">
                        <br>
                        <label> Kolom Pesan Default </label>
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
            $('#title').html('BLASTJET > Saved cabang')
        });
        document.getElementById("costumer-sid").classList.add("active");
    </script>
    </body>

    </html>