<?php
include_once("../helper/conn.php");
include_once("../helper/function.php");
// baca current date
$today = date("dmY");
$username = $_SESSION['username'];
$login = cekSession();
if ($login == 0) {
    redirect("login.php");
}
if ($_SESSION["level"] != 1) {
    redirect("../users/transaksi.php");
}


$query = mysqli_query($koneksi, "SELECT max(no_trx) as no_trx FROM transaksi");
$data = mysqli_fetch_array($query);
$no_trx = $data['no_trx'];

// mengambil angka dari no transaksi terbesar, menggunakan fungsi substr
// dan diubah ke integer dengan (int)
$urutan = (int) substr($no_trx, 3, 4);

// bilangan yang diambil ini ditambah 1 untuk menentukan nomor urut berikutnya
$urutan++;

// membentuk no transaksi baru
// perintah sprintf("%04s", $urutan); berguna untuk membuat string menjadi 4 karakter
// misalnya perintah sprintf("%04s", 15); maka akan menghasilkan '0015'
// angka yang diambil tadi digabungkan dengan kode huruf yang kita inginkan, misalnya TRX
$huruf = "TRX";
$no_trx = $huruf . sprintf("%04s", $urutan);


if (isset($_POST['tambah-transaksi'])) {
    $no_trx = post("no_trx");
    $nama = post("nama");
    $nomer_kamar = post("nomer_kamar");
    $dibayar = post("dibayar");
    $filemedia = post("filemedia");
    $sewa_bln = post("sewa_bln");
    $status_trx = post("status_trx");

    if (!empty($_FILES['filemedia']) && $_FILES['filemedia']['error'] == UPLOAD_ERR_OK) {
        // Be sure we're dealing with an upload
        if (is_uploaded_file($_FILES['filemedia']['tmp_name']) === false) {
            throw new \Exception('Error on upload: Invalid file definition');
        }


        if ($size > 1000000) {
            toastr_set("error", "Maximal 1mb");
            redirect("transaksi.php");
            exit;
        }
 // Rename the uploaded file
 $uploadName = $_FILES['filemedia']['name'];
 $ext = strtolower(substr($uploadName, strripos($uploadName, '.') + 1));

 $allow = ['png', 'jpeg', 'pdf', 'jpg'];
 if (in_array($ext, $allow)) {
     if ($ext == "pdf") {
         $filename = $username . '-' . round(microtime(true)) . $_FILES['filemedia']['name'];
     }
     if ($ext == "png") {
         $filename = $username . '-' . round(microtime(true)) . mt_rand() . '.jpg';
     }
     if ($ext == "jpg") {
         $filename = $username . '-' . round(microtime(true)) . $_FILES['filemedia']['name'];
     }

     if ($ext == "jpeg") {
         $filename = $username . '-' . round(microtime(true)) . $_FILES['filemedia']['name'];
     }
 } else {
     toastr_set("error", "Format png, jpg, pdf only");
     redirect("transaksi.php");
     exit;
 }
 mkdir('../uploads/base');
 move_uploaded_file($_FILES['filemedia']['tmp_name'], '../uploads/base/' . $filename);
 // Insert it into our tracking along with the original name
 $filemedia = $base_url . "uploads/base/" . $filename;
} else {
 $filemedia = null;
}

$u = $_SESSION['username'];
$cek = mysqli_query($koneksi, "SELECT * FROM transaksi WHERE no_trx = '$no_trx' OR sewa_bln = '$sewa_bln'");
    if (mysqli_num_rows($cek) > 0) {

        swal_set("error", "transaksi di input sudah tersedia");
        redirect("transaksi.php");
    } else {

        $q = mysqli_query($koneksi, "INSERT INTO transaksi(`id`,`no_trx`, `nama`,`nomer_kamar`,`dibayar`,`filemedia`,`sewa_bln`,`status_trx`,`make_by`)
        VALUES('','$no_trx', '$nama','$nomer_kamar','$dibayar','$filemedia','$sewa_bln','$status_trx', '$u')");
        swal_set("success", "Sukses input transaksi");
        redirect("transaksi.php");
    }

}

// Update transaksi

if (isset($_POST['update-transaksi'])) {
    $no_trx = post("no_trx");
    $dibayar = post("dibayar");
    $status_trx = post("status_trx");
    $update = mysqli_query($koneksi, "UPDATE `transaksi` SET `no_trx` = '$no_trx',`dibayar` = '$dibayar',`status_trx` = '$status_trx' WHERE `transaksi`.`no_trx` = '$no_trx'");
    if ($update) {
        swal_set("success", "Berhasil ubah status transaksi");
    } else {
        swal_set("error", "Gagal ubah status transaksi");
    }
}


if (get("act") == "hapus") {
    $id = get("id");

    $q = mysqli_query($koneksi, "DELETE FROM transaksi WHERE id='$id'");
    swal_set("success", "Berhasil menghapus data");
    redirect("transaksi.php");
}

if (get("act") == "delete_all") {
    $q = mysqli_query($koneksi, "DELETE FROM transaksi");
    swal_set("success", "Sukses hapus semua transaksi");
    redirect("transaksi.php");
}
require_once('../templates/header.php');
?>


<div class="content-inner">
    <div class="container-fluid">
        <!-- Begin Page Header-->
        <div class="row">
            <div class="page-header">
                <div class="d-flex align-items-center">
                    <h2 class="page-header-title">Transaksi</h2>
                    <div>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="dashboard.html"><i class="ti ti-home"></i></a></li>
                            <li class="breadcrumb-item active">Data Transaksi</li>
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
                    Tambah transaksi
                </button>
            </div>
        </div>
        <div class="mb-4"></div>
        <div class="row">
            <div class="col-xl-12">
                <!-- Sorting -->
                <div class="widget has-shadow">
                    <div class="widget-header bordered no-actions d-flex align-items-center">
                        <div class="col"> Data Transaksi</div>
                        <a class="btn btn-info float-right" href="<?= $base_url; ?>lib/export_excel.php" style="margin:5px">Export data transaksi</a>
                        <!-- <button type="button" class="btn btn-success float-right" data-toggle="modal" style="margin:5px" data-target="#import">
                            Import Excel
                        </button> -->
                        <!-- <a class="btn btn-danger float-right" href="transaksi.php?act=delete_all" style="margin:5px">Hapus Semua</a> -->
                    </div>

                    <div class="widget-body">
                        <div class="table-responsive">
                            <table id="sorting-table" class="table mb-0">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>No. Transkasi</th>
                                        <th>Nama</th>
                                        <th>No.Kamar</th>
                                        <th>Dibayar</th>
                                        <th>Bukti pembayaran</th>
                                        <th>Sewa bulan</th>
                                        <th>status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                  <!-- halaman -->
                                  <?php
                                  $no = 0;
                                  
                                    $q = mysqli_query($koneksi, "SELECT * FROM transaksi  ORDER BY id DESC");

                                    while ($row = mysqli_fetch_assoc($q)) {
                                        $filemedia = $row['filemedia'];
                                        $no++;
                                        echo '<tr>';
                                        echo '<td>' . $no . '</td>';
                                        echo '<td>' . $row['no_trx'] . '</td>';
                                        echo '<td>' . $row['nama'] . '</td>';
                                        echo '<td>' . $row['nomer_kamar'] . '</td>';
                                        echo '<td>' .'Rp. '.number_format($row['dibayar'],0,"",".")  . '</td>';
                                        if (substr($row['filemedia'], -3) == "pdf") {
                                            echo '<td><a href="' . $row['filemedia'] . '" target="_blank"><img src="../assets/img/pdf.png" title="' . $row['filemedia'] . '" style="width:40px"></a></td>';
                                        } else {
                                            echo '<td><a href="' . $row['filemedia'] . '" target="_blank"><img src="' . $row['filemedia'] . '" title="' . $row['filemedia'] . '" style="width:40px">' .  '</a></td>';
                                        }
                                        // echo '<td>' .'<img'. 'src="uploads/base/broadcast-filefilefilemedia/'.$row['filefilemedia'].'"></img>'.'</td>';
                                        echo '<td>' . $row['sewa_bln'] . '</td>';
                                        if ($row['status_trx'] == "Lunas") {
                                            echo '<td><span class="badge badge-success status-container-' . $row['id'] . '">Lunas</span></td>';
                                        } else if ($row['status_trx'] == "DP") {
                                            echo '<td><span class="badge badge-warning status-container-' . $row['id'] . '">DP</span></td>';
                                        } else {
                                            echo '<td><span class="badge badge-danger status-container-' . $row['id'] . '">Pending</span></td>';
                                        }
                                        echo '<td class="td-actions"><a href="#" data-toggle="modal" data-target="#Edit' . $row['id'] . '"><i class="la la-edit edit"></i></a><a href="transaksi.php?act=hapus&id=' . $row['id'] . '"><i class="la la-close delete"></i></a></td>';
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
        <!-- Modal edit -->
        <?php
        $q = mysqli_query($koneksi, "SELECT * FROM transaksi");
        while ($row = mysqli_fetch_assoc($q)) { ?>
            <!-- Modal edit -->
            <div class="modal fade" id="Edit<?= $row['id'] ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Ubah status transaksi <?= $row['no_trx'] ?></h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form action="" method="POST" enctype="multipart/form-data">
                                <label>no_trx </label>
                                <input type="text" name="no_trx" readonly class="form-control" value="<?= $row['no_trx'] ?>">
                                <br>
                                <label>Nama</label>
                                <input type="text" name="nama" readonly class="form-control" value="<?= $row['nama'] ?>">
                                <br>
                                <label>No. Kamar</label>
                                <input type="text" name="nomer_kamar" readonly class="form-control" value="<?= $row['nomer_kamar'] ?>">
                                <br>
                                <label>Dibayar</label>
                                <input type="text" name="dibayar" class="form-control" value="<?= $row['dibayar'] ?>">
                                <br>
                                <label>Sewa bulan</label>
                                <input type="text" name="sewa_bln" readonly class="form-control" value="<?= $row['sewa_bln'] ?>">
                                <br>
                                <label>Status </label>
                                <select class="form-control" name="status_trx" required>
                                    <option value="" disabled selected><?=$row['status_trx']?> </option>
                                    <option value="Pending">Pending</option>
                                    <option value="DP">DP</option>
                                    <option value="Lunas">Lunas</option>
                                </select>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" name="update-transaksi" class="btn btn-primary">Simpan</button>
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
                    <h5 class="modal-title" id="exampleModalLabel">Tambah Data penghuni</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="" method="POST" enctype="multipart/form-data">

                    <label>No.Transaksi</label>
                    <input type="text" name="no_trx"  class="form-control"  required="required" value="<?php echo $no_trx ?>" readonly>

                    <label>Nama </label>
                        <!-- <select class="form-control js-example-basic-multiple" name="nama" style="width: 100%"> -->
                        <?php
                        $u = $_SESSION['username'];
                        $q = mysqli_query($koneksi, "SELECT * FROM kosan WHERE make_by='$u'");
                        $jsArray = "var nm= new Array();\n";
                        echo '<select class="form-control "name="nama" onchange="document.getElementById(\'nama\').value = nm[this.value]" >';
                                echo '<option>-------</option>';
                                while ($row = mysqli_fetch_array($q)) {
                                    echo '<option value="' . $row['nama'] . '">' .$row['nama']  .'('. 'Rp. '.number_format($row['harga'],0,"","."). ')</option>';
                                    $jsArray .= "nm['" . $row['nama'] . "'] = '" . addslashes($row['nomer_kamar']) . "';\n";
                                    // $jsArray .= "nm['" . $row['nama'] . "'] = {jk:'" . addslashes($row['jenis_kamar']) . "',almt:'".addslashes($row['alamat'])."'};\n";
                                }
                                ?>
                            </select>
                        <br>
                        <!-- //menampilkan data berdasarkan pilihan combobox ke dalam form -->
                        <label>Nomer Kamar </label>
                        <input type="text" name="nomer_kamar" required class="form-control" id="nama" readonly>
                        <br>
                        <label>dibayar</label>
                        <input type="number" name="dibayar" class="form-control" required>
                        <br>
                        <label>Bukti pembayaran</label> <em style="color:red;font-size:12px">*Max 1 mb </em>
                        <input type="file" name="filemedia" class="form-control" required>
                        <br>
                        <label for="sewa_bln">Sewa Bulan: </label>
                        <input type="month" id="bdaymonth" name="sewa_bln">
                        <br>
                        <label>Status</label>
                        <select class="form-control" name="status_trx" style="width: 100%">
                            <option >--Pilih status--</option>;
                            <option value="DP">DP</option>;
                            <option value="Pending">Pending</option>;
                            <option value="Lunas">Lunas</option>;
                        </select>
                        <br>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" name="tambah-transaksi" class="btn btn-primary">Simpan</button>  
                </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="import" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Import nama</h5>
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
                        <label> Kolom no_trx </label>
                        <input type="text" name="b" required class="form-control" value="1">
                        <br>
                        <label> Kolom nama </label>
                        <input type="text" name="c" required class="form-control" value="2">
                        <br>
                        <label> Kolom alamat Default </label>
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
     <Script>
        <?php

        toastr_show();
        swal_show();

        ?>
        $(document).ready(function() {
            $('#title').html('KOSAN ONLINE > Transaksi')
        });
        document.getElementById("transaksi-sid").classList.add("active");
    </script>
    <script type="text/javascript">
        <?php echo $jsArray; ?>
    </script>


    </body>

    </html>