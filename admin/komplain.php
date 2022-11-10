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
    redirect("../users/komplain.php");
}


$query = mysqli_query($koneksi, "SELECT max(no_komplain) as no_komplain FROM komplain");
$data = mysqli_fetch_array($query);
$no_komplain = $data['no_komplain'];

// mengambil angka dari no komplain terbesar, menggunakan fungsi substr
// dan diubah ke integer dengan (int)
$urutan = (int) substr($no_komplain, 3, 4);

// bilangan yang diambil ini ditambah 1 untuk menentukan nomor urut berikutnya
$urutan++;

// membentuk no komplain baru
// perintah sprintf("%04s", $urutan); berguna untuk membuat string menjadi 4 karakter
// misalnya perintah sprintf("%04s", 15); maka akan menghasilkan '0015'
// angka yang diambil tadi digabungkan dengan kode huruf yang kita inginkan, misalnya TRX
$huruf = "PRB";
$no_komplain = $huruf . sprintf("%04s", $urutan).$today;


if (isset($_POST['tambah-komplain'])) {
    $id = get("id");
    $no_komplain = post("no_komplain");
    $nomor = post("nomor");
    $nama = post("nama");
    $nomer_kamar = post("nomer_kamar");
    $problem = utf8_encode(post("problem"));
    $photo_awal = post("photo_awal");
    $status = post("status");
    $tgl_selesai = date("Y-m-d", strtotime(post("tgl_selesai")));
    if (!empty($_FILES['photo_awal']) && $_FILES['photo_awal']['error'] == UPLOAD_ERR_OK) {
        // Be sure we're dealing with an upload
        if (is_uploaded_file($_FILES['photo_awal']['tmp_name']) === false) {
            throw new \Exception('Error on upload: Invalid file definition');
        }


        if ($size > 1000000) {
            swal_set("error", "Maximal 1mb");
            redirect("komplain.php");
            exit;
        }
 // Rename the uploaded file
 $uploadName = $_FILES['photo_awal']['name'];
 $ext = strtolower(substr($uploadName, strripos($uploadName, '.') + 1));

 $allow = ['png', 'jpeg', 'pdf', 'jpg'];
 if (in_array($ext, $allow)) {
     if ($ext == "pdf") {
         $filename = $username . '-' . round(microtime(true)) . $_FILES['photo_awal']['name'];
     }
     if ($ext == "png") {
         $filename = $username . '-' . round(microtime(true)) . mt_rand() . '.jpg';
     }
     if ($ext == "jpg") {
         $filename = $username . '-' . round(microtime(true)) . $_FILES['photo_awal']['name'];
     }

     if ($ext == "jpeg") {
         $filename = $username . '-' . round(microtime(true)) . $_FILES['photo_awal']['name'];
     }
 } else {
     swal_set("error", "Format png, jpg, pdf only");
     redirect("komplain.php");
     exit;
 }
 mkdir('../uploads/komplain');
 move_uploaded_file($_FILES['photo_awal']['tmp_name'], '../uploads/komplain/' . $filename);
 // Insert it into our tracking along with the original name
 $photo_awal = $base_url . "uploads/komplain/" . $filename;
} else {
 $photo_awal = null;
}
$u = $_SESSION['username'];
$cek = mysqli_query($koneksi, "SELECT * FROM komplain WHERE no_komplain = '$no_komplain'");
    if (mysqli_num_rows($cek) > 0) {

        swal_set("error", "no komplain di input sudah tersedia");
        redirect("komplain.php");
    } else {

        $q = mysqli_query($koneksi, "INSERT INTO komplain(`id`,`no_komplain`,`nomor`, `nama`,`nomer_kamar`,`photo_awal`,`problem`,`status`,`tgl_selesai`,`make_by`)
        VALUES('','$no_komplain', '$nomor', '$nama','$nomer_kamar','$photo_awal','$problem','$status','$tgl_selesai','$u')");
        swal_set("success", "Sukses input komplain");
        redirect("komplain.php");
    }

}


// Update komplain

if (isset($_POST['update-komplain'])) {
    $no_komplain = post("no_komplain");
    $status = post("status");
    $tgl_selesai = date("Y-m-d", strtotime(post("tgl_selesai")));
    // $q = mysqli_query($koneksi, "INSERT INTO komplain (`status`,`tgl_selesai`)
    // VALUES('$status','$tgl_selesai')");
    $update = mysqli_query($koneksi, "UPDATE `komplain` SET `no_komplain` = '$no_komplain',`status` = '$status' ,`tgl_selesai` = '$tgl_selesai' WHERE `komplain`.`no_komplain` = '$no_komplain'");
    if ($update) {
        swal_set("success", "Berhasil ubah status komplain");
    } else {
        swal_set("error", "Gagal ubah status komplain");
    }
}


if (get("act") == "hapus") {
    $id = get("id");

    $q = mysqli_query($koneksi, "DELETE FROM komplain WHERE id='$id'");
    swal_set("success", "Berhasil menghapus data");
    redirect("komplain.php");
}

if (get("act") == "delete_all") {
    $q = mysqli_query($koneksi, "DELETE FROM komplain");
    swal_set("success", "Sukses hapus semua komplain");
    redirect("komplain.php");
}
require_once('../templates/header.php');
?>


<div class="content-inner">
    <div class="container-fluid">
        <!-- Begin Page Header-->
        <div class="row">
            <div class="page-header">
                <div class="d-flex align-items-center">
                    <h2 class="page-header-title">komplain</h2>
                    <div>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="dashboard.html"><i class="ti ti-home"></i></a></li>
                            <li class="breadcrumb-item active">Data komplain</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Page Header -->
        <!-- <div class="alert alert-danger alert-dissmissible fade show" role="alert">
            <div class="text-center">MASIH DALAM PROSES PENGEMBANGAN</div>
            <div class="text-center"><a href="<?= $base_url; ?>lib/template-import-kontak.xlsx" class="btn btn-success text-center">Download Template</a></div>
        </div> -->
        <!-- Default -->

        <div class="mb-4"></div>
        <div class="row">
            <div class="col-xl-12">
                <!-- Sorting -->
                <div class="widget has-shadow">
                    <div class="widget-header bordered no-actions d-flex align-items-center">
                        <div class="col"> Data komplain</div>
                        <a class="btn btn-info float-right" href="<?= $base_url; ?>lib/export_excel_komplain.php" style="margin:5px">Export data komplain</a>
                        <!-- <button type="button" class="btn btn-success float-right" data-toggle="modal" style="margin:5px" data-target="#import">
                            Import Excel
                        </button> -->
                        <!-- <a class="btn btn-danger float-right" href="komplain.php?act=delete_all" style="margin:5px">Hapus Semua</a> -->
                    </div>

                    <div class="widget-body">
                        <div class="table-responsive">
                            <table id="sorting-table" class="table mb-0">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>No. Komplain</th>
                                        <th>Nomor</th>
                                        <th>Nama</th>
                                        <th>No.Kamar</th>
                                        <th>Bukti Photo</th>
                                        <th>Problem</th>
                                        <th>Status</th>
                                        <th>Tgl Selesai dikerjakan</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                  <!-- halaman -->
                                  <?php
                                  $no = 0;
                                  $u = $_SESSION['username'];
                                    $q = mysqli_query($koneksi, "SELECT * FROM komplain  ORDER BY no_komplain DESC");
                                    while ($row = mysqli_fetch_assoc($q)) {

                                        $no++;
                                        echo '<tr>';
                                        echo '<td>' . $no . '</td>';
                                        echo '<td>' . $row['no_komplain'] . '</td>';
                                        echo '<td>' . $row['nomor'] . '</td>';
                                        echo '<td>' . $row['nama'] . '</td>';
                                        echo '<td>' . $row['nomer_kamar'] . '</td>';
                                        if (substr($row['photo_awal'], -3) == "pdf") {
                                            echo '<td><a href="' . $row['photo_awal'] . '" target="_blank"><img src="../assets/img/pdf.png" title="' . $row['photo_awal'] . '" style="width:40px"></a></td>';
                                        } else {
                                            echo '<td><a href="' . $row['photo_awal'] . '" target="_blank"><img src="' . $row['photo_awal'] . '" title="' . $row['photo_awal'] . '" style="width:40px">' .  '</a></td>';
                                        }
                                        echo '<td>' . $row['problem'] . '</td>';
                                        if ($row['status'] == "selesai") {
                                            echo '<td><span class="badge badge-success status-container-' . $row['id'] . '">Selesai</span></td>';
                                        } else if ($row['status'] == "dikerjakan") {
                                            echo '<td><span class="badge badge-warning status-container-' . $row['id'] . '">Dikerjakan</span></td>';
                                        } else {
                                            echo '<td><span class="badge badge-danger status-container-' . $row['id'] . '">Belum</span></td>';
                                        }

                                        // echo '<td>' .'<img'. 'src="uploads/komplain/broadcast-photo_awal/'.$row['photo_awal'].'"></img>'.'</td>';
                                        echo '<td>' . $row['tgl_selesai'] . '</td>';
                                        echo '<td class="td-actions"><a href="#" data-toggle="modal" data-target="#Edit' . $row['id'] . '"><i class="la la-edit edit"></i></a>';
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
        $q = mysqli_query($koneksi, "SELECT * FROM komplain");
        while ($row = mysqli_fetch_assoc($q)) { ?>
            <!-- Modal edit -->
            <div class="modal fade" id="Edit<?= $row['id'] ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Ubah status transaksi <?= $row['no_komplain'] ?></h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form action="" method="POST" enctype="multipart/form-data">
                                <label>No. Komplain </label>
                                <input type="text" name="no_komplain" readonly class="form-control" value="<?= $row['no_komplain'] ?>">
                                <br>
                                <label>Nama</label>
                                <input type="text" name="nama" readonly class="form-control" value="<?= $row['nama'] ?>">
                                <br>
                                <label>No. Kamar</label>
                                <input type="text" name="nomer_kamar" readonly class="form-control" value="<?= $row['nomer_kamar'] ?>">
                                <br>
                                <label>problem</label>
                                <textarea type="text" name="problem" readonly class="form-control" value=""><?= $row['problem'] ?></textarea>
                                <br>
                                <label>Status</label>
                                <select class="form-control" name="status" style="width: 100%"  required>
                                    <option >--Pilih status--</option>;
                                <option value="belum">Belum</option>;
                                <option value="dikerjakan">Dikerjakan</option>;
                                <option value="selesai">Selesai</option>;
                                </select>
                                <br>
                                <label> Tanggal Selesai</label>
                                <input type="date" name="tgl_selesai" class="form-control" >
                                <br>

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" name="update-komplain" class="btn btn-primary">Simpan</button>
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
                    <h5 class="modal-title" id="exampleModalLabel">Tambah Komplain</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="" method="POST" enctype="multipart/form-data">

                    <label>No.komplain</label>
                    <input type="text" name="no_komplain"  class="form-control"  required="required" value="<?php echo $no_komplain ?>" readonly>
                    <label>Nomer Kamar </label>
                        <!-- <select class="form-control js-example-basic-multiple" name="nama" style="width: 100%"> -->
                        <?php
                        $u = "admin";
                        $q = mysqli_query($koneksi, "SELECT * FROM kosan WHERE make_by='$u'");
                        $jsArray = "var nm= new Array();\n";
                        echo '<select class="form-control "name="nomer_kamar" onchange="changeValue(this.value)" >';
                        // echo '<select class="form-control "name="nama" onchange="document.getElementById(\'nama\').value = nm[this.value]" >';
                                echo '<option>--Pilih Nomer Kamar--</option>';
                                while ($row = mysqli_fetch_array($q)) {
                                    echo '<option value="' . $row['nomer_kamar'] . '">' .$row['nomer_kamar']  .'</option>';
                                    // $jsArray .= "nm['" . $row['nama'] . "'] = '" . addslashes($row['nomer_kamar']) . "';\n";
                                    $jsArray .= "nm['" . $row['nomer_kamar'] . "'] = {nma:'" . addslashes($row['nama']) . "',usr:'".addslashes($row['nomor'])."'};\n";
                                }
                                ?>
                            </select>
                        <br>
                        <!-- //menampilkan data berdasarkan pilihan combobox ke dalam form -->
                        <label>Nama Penghuni </label>
                        <input type="text" name="nama" required class="form-control" id="nama" readonly>
                        <br>
                        <input type="text" name="nomor" required class="form-control" id="nomor" hidden>
                        <br>
                        <label>Photo Bukti</label> <em style="color:red;font-size:12px">*Max 1 mb </em>
                        <input type="file" name="photo_awal" class="form-control" required>
                        <br>
                        <label>problem</label>
                        <textarea type="text" name="problem" class="form-control" required></textarea>
                        <br>

                        <label>Status</label>
                        <select class="form-control" name="status" style="width: 100%" value="Belum" readonly>
                            <!-- <option >--Pilih status--</option>; -->
                            <option value="belum">Belum</option>;
                            </select>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" name="tambah-komplain" class="btn btn-primary">Simpan</button>  
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
                        <label> Kolom no_komplain </label>
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
            $('#title').html('KOSAN ONLINE > Komplain')
        });
        document.getElementById("komplain-sid").classList.add("active");
    </script>
    <script type="text/javascript">
        <?php echo $jsArray; ?>
        function changeValue(id){
document.getElementById('nama').value = nm[id].nma;
document.getElementById('nomor').value = nm[id].usr;

};
    </script>


    </body>

    </html>