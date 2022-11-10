<?php
include_once("../helper/conn.php");
include_once("../helper/function.php");



$login = cekSession();
if ($login == 0) {
    redirect("login.php");
}
if ($_SESSION["level"] != 2) {
    redirect("../admin/dashboard.php");
}
if (isset($_POST['ambilkontak'])) {
    $u = $_SESSION['username'];
    $sender = $_POST['device'];

    $b = mysqli_query($koneksi, "SELECT * FROM all_contacts WHERE sender = '$sender'");
    while ($data = $b->fetch_assoc()) {
        $number = $data['number'];
        $nama = $data['name'];
        $type = $data['type'];
        $cek = mysqli_query($koneksi, "SELECT * FROM contacts WHERE sender = '$sender' AND number = '$number'");
        if ($cek->num_rows > 0) {
            swal_set("error", "Kontak dari nomor Whatsapp tersebut sudah ada di dalam database");
            redirect("saved_contact.php");
        } else {

            $insert = mysqli_query($koneksi, "INSERT INTO contacts VALUES('','$sender','$number','$nama','$type','$u')");
            swal_set("success", "Berhasil Ambil Kontak");
        }
    }
}

// Update Contact
if (post("type")) {
    $nomor = post("nomor");
    $nama = post("nama");
    $type = post("type");
    $u = $_SESSION['username'];
    mysqli_query($koneksi, "UPDATE `contacts` SET `name` = '$nama' WHERE `contacts`.`number` = '$nomor'");
    swal_set("success", "Berhasil Update Contact.");
    redirect("saved_contact.php");
}

if (get("act") == "hapus") {
    $u = $_SESSION['username'];
    $id = get("id");

    $q = mysqli_query($koneksi, "DELETE  FROM contacts WHERE id='$id' AND make_by = '$u'");
    swal_set("success", "Sukses hapus kontak");
    redirect("saved_contact.php");
}

if (get("act") == "delete_all") {
    $u = $_SESSION['username'];
    $q = mysqli_query($koneksi, "DELETE FROM contacts WHERE make_by = '$u'");
    swal_set("success", "Sukses hapus semua kontak");
    redirect("saved_contact.php");
}
require_once('../templates/header.php');
?>


<div class="content-inner">
    <div class="container-fluid">
        <!-- Begin Page Header-->
        <div class="row">
            <div class="page-header">
                <div class="d-flex align-items-center">
                    <h2 class="page-header-title">Saved Contact</h2>
                    <div>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="dashboard.html"><i class="ti ti-home"></i></a></li>
                            <li class="breadcrumb-item active">Saved Contact</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Page Header -->
        <!-- Default -->
        <div class="row container">
            <div class="col">

            </div>
            <a class="btn btn-info float-right" href="<?= $base_url; ?>lib/kontakgroup.php" style="margin:5px">Export Contact Group</a>
            <a class="btn btn-info float-right" href="<?= $base_url; ?>lib/kontakpersonal.php" style="margin:5px">Export Contact Personal</a>
        </div>
        <div class="mb-4"></div>
        <div class="row">
            <div class="col-xl-12">
                <!-- Sorting -->
                <div class="widget has-shadow">
                    <div class="widget-header bordered no-actions d-flex align-items-center">
                        <div class="col">Contacts Archive </div>
                        <button type="submit" class="btn btn-info float-right" data-toggle="modal" style="margin:5px" data-target="#ambilkontak"><i class="ion ion-archive"></i>
                            Get Contact
                        </button>
                        <a class="btn btn-danger float-right" href="saved_contact.php?act=delete_all" style="margin:5px"><i class="ion-trash-a"></i>Delete All</a>
                    </div>

                    <div class="widget-body">
                        <div class="table-responsive">
                            <table id="sorting-table" class="table mb-0">
                                <thead>
                                    <tr>
                                        <th>Sender</th>
                                        <th>Nama</th>
                                        <th>Nomor</th>
                                        <th>Type</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $u = $_SESSION['username'];
                                    $q = mysqli_query($koneksi, "SELECT * FROM contacts WHERE make_by='$u'");

                                    while ($row = mysqli_fetch_assoc($q)) {
                                        $number = $row['number'];
                                        echo '<tr>';
                                        echo '<td>' . $row['sender'] . '</td>';
                                        echo '<td>' . $row['name'] . '</td>';
                                        echo '<td>' . $number . '</td>';
                                        echo '<td>' . $row['type'] . '</td>';
                                        echo '<td class="td-actions"><a href="#" data-toggle="modal" data-target="#edit' . $number . '"><i class="la la-edit edit"></i></a><a href="saved_contact.php?act=hapus&id=' .
                                            $row['id'] . '"><i class="la la-close delete"></i></a></td>';
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

    <!-- Modal ambil kontak -->
    <div class="modal fade" id="ambilkontak" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Tambah Nomor</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="" method="POST">
                        <label> Kontak dari nomor ? </label>
                        <select class="form-control js-example-basic-multiple" name="device" style="width: 100%">
                            <?php

                            $u = $_SESSION['username'];
                            $q = mysqli_query($koneksi, "SELECT * FROM device WHERE pemilik='$u'");

                            while ($row = mysqli_fetch_assoc($q)) {
                                echo '<option value="' . $row['nomor'] . '">' . $row['nama'] . ' (' . $row['nomor'] . ')</option>';
                            }
                            ?>
                        </select>
                        <br>
                        <em style="color:red;font-size:13px">*Pastikan nomor tersebut sudah di scan dan connected</em>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" name="ambilkontak" class="btn btn-primary">Ambil</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <?php
    $u = $_SESSION['username'];
    $cek1 = mysqli_query($koneksi, "SELECT * FROM contacts WHERE make_by = '$u'");
    while ($view = $cek1->fetch_assoc()) { ?>
        <!-- Modal -->
        <div class="modal fade" id="edit<?= $view['number'] ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Edit Contact - <?= $view['name'] ?></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="" method="POST">
                            <label> Nama </label>
                            <input type="text" name="nama" value="<?= $view['name'] ?>" required class="form-control">
                            <br>
                            <label> Nomor </label>
                            <input type="text" name="nomor" required class="form-control" readonly value="<?= $view['number'] ?>">
                            <br>
                            <label> Type </label>
                            <input type="text" name="type" required class="form-control" readonly value="<?= $view['type'] ?>">
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
    <?php }
    include_once('../templates/footer.php')
    ?>
    <!-- Bootstrap core JavaScript-->
    <script src="<?= $base_url; ?>vendor/jquery/jquery.min.js"></script>
    <script src="<?= $base_url; ?>vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

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
            $('#title').html('BLASTJET > Saved Number')
        });
        document.getElementById("costumer-sid").classList.add("active");
    </script>
    </body>

    </html>