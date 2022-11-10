<?php
include_once("../helper/conn.php");
include_once("../helper/function.php");

$username = $_SESSION['username'];
$login = cekSession();
if ($login == 0) {
    redirect("../auth/login.php");
}
if ($_SESSION["level"] != 1) {
    redirect("../users/auto_responder.php");
}


if (get("act") == "hapus") {
    $med = get("media");
    $id = get("id");
    $key = get("keyword");
    $e = strlen($base_url . "uploads/base/");
    $q = mysqli_query($koneksi, "DELETE FROM autoreply WHERE id='$id'");
    $lis = substr($med, $e);
    unlink("../uploads/base/" . $lis);
    swal_set("success", "Berhasil Hapus List Auto Reply dengan keyword " . $key);
    redirect("auto_responder.php");
}

if (post("nomor")) {
    $nomor = post("nomor");
    $keyword = post("keyword");
    $respon = post("response");
    $on = post("btnon");
    $btn1 = post("button1");
    $btn2 = post("button2");
    $btn3 = post("button3");
    if (!empty($_FILES['media']) && $_FILES['media']['error'] == UPLOAD_ERR_OK) {
        // Be sure we're dealing with an upload
        if (is_uploaded_file($_FILES['media']['tmp_name']) === false) {
            throw new \Exception('Error on upload: Invalid file definition');
        }


        if ($size > 1000000) {
            toastr_set("error", "Maximal 1mb");
            redirect("auto_responder.php");
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
            redirect("auto_responder.php");
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
    $cek = mysqli_query($koneksi, "SELECT * FROM autoreply WHERE nomor = '$nomor' AND keyword = '$keyword'");

    if (mysqli_num_rows($cek) > 0) {
        $up = mysqli_query($koneksi, "UPDATE `autoreply` SET `response` = '$respon', `button` = '$on', `dbutton1` = '$btn1', `dbutton2` = '$btn2', `dbutton3` = '$btn3', `media` = '$media' WHERE `autoreply`.`keyword` = '$keyword'");
        swal_set("success", "Berhasil Edit Auto Reply.");
        redirect("auto_responder.php");
    } else {
        $q = mysqli_query($koneksi, "INSERT INTO `autoreply` VALUES ('','$keyword','$respon','$media','$on','$btn1','$btn2','$btn3','$nomor','$u')");
        swal_set("success", "Berhasil Menambahkan List Auto Reply");
        redirect("auto_responder.php");
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
                    <h2 class="page-header-title">Auto Reply</h2>
                </div>
            </div>
        </div>
        <!-- Default -->
        <div style="height: 40px;"></div>
        <div class="row">
            <div class="col-xl-12">
                <!-- Sorting -->
                <div class="widget has-shadow">
                    <div class="widget-header bordered no-actions d-flex align-items-center">
                        <button class="btn btn-primary" data-toggle="modal" data-target="#AddReply"><i class="ion ion-network"></i> Add Reply</button>
        
                    </div>
                    <div class="widget-body">
                        <div class="table-responsive">
                            <table id="sorting-table" class="table mb-0">
                                <thead>
                                    <tr>
                                        <th>Nomor</th>
                                        <th>Keyword</th>
                                        <th>Response</th>
                                        <th>Response Media</th>
                                        <th>Response Button</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $q = mysqli_query($koneksi, "SELECT * FROM autoreply WHERE make_by = '$username'");
                                    while ($row = mysqli_fetch_assoc($q)) {
                                        $id = $row['id'];
                                        $keyword = $row['keyword'];
                                        $response = $row['response'];
                                        $media = $row['media'];
                                        echo '<tr>';
                                        echo '<td>' . $row['nomor'] . '</td>';
                                        echo '<td>' . $row['keyword'] . '</td>';
                                        if (strlen($row['response']) >= 50) {
                                            echo '<td>' . substr($row['response'], 0, 50) . '...' . '</td>';
                                        } else {
                                            echo '<td>' . $row['response'] . '</td>';
                                        }
                                        if (substr($row['media'], -3) == "pdf") {
                                            echo '<td><a href="' . $row['media'] . '" target="_blank"><img src="../assets/img/pdf.png" title="' . $row['media'] . '" style="width:40px"></a></td>';
                                        } else {
                                            echo '<td><a href="' . $row['media'] . '" target="_blank"><img src="' . $row['media'] . '" title="' . $row['media'] . '" style="width:40px">' .  '</a></td>';
                                        }
                                        if ($row['button'] == 0) {
                                            echo '<td>-</td>';
                                        } else if ($row['button'] == 1){
                                            echo '<td>' . $row['dbutton1'] . '</td>';
                                        } else if ($row['button'] == 2){
                                            echo '<td>' . $row['dbutton1'] . ',' . $row['dbutton2'] . '</td>';
                                        } else if ($row['button'] == 3){
                                            echo '<td>' . $row['dbutton1'] . ',' . $row['dbutton2'] . ',' . $row['dbutton2'] . '</td>';
                                        }
                                        echo '<td class="td-actions"><a href="#" data-toggle="modal" data-target="#Edit_' . $row['id'] . '"><i class="la la-edit edit"></i></a><a href="auto_responder.php?act=hapus&id=' . $row['id'] . '&media=' . $row['media'] . '&keyword=' . $row['keyword'] . '"><i class="la la-close delete"></i></a></td>';
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
    <!-- Modal -->
    <div class="modal fade" id="AddReply" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title"><i class="ion ion-network"></i> Add Auto Reply</h4>
                    <button type="button" class="close" data-dismiss="modal">
                        <span aria-hidden="true">×</span>
                        <span class="sr-only">close</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="" method="POST" enctype="multipart/form-data">
                        <label for="">Nomor</label>
                        <select class="form-control" name="nomor" style="width: 100%">
                            <?php
                            $u = $_SESSION['username'];
                            $q = mysqli_query($koneksi, "SELECT * FROM device WHERE pemilik='$u'");
                            while ($row = mysqli_fetch_assoc($q)) {
                                echo '<option value="' . $row['nomor'] . '">' . $row['nomor'] . '</option>';
                            }
                            ?>
                        </select>
                        <label> Keyword </label>
                        <input type="text" name="keyword" required class="form-control">
                        <br>
                        <label> Media </label><em style="color:red;font-size:10px">*support jpg dan pdf. Max 1 Mb</em>
                        <input type="file" name="media" class="form-control">
                        <label> Response </label>
                        <textarea name="response" class="form-control" required></textarea>
                        <br>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal -->
     
    <?php
    $q = mysqli_query($koneksi, "SELECT * FROM autoreply WHERE make_by='$username'");
    while ($row = mysqli_fetch_assoc($q)) { ?>
        <!-- Modal edit -->
        <div class="modal fade" id="Edit_<?= $row['id'] ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel"><i class="ion ion-network"></i> Edit Auto Reply</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="" method="POST" enctype="multipart/form-data">
                            <label for="">Nomor</label>
                            <select class="form-control" name="nomor" style="width: 100%">
                                <?php
                                $u = $_SESSION['username'];
                                $y = mysqli_query($koneksi, "SELECT * FROM device WHERE pemilik='$u'");
                                while ($res = mysqli_fetch_assoc($y)) {
                                    echo '<option value="' . $row['nomor'] . '">' . $res['nomor'] . '</option>';
                                }
                                ?>
                            </select>
                            <label> Keyword </label>
                            <input type="text" readonly name="keyword" value="<?= $row['keyword'] ?>" required class="form-control">
                            <br>
                            <div class="row">
                                <div class="col">
                                    <label> Media </label><em style="color:red;font-size:10px">*support jpg dan pdf. Max 1 Mb</em>
                                </div>
                                <?php if ($row['media'] == '') {
                                    echo '';
                                } else if (substr($row['media'], -3) == "pdf") {
                                    echo '<div class="col-3 text-end"><a href="' . $row['media'] . '" style="font-size:13px;" target="_blank">View Pdf</a></div>';
                                } else {
                                    echo '<div class="col-3 text-end"><a href="' . $row['media'] . '" style="font-size:13px;" target="_blank">View Image</a></div>';
                                } ?>
                            </div>
                            <input type="file" name="media" class="form-control">
                            <br>
                            <label> Response </label>
                            <textarea name="response" class="form-control" cols="30" rows="5" required><?= $row['response'] ?></textarea>
                            <br>
                            <label> Button </label>
                                <select name="btnon" id="btnon" required class="form-control">
                                <?php if ($row['button'] == 0) {
                                    echo '<option value="0">Text Only</option>';
                                } else if ($row['button'] == 1) {
                                    echo '<option value="1">Add 1 Button</option>';
                                } else if ($row['button'] == 2) {
                                    echo '<option value="2">Add 2 Button</option>';
                                } else if ($row['button'] == 3) {
                                    echo '<option value="3">Add 3 Button</option>';
                                }  ?>
                                    <option value="0">Text Only</option>
                                    <option value="1">Add 1 Button</option>
                                    <option value="2">Add 2 Button</option>
                                    <option value="3">Add 3 Button</option>
                                </select>
                                <br>
                                <label> Button 1</label>
                                <input type="text" name="button1" value="<?= $row['dbutton1'] ?>" class="form-control">
                                <br>
                                <label> Button 2</label>
                                <input type="text" name="button2" value="<?= $row['dbutton2'] ?>" class="form-control">
                                <br>
                                <label> Button 3</label>
                                <input type="text" name="button3" value="<?= $row['dbutton3'] ?>" class="form-control">
                                <br>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!--Close Modal edit -->
    <?php  }
    ?>
    <div style="height: 40px;"></div>
    <?php
    include_once('../templates/footer.php')
    ?>
</div>
<!-- Bootstrap core JavaScript-->
<script src="<?= $base_url; ?>vendor/jquery/jquery.min.js"></script>
<script src="<?= $base_url; ?>vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- Core plugin JavaScript-->
<script src="<?= $base_url; ?>vendor/jquery-easing/jquery.easing.min.js"></script>
<script src="<?= $base_url; ?>assets/vendors/js/app/app.min.js"></script>
<script src="<?= $base_url; ?>assets/vendors/js/datatables/datatables.min.js"></script>
 <script src="<?= $base_url; ?>assets/vendors/js/datatables/tables.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js" integrity="sha512-VEd+nq25CkR676O+pLBnDW09R7VQX9Mdiij052gVCp5yVH3jGtH70Ho/UUv4mJDsEdTvqRCFZg0NKGiojGnUCw==" crossorigin="anonymous"></script>
<script>
    <?php

    toastr_show();
    swal_show();

    ?>
    $(document).ready(function() {
        $('#title').html('BLASTJET > Auto Reply')
    });
    document.getElementById("reply-sid").classList.add("active");
</script>
</body>

</html>