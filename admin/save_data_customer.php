<?php
include_once("../helper/conn.php");
include_once("../helper/function.php");


$login = cekSession();
if ($login == 0) {
    redirect("login.php");
}
if ($_SESSION["level"] != 1) {
    redirect("../users/dashboard.php");
}
$query = mysqli_query($koneksi, "SELECT max(id) as noid FROM customer");
$data = mysqli_fetch_array($query);
$no_k = $data['noid'];

// mengambil angka dari no customer terbesar, menggunakan fungsi substr
// dan diubah ke integer dengan (int)
$urutan = (int) substr($no_k, 3, 3);

// bilangan yang diambil ini ditambah 1 untuk menentukan nomor urut berikutnya
$urutan++;

// membentuk no customer baru
// perintah sprintf("%03s", $urutan); berguna untuk membuat string menjadi 3 karakter
// misalnya perintah sprintf("%03s", 15); maka akan menghasilkan '015'
// angka yang diambil tadi digabungkan dengan kode huruf yang kita inginkan, misalnya customer
$huruf = "KMR";
$no_customer = $huruf . sprintf("%03s", $urutan);



if (isset($_POST['tambah-customer'])) {
    $nomer_customer = post("nomer_customer");
    $jenis_customer = post("jenis_customer");
    $harga = post("harga");
    $ket = utf8_encode(post("ket"));
    $status = post("status");
    $u = $_SESSION['username'];

    $cek = mysqli_query($koneksi, "SELECT * FROM customer WHERE nomor_customer = '$nomor_customer'");
    if (mysqli_num_rows($cek) > 0) {

        swal_set("error", "Data customer di input sudah tersedia");
        redirect("save_data_customer.php");
    } else {
        $q = mysqli_query($koneksi, "INSERT INTO customer(`nomer_customer`,`jenis_customer`, `harga`,`ket`,`status`, `make_by`)
             VALUES('$nomer_customer','$jenis_customer', '$harga','$ket','Kosong', '$u')");
        swal_set("success", "Sukses input data customer");
        redirect("save_data_customer.php");
    }

    // $count = countNo("customer", "nomer_customer", $nomer_customer, "make_by", $u);
    // if ($count == 0) {
    //     $q = mysqli_query($koneksi, "INSERT INTO customer(`nomer_customer`,`jenis_customer`, `harga`,`ket`,`status`, `make_by`)
    //         VALUES('$nomer_customer','$jenis_customer', '$harga','$ket','Kosong', '$u')");
    //    swal_set("success", "Sukses input Data customer");
    //     redirect("save_data_customer.php");
    // } else {
    //     swal_set("error", "Data customer yang di input sudah tersedia");
    //     redirect("save_data_customer.php");
    // }
}

if (get("act") == "hapus") {
    $id = get("id");

    $q = mysqli_query($koneksi, "DELETE FROM customer WHERE id='$id'");
    swal_set("success", "Berhasil hapus Data customer");
    redirect("save_data_customer.php");
}

if (get("act") == "delete_all") {
    $q = mysqli_query($koneksi, "DELETE FROM customer");
    swal_set("success", "Sukses hapus semua Data customer");
    redirect("save_data_customer.php");
}

//update data customer

if (isset($_POST['update-customer'])) {
    $nomer_customer = post("nomer_customer");
    $jenis_customer = post("jenis_customer");
    $harga = post("harga");
    $ket = post("ket");
    $u = $_SESSION['username'];
    $update = mysqli_query($koneksi, "UPDATE `customer` SET `nomer_customer` = '$nomer_customer',`jenis_customer` = '$jenis_customer',`harga` = '$harga',`ket` = '$ket' WHERE `customer`.`nomer_customer` = '$nomer_customer'");
    if ($update) {
        swal_set("success", "Berhasil update data customer");
    } else {
        swal_set("error", "Gagal update data customer");
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
                    <h2 class="page-header-title">Saved Data customer</h2>
                    <div>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="admin/dashboard.php"><i class="ti ti-home"></i></a></li>
                            <li class="breadcrumb-item active">Saved Data customer</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Page Header -->
        <!-- <div class="alert alert-danger alert-dissmissible fade show" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"></button>
            <div class="text-center">Import Kontak langsung dengan Excel?</div>
            <div class="text-center"><a href="<?= $base_url; ?>lib/template-import-kontak.xlsx" class="btn btn-success text-center">Download Template</a></div>
        </div> -->
        <!-- Default -->
        <div class="row container">
            <div class="col">
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
                    Add Data customer
                </button>
            </div>
        </div>
        <div class="mb-4"></div>
        <div class="row">
            <div class="col-xl-12">
                <!-- Sorting -->
                <div class="widget has-shadow">
                    <div class="widget-header bordered no-actions d-flex align-items-center">
                        <div class="col"> Saved Data customer</div>
                        <a class="btn btn-info float-right" href="<?= $base_url; ?>lib/export_excelcustomer.php" style="margin:5px">Export Nomor</a>
                        <button type="button" class="btn btn-success float-right" data-toggle="modal" style="margin:5px" data-target="#import">
                            Import Excel
                        </button>
                        <a class="btn btn-danger float-right" href="save_data_customer.php?act=delete_all" style="margin:5px">Hapus Semua</a>
                    </div>

                    <div class="widget-body">
                        <div class="table-responsive">
                            <table id="sorting-table" class="table mb-0">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>Nomer customer</th>
                                        <th>Fasilitas</th>
                                        <th>Harga</th>
                                        <th>ket tambahan</th>
                                        <!-- <th>Status</th> -->
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- halaman -->
                                        <?php
                                    $u = $_SESSION['username'];
                                    $q = mysqli_query($koneksi, "SELECT * FROM customer WHERE make_by='$u' ORDER BY id DESC");
                                    while ($row = mysqli_fetch_assoc($q)) {
                                        $id = $row['id'];
                                        $no++;
                                        echo '<tr>';
                                        echo '<td>' . $no . '</td>';
                                        echo '<td>' . $row['nomer_customer'] . '</td>';
                                        echo '<td>' . $row['jenis_customer'] . '</td>';
                                        echo '<td>' .'Rp. '.number_format($row['harga'],0,"",".")  . '</td>';
                                        echo '<td>' . utf8_decode($row['ket']) . '</td>';
                                        // echo '<td>' . $row['status'] . '</td>';
                                        echo '<td class="td-actions"><a href="#" data-toggle="modal" data-target="#Edit' . $row['id'] . '"><i class="la la-edit edit"></i></a><a href="save_data_customer.php?act=hapus&id=' . $row['id'] . '"><i class="la la-close delete"></i></a></td>';
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
        <div style="height: 40px;"></div>
    </div>
        <!-- Modal edit -->
        <?php
        $q = mysqli_query($koneksi, "SELECT * FROM customer");
        while ($row = mysqli_fetch_assoc($q)) { ?>
            <!-- Modal edit -->
            <div class="modal fade" id="Edit<?= $row['id'] ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Ubah status customer <?= $row['nomer_customer'] ?></h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form action="" method="POST">
                                <label>Nomer customer </label>
                                <input type="text" name="nomer_customer" readonly class="form-control" value="<?= $row['nomer_customer'] ?>">
                                <br>
                                <label>Fasilitas</label>
                                <select class="form-control" name="jenis_customer" required>
                                    <option value="" disabled selected><?=$row['jenis_customer']?> </option>
                                    <option value="AC">AC</option>
                                    <option value="Non AC">Non AC</option>
                                </select>
                                <br>
                                <label>Harga</label>
                                <input type="number" name="harga" class="form-control" value="<?= $row['harga'] ?>">
                                <br>
                                <label>Keterangan Tambahan</label>
                                <textarea type="text" name="ket"  class="form-control" value=""><?= $row['ket'] ?></textarea>
                                <br>

                                <!-- <br>
                                <label>Status </label>
                                <select class="form-control" name="status" required>
                                    <option value="" disabled selected><?php if ($row['aktif'] == 1) {
                                                                            echo 'Aktif';
                                                                        } else {
                                                                            echo 'Nonaktif';
                                                                        } ?></option>
                                    <option value="1">Aktif</option>
                                    <option value="0">Nonaktif</option>
                                </select>
                                <br> -->
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" name="update-customer" class="btn btn-primary">Simpan</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!--Close Modal edit -->
        <?php  }
        ?>
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Tambah Data customer</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="" method="POST" enctype="multipart/form-data">
                        <label>Nomer customer </label>
                        <input type="text" name="nomer_customer" class="form-control" required="required" value="<?php echo $no_customer ?>" readonly>
                        <br>
                        <br>
                        <label>Fasilitas</label>
                        <select class="form-control" name="jenis_customer" style="width: 100%">
                                    <option value="AC">AC</option>
                                    <option value="Non AC">Non AC</option>
                        </select>
                        <br>
                        <label>Harga </label>
                        <input type="text" name="harga" required class="form-control">
                        <br>
                        <label>Ket Tambahan </label>
                        <textarea type="text" name="ket"  class="form-control" placeholder="ket bila ada tambahan info dll"></textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" name="tambah-customer" class="btn btn-primary">Simpan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="import" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Import Data customer</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="../lib/import_excelcustomer.php" method="POST" enctype="multipart/form-data">
                        <label> File Excel (.xlsx) </label>
                        <input type="file" name="file" required class="form-control">
                        <br>
                        <label> Mulai dari Baris</label>
                        <input type="text" name="a" required class="form-control" value="5">
                        <br>
                        <label> Kolom Nomer customer </label>
                        <input type="text" name="b" required class="form-control" value="1">
                        <br>
                        <label> Kolom Jenis customer</label>
                        <input type="text" name="c" required class="form-control" value="2">
                        <br>
                        <br>
                        <label> Kolom Harga</label>
                        <input type="text" name="d" required class="form-control" value="3">
                        <br>
                        <label> Kolom ket Tambahan </label>
                        <input type="text" name="e" required class="form-control" value="4">
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
            $('#title').html('KOSAN ONLINE > Saved Data customer')
        });
        document.getElementById("customer-sid").classList.add("active");
    </script>
    </body>

    </html>