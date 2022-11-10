
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
if (isset($_POST['tambah-kosan'])) {
    $nama = post("nama");
    $nomor = post("nomor");
    $username = post("username");
    $media = post("media");
    $nomer_kamar = post("nomer_kamar");
    $jenis_kamar = post("jenis_kamar");
    $harga = post("harga");
    $alamat = utf8_encode(post("alamat"));
    $tgl_pakai = date("Y-m-d", strtotime(post("tgl_pakai")));


    if (!empty($_FILES['media']) && $_FILES['media']['error'] == UPLOAD_ERR_OK) {
        // Be sure we're dealing with an upload
        if (is_uploaded_file($_FILES['media']['tmp_name']) === false) {
            throw new \Exception('Error on upload: Invalid file definition');
        }


        if ($size > 1000000) {
            toastr_set("error", "Maximal 1mb");
            redirect("save_number.php");
            exit;
        }
 // Rename the uploaded file
 $uploadName = $_FILES['media']['name'];
 $ext = strtolower(substr($uploadName, strripos($uploadName, '.') + 1));

 $allow = ['png', 'jpeg', 'pdf', 'jpg'];
 if (in_array($ext, $allow)) {
     if ($ext == "pdf") {
         $filename = $username . '-' . round(microtime(true)) . $_FILES['media']['name'];
     }
     if ($ext == "png") {
         $filename = $username . '-' . round(microtime(true)) . mt_rand() . '.jpg';
     }
     if ($ext == "jpg") {
         $filename = $username . '-' . round(microtime(true)) . $_FILES['media']['name'];
     }

     if ($ext == "jpeg") {
         $filename = $username . '-' . round(microtime(true)) . $_FILES['media']['name'];
     }
 } else {
     toastr_set("error", "Format png, jpg, pdf only");
     redirect("save_data_kosan.php");
     exit;
 }
 mkdir('../uploads/base');
 move_uploaded_file($_FILES['media']['tmp_name'], '../uploads/base/' . $filename);
 // Insert it into our tracking along with the original name
 $media = $base_url . "uploads/base/" . $filename;
} else {
 $media = null;
}

$u = $_SESSION['username'];

$cek = mysqli_query($koneksi, "SELECT * FROM kosan WHERE nomor = '$nomor' OR nomer_kamar = '$nomer_kamar'");
    if (mysqli_num_rows($cek) > 0) {

        swal_set("error", "Data Pengguna Kosan di input sudah tersedia");
        redirect("save_data_kosan.php");
    } else {
        $q = mysqli_query($koneksi, "INSERT INTO kosan(`id`,`nama`,`nomor`,`username`,`media`,`nomer_kamar`, `jenis_kamar`, `harga`,`alamat`,`tgl_pakai`, `make_by`)
            VALUES('','$nama','$nomor','$username','$media','$nomer_kamar', '$jenis_kamar', '$harga','$alamat','$tgl_pakai', '$u')");
        swal_set("success", "Sukses input data penghuni kosan");
        redirect("save_data_kosan.php");
    }

}

if (get("act") == "hapus") {
    $id = get("id");

    $q = mysqli_query($koneksi, "DELETE FROM kosan WHERE id='$id'");
    swal_set("success", "Berhasil hapus Data Kosan");
    redirect("save_data_kosan.php");
}

if (get("act") == "delete_all") {
    $q = mysqli_query($koneksi, "DELETE FROM kosan");
    swal_set("success", "Sukses hapus semua Data Kosan");
    redirect("save_data_kosan.php");
}

//update data kosan
if (isset($_POST['update-kosan'])) {
    $nomer_kamar = post("nomer_kamar");
    $nama = post("nama");
    $nomor = post("nomor");
    $username = post("username");
    $media = post("media");
    $jenis_kamar = post("jenis_kamar");
    $harga = post("harga");
    $alamat = post("alamat");
    $tgl_pakai = date("Y-m-d", strtotime(post("tgl_pakai")));
    $u = $_SESSION['username'];
    $update = mysqli_query($koneksi, "UPDATE `kosan` SET `nomer_kamar` = '$nomer_kamar',`nama` = '$nama',`jenis_kamar` = '$jenis_kamar',`harga` = '$harga',`alamat` = '$alamat',`tgl_pakai` = '$tgl_pakai' WHERE `kosan`.`nomer_kamar` = '$nomer_kamar'");
    if ($update) {
        swal_set("success", "Berhasil update data penghuni kosan");
    } else {
        swal_set("error", "Gagal update data penghuni kosan");
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
                    <h2 class="page-header-title">Data penghuni Kosan</h2>
                    <div>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="admin/dashboard.php"><i class="ti ti-home"></i></a></li>
                            <li class="breadcrumb-item active">Data penghuni Kosan</li>
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
                <div style="height: 40px;"></div>
        <div class="row">
            <div class="col-xl-12">
                <!-- Sorting -->
                <div class="widget has-shadow">
                    <div class="widget-header bordered no-actions d-flex align-items-center">
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
                    Tambah Data Penghuni Kosan
                </button>
                    </div>
                    <div class="widget-body">
                        <div class="table-responsive">
                            <table id="sorting-table" class="table mb-0">
                                <thead>
                                <tr>
                                        <th>No.</th>
                                        <th>Nama</th>
                                        <th>Whatsapp</th>
                                        <th>KTP</th>
                                        <th>Nomer Kosan</th>
                                        <th>Fasilitas</th>
                                        <th>Harga</th>
                                        <th>Alamat KTP</th>
                                        <th>Tgl Digunakan</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                     $id = 0;
                                     $u = $_SESSION['username'];
                                     $q = mysqli_query($koneksi, "SELECT * FROM kosan WHERE make_by='$u'");
                                    while ($row = mysqli_fetch_assoc($q)) {
                                        $no++;
                                        echo '<tr>';
                                        echo '<td>' . $no . '</td>';
                                        echo '<td>' . $row['nama'] . '</td>';
                                        echo '<td>' . $row['nomor'] . '</td>';
                                        if (substr($row['media'], -3) == "pdf") {
                                            echo '<td><a href="' . $row['media'] . '" target="_blank"><img src="../assets/img/pdf.png" title="' . $row['media'] . '" style="width:40px"></a></td>';
                                        } else {
                                            echo '<td><a href="' . $row['media'] . '" target="_blank"><img src="'. $row['media'] . '" title="' . $row['media'] . '" style="width:40px">' .  '</a></td>';
                                        }
                                        echo '<td>' . $row['nomer_kamar'] . '</td>';
                                        echo '<td>' . $row['jenis_kamar'] . '</td>';
                                        echo '<td>' .'Rp. '.number_format($row['harga'],0,"",".")  . '</td>';
                                        echo '<td>' . utf8_decode($row['alamat']) . '</td>';
                                        echo '<td>' . $row['tgl_pakai'] . '</td>';
                                        echo '<td class="td-actions"><a href="#" data-toggle="modal" data-target="#Edit' . $row['id'] . '"><i class="la la-edit edit"></i></a><a href="save_data_kosan.php?act=hapus&id=' . $row['id'] .'"><i class="la la-close delete"></i></a></td>';
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
        <!-- End of Page Wrapper -->
    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>
       <!-- Modal edit -->
       <?php
       $u = $_SESSION['username'];
        $q = mysqli_query($koneksi, "SELECT * FROM kosan WHERE make_by='$u'");
        while ($row = mysqli_fetch_assoc($q)) { ?>
            <!-- Modal edit -->
            <div class="modal fade" id="Edit<?= $row['id'] ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Ubah data kamar <?= $row['nomer_kamar'] ?></h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form action="" method="POST" enctype="multipart/form-data">
                                <label>Nama</label>
                                <input type="text" name="nama" readonly class="form-control" value="<?=$row['nama']?>">
                                <br>
                                <label>Nomer kamar </label>
                                 <?php
                                $u = $_SESSION['username'];
                                $q = mysqli_query($koneksi, "SELECT * FROM kamar WHERE make_by='$u'");
                                $jsArray4 = "var nmr1 = new Array();\n";
                                echo '<select class="form-control "name="nomer_kamar" onchange="changeValue(this.value)" >';
                                echo '<option>-------</option>';
                                while ($row = mysqli_fetch_array($q)) {
                                    echo '<option value="' . $row['nomer_kamar'] . '">' . $row['jenis_kamar'] . ' (' . $row['nomer_kamar'] . ') status: '.$row['status'].'</option>';
                                    // $jsArray .= "nmr1['" . $row['nomer_kamar'] . "'] = '" . addslashes($row['harga']) . "';\n";
                                    $jsArray4 .= "nmr1['" . $row['nomer_kamar'] . "'] = {jkm1:'" . addslashes($row['jenis_kamar']) . "',hrg1:'".addslashes($row['harga'])."'};\n";
                                }
                                ?>
                            </select>
                        <!-- //menampilkan data berdasarkan pilihan combobox ke dalam form -->
                                <label>Fasilitas/label>
                                <input type="text" name="jenis_kamar" readonly class="form-control" id="jenis_kamar1" value="<?=$row['jenis_kamar']?>">
                                <br>
                                <label>Harga</label>
                                <input type="number" name="harga" readonly  class="form-control" id="harga1" value="<?= $row['harga'] ?>">
                                <br>
                                <label>Alamat KTP </label>
                                <textarea type="text" name="alamat"  class="form-control" placeholder="alamat KTP" ><?= utf8_decode($row['alamat'])?></textarea>
                                <br>
                                <label> Tanggal pakai</label>
                                <input type="date" name="tgl_pakai" class="form-control"  value="<?= $row['tgl_pakai'] ?>">
                                <br>
                            </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" name="update-kosan" class="btn btn-primary">Simpan</button>
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
                    <h5 class="modal-title" id="exampleModalLabel">Tambah Data Kosan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="" method="POST" enctype="multipart/form-data">
                    <label>Nama Lengkap (Sesuai KTP)</label>
                    <input type="text" name="nama" required  class="form-control" >
                    <br>
                    <label>Whatsapp</label>
                                <?php
                                $q = mysqli_query($koneksi, "SELECT * FROM account");
                        $jsArray = "var nm= new Array();\n";
                        echo '<select class="form-control "name="nomor" onchange="document.getElementById(\'nomor\').value = nm[this.value]" >';
                                echo '<option>-pilih nomor whatsapp anda-</option>';
                                while ($row = mysqli_fetch_array($q)) {
                                    echo '<option value="' . $row['whatsapp'] . '">' .$row['whatsapp']  .'('. $row['username'] . ')</option>';
                                    $jsArray .= "nm['" . $row['whatsapp'] . "'] = '" . addslashes($row['username']) . "';\n";
                                    // $jsArray .= "nm['" . $row['nama'] . "'] = {jk:'" . addslashes($row['jenis_kamar']) . "',almt:'".addslashes($row['alamat'])."'};\n";
                                }
                                ?>
                            </select>
                        <br>
                        <label>Nomer kamar </label>
                        <?php
                        $u = $_SESSION['username'];
                        $q = mysqli_query($koneksi, "SELECT * FROM kamar WHERE make_by='$u'");
                        $jsArray2 = "var nmr = new Array();\n";
                        echo '<select class="form-control "name="nomer_kamar" onchange="changeValue(this.value)" >';
                                echo '<option>-------</option>';
                                while ($row = mysqli_fetch_array($q)) {
                                    echo '<option value="' . $row['nomer_kamar'] . '">' . $row['jenis_kamar'] . ' (' . $row['nomer_kamar'] . ') </option>';
                                    // $jsArray .= "nmr['" . $row['nomer_kamar'] . "'] = '" . addslashes($row['harga']) . "';\n";
                                    $jsArray2 .= "nmr['" . $row['nomer_kamar'] . "'] = {jkm:'" . addslashes($row['jenis_kamar']) . "',hrg:'".addslashes($row['harga'])."'};\n";
                                }
                                ?>
                            </select>
                        <!-- //menampilkan data berdasarkan pilihan combobox ke dalam form -->
                        <input type="text" name="jenis_kamar" required class="form-control" id="jenis_kamar" hidden>
                          <label>Harga </label>
                        <input type="number" name="harga" required class="form-control" id="harga" readonly>
                        <input type="text" name="username" required class="form-control" id="nomor" hidden>
                        <br>
                        <label>Foto KTP</label> <em style="color:red;font-size:12px">*Max 1 mb </em>
                        <input type="file" name="media" class="form-control" required>
                        <br>
                        <label>Alamat KTP </label>
                        <textarea type="text" name="alamat" required class="form-control" placeholder="Alamat sesuai KTP" id="nama" ></textarea>
                        <br>
                        <label> Tanggal pakai</label>
                        <input type="date" name="tgl_pakai" class="form-control" required>
                        <br>
                    </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" name="tambah-kosan" class="btn btn-primary">Simpan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="import" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Import Data Kamar</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="../lib/import_excelkosan.php" method="POST" enctype="multipart/form-data">
                        <label> File Excel (.xlsx) </label>
                        <input type="file" name="file" required class="form-control">
                        <br>
                        <label> Mulai dari Baris</label>
                        <input type="text" name="a" required class="form-control" value="4">
                        <br>
                        <label> Kolom Nama </label>
                        <input type="text" name="b" required class="form-control" value="1">
                        <br>
                        <label> Kolom Harga</label>
                        <input type="text" name="c" required class="form-control" value="2">
                        <br>
                        <label> Kolom Alamat KTP </label>
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
            $('#title').html('KOSAN > Saved Data Kamar')
        });
        document.getElementById("costumer-sid").classList.add("active");
    </script>
    <script type="text/javascript">
    <?php
     echo $jsArray4;
     echo $jsArray; 
     echo $jsArray2;
      ?>

    function changeValue(id){
document.getElementById('jenis_kamar').value = nmr[id].jkm;
document.getElementById('harga').value = nmr[id].hrg;
document.getElementById('jenis_kamar1').value = nmr1[id].jkm1;
document.getElementById('harga1').value = nmr1[id].hrg1;
};
    </script>
    </body>

    </html>