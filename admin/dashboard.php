<?php
include_once("../helper/conn.php");
include_once("../helper/function.php");


$login = cekSession();
if ($login == 0) {
    redirect("../auth/login.php");
}
if ($_SESSION["level"] != 1) {
    redirect("../users/dashboard.php");
}

function generate_password($len = 8)
{
    $chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%&";
    $password = substr(str_shuffle($chars), 0, $len);
    return $password;
}

//Add User
$by = $_SESSION['username'];
if (post("uname")) {
    $by = $_SESSION['username'];
    $username = post("uname");
    $whatsapp = post("wa");
    $role = post("lvl");
    $expired = post("exp");
    $password = "PENTA";
    // $password = generate_password();
    $encpt_password = sha1($password);
    $defaultphoto = "../img/profiles1.png";
    $api_key = sha1(date("Y-m-d H:i:s") . rand(100000, 999999));
    $cek_whatsapp = mysqli_query($koneksi, "SELECT * FROM account WHERE whatsapp = '$whatsapp'");
    $cek = mysqli_query($koneksi, "SELECT * FROM account WHERE username = '$username'");
    if ($role == 1) {
        $rules = 'admin';
    } else {
        $rules = 'user';
    };
    $isipesan = "Nomor Whatsapp Anda telah didaftarkan layanan BlastJET oleh role Admin (```" . $by . "```) dengan data akses berikut:\nUsername : " . $username . "\nPassword : " . $password . "\nRole : " . $rules . "\nExpired : " . $expired . "\nsilahkan login di https://blastjet.karyaped.com\nUntuk menjaga keamanan, segera ganti password tersebut.\n\nBest regards,\nBlastJET";
    if ($cek->num_rows > 0) {
        swal_set("error", "Username Sudah dipakai");
         redirect('dashboard.php');
    } 

    else {
        $q = mysqli_query($koneksi, "INSERT INTO `account` (`id`, `username`, `password`, `api_key`, `level`, `chunk`, `photo`, `whatsapp`, `aktif`, `token`,`date_pro`) VALUES ('','$username','$encpt_password','$api_key',$role,'10','$defaultphoto','$whatsapp','1','ADDED','$expired')");
    if ($q) {
        sendMSG($whatsapp, $isipesan, "6285162830081");
        swal_set("success", "Berhasil menambahkan user");
        redirect('dashboard.php');
    }
    }
}

// Update User

if (post("username")) {
    $username = post("username");
    $whatsapp = post("whatsapp");
    $role = post("role");
    $expired = post("exp");
    $status = post("status");
    if ($expired == null) {
        $update = mysqli_query($koneksi, "UPDATE `account` SET `username` = '$username', `whatsapp` = '$whatsapp',`level` = '$role', `aktif`='$status' WHERE `account`.`username`='$username'");
        swal_set("success", "Berhasil edit user");
        redirect('dashboard.php');
    } else if ($username) {
        $update = mysqli_query($koneksi, "UPDATE `account` SET `username` = '$username', `whatsapp` = '$whatsapp', `level` = '$role', `aktif`='$status', `date_pro`='$expired' WHERE `account`.`username`='$username'");
        swal_set("success", "Berhasil edit user");
        redirect('dashboard.php');
    } else {
        swal_set("error", "Gagal edit user");
        redirect('dashboard.php');
    }
}

// update expired
if (post("user")) {
    $username = post("user");
    $cek = mysqli_query($koneksi, "SELECT * FROM account WHERE username = '$username'");
    $row = mysqli_fetch_assoc($cek);
    $whatsapp = $row['whatsapp'];
    $expired = post("exp");
    $isipesan = "Status Aktif dan Masa berlaku akun kamu telah ditambahkan menjadi ".$expired;
   if ($expired) {
        $update = mysqli_query($koneksi, "UPDATE `account` SET `date_pro`='$expired',`aktif` = '1' WHERE `account`.`username`='$username'");
sendMSG($whatsapp, $isipesan, "6285162830081");
        swal_set("success", "Berhasil menambahkan masa aktif user ke ".$expired);
        redirect('dashboard.php');
    } else {
        swal_set("error", "Gagal edit user");
        redirect('dashboard.php');
    }
}

// Forgot Password

if (post("forgot")) {
    $username = post("forgot");
    $password = generate_password();
    $encpt_password = sha1($password);
    $cek = mysqli_query($koneksi, "SELECT * FROM account WHERE username = '$username'");
    $row = mysqli_fetch_assoc($cek);
    $whatsapp = $row['whatsapp'];
    $isipesan = "Admin (```" . $by . "```) telah mereset password Anda menjadi berikut;\nUsername : " . $username . "\nPassword baru : " . $password . "\n\nSilahkan untuk segera ganti password Anda.";
    if ($whatsapp) {
        $getpass = mysqli_query($koneksi, "UPDATE `account` SET `password` = '$encpt_password' WHERE `account`.`username`='$username'");
       sendMSG($whatsapp, $isipesan, "6285162830081");
        swal_set("success", "Berhasil kirim Password baru ke user " . $username);
        redirect('dashboard.php');
    } else {
        swal_set("error", "Gagal kirim Password");
        redirect('dashboard.php');
    }
}


// Hapus User
if (get("act") == "hapus") {
    $id = get("id");
    $user = get("user");
    $a = mysqli_query($koneksi, "DELETE FROM account WHERE id='$id'");
    if ($a) {
    $b = mysqli_query($koneksi, "DELETE FROM autoreply WHERE make_by='$user'");
    $c = mysqli_query($koneksi, "DELETE FROM contacts WHERE make_by='$user'");
    $d = mysqli_query($koneksi, "DELETE FROM device WHERE pemilik='$user'");
    $e = mysqli_query($koneksi, "DELETE FROM nomor WHERE make_by='$user'");
    $f = mysqli_query($koneksi, "DELETE FROM pesan WHERE make_by='$user'");
        swal_set("success", "Berhasil Hapus User '.$user.'");
        redirect('dashboard.php');
        $file = '../whatsapp-session-' . $nomor . '.json';
    $cekfile = file_exists($file);

    if ($cekfile == true){
        unlink($file);
    }else 
        swal_set("error", "terjadi kesalahan");   
    }else {
        swal_set("error", "terjadi kesalahan");   
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
                    <h2 class="page-header-title">Dashboard</h2>
                </div>
            </div>
        </div>
        <!-- Close Page Header-->
               <?php
         $username = $_SESSION['username'];
         $tgl_sekarang = date("Y-m-d");
        $qu = mysqli_query($koneksi, "SELECT * FROM account WHERE `username`='$username'");
        while ($dat = mysqli_fetch_assoc($qu)) { 
        $expired=$dat['date_pro']; 
        if($tgl_sekarang==$expired){
            echo '<div class="alert alert-warning alert-dissmissible fade show" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"></button>Hi! '. $_SESSION['username'] .', Harap segera perpanjang masa aktif langganan kamu, sebelum akun dinonaktifkan.         
        </div>';
        }
        }
        ?>
        <!-- Begin Row -->
        <div class="row flex-row">
            <!-- Begin Facebook -->
            <div class="col-xl-4 col-md-6 col-sm-6">
                <div class="widget widget-12 has-shadow">
                    <div class="widget-body">
                        <div class="media">
                            <div class="align-self-center ml-5 mr-5">
                                <i class="ion-person-stalker text-facebook"></i>
                            </div>
                            <div class="media-body align-self-center">
                                <div class="title text-facebook">Account Terdaftar</div>
                                <div class="number"><?= countUser("username") ?> Admin</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End Facebook -->

        </div>
        <!-- End Row -->
        <div class="row">
            <div class="col-xl-12">
                <!-- Sorting -->
                <div class="widget has-shadow">
                    <div class="widget-header bordered no-actions d-flex align-items-center">
                        <div class="col">Admin Terdaftar</div>
                        <!-- <div class="btn btn-primary mr-1" data-toggle="modal" data-target="#Forgot">
                            <i class="ion-locked" aria-hidden="true"></i> Forgot Password
                        </div> -->
                        <div class="btn btn-success mr-1" data-toggle="modal" data-target="#expired">
                            <i class="ion-clock" aria-hidden="true"></i> Expired Users
                        </div>
                        <div class="btn btn-success" data-toggle="modal" data-target="#addUser">
                            <i class="ion-person-add" aria-hidden="true"></i> Add Admin
                        </div>
                    </div>
                    <div class="widget-body">
                        <div class="table-responsive">
                            <table id="sorting-table" class="table mb-0">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Username</th>
                                        <th>Whatsapp</th>
                                        <th>Role</th>
                                        <th>Status</th>
                                        <th>Expired</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $iduser = 0;
                                    $q = mysqli_query($koneksi, "SELECT * FROM account");
                                    while ($row = mysqli_fetch_assoc($q)) {
                                        $iduser++;
                                        echo '<tr>';
                                        echo '<td>' . $iduser . '</td>';
                                        echo '<td>' . $row['username'] . '</td>';
                                        echo '<td>' . $row['whatsapp'] . '</td>';
                                        if ($row['level'] == 1) {
                                            echo '<td><span class="badge badge-info status-container-' . $row['id'] . '">Admin</span></td>';
                                        } else {
                                            echo '<td><span class="badge badge-warning status-container-' .$row['id'] . '">Cabang</span></td>';
                                        }
                                        if ($row['aktif'] == 1) {
                                            echo '<td><span class="badge badge-success status-container-' . $row['id'] . '">Aktif</span></td>';
                                        } else {
                                            echo '<td><span class="badge badge-danger status-container-' . $row['id'] . '">Nonaktif</span></td>';
                                        }
                                        echo '<td>' . $row['date_pro'] . '</td>';
                                        echo '<td><div class="btn btn-primary"  data-toggle="modal" data-target="#Edit' . $row['username'] . '">
                                        <span class="la la-edit"><span></div> 
                                        <a class="btn btn-danger" href="?act=hapus&id=' . $row['id'] . '&user=' . $row['username'] . '");"><span class="la la-trash"></span></a></td>';
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
                <!-- Modal add -->
        <div class="modal fade" id="addUser" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Add Admin</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="" method="POST">
                            <label>Username </label>
                            <input type="text" name="uname" class="form-control" placeholder="Username" required>
                            <br>
                            <label>Whatsapp </label>
                            <input type="text" name="wa" class="form-control" placeholder="No Whatsapp">
                            <br>
                            <label>Role </label>
                            <select class="form-control" name="lvl" required>
                                <option value="" disabled selected>Select Role User</option>
                                <option value="1">Admin</option>
                                <option value="2">Cabang</option>
                            </select>
                            <br>
                            <label>Expired </label>
                            <input type="date" name="exp" class="form-control" required>
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
        <!--Close Modal add -->
        <?php
        $q = mysqli_query($koneksi, "SELECT * FROM account");
        while ($row = mysqli_fetch_assoc($q)) { ?>
            <!-- Modal edit -->
            <div class="modal fade" id="Edit<?= $row['username'] ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Edit User <?= $row['username']; ?></h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form action="" method="POST">
                                <label>Username </label>
                                <input type="text" name="username" readonly class="form-control" value="<?= $row['username'] ?>">
                                <br>
                                <label>Whatsapp </label>
                                <input type="text" name="whatsapp" class="form-control" value="<?= $row['whatsapp'] ?>">
                                <br>
                                <label>Role </label>
                                <select class="form-control" name="role" required>
                                    <option value="" disabled selected><?php if ($row['level'] == 1) {
                                                                            echo 'Admin';
                                                                        } else {
                                                                            echo 'Cabang';
                                                                        } ?></option>
                                    <option value="1">Admin</option>
                                    <option value="2">Cabang</option>
                                </select>
                                <br>
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
                                <br>
                                <label>Expired </label> : <span style="font-size:12px;color:red"><?= $row['date_pro'] ?></span>
                                <input type="date" name="exp" class="form-control">
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
        <!-- Modal Forgot -->
        <div class="modal fade" id="Forgot" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Forgot Password <?= $row['username'] ?></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="" method="POST">
                            <label>Select Username</label>
                            <select class="form-control" name="forgot">
                                <?php
                                $q = mysqli_query($koneksi, "SELECT * FROM account");

                                while ($row = mysqli_fetch_assoc($q)) {
                                    echo '<option value="' . $row['username'] . '">' . $row['username'] . '</option>';
                                }
                                ?>
                            </select>
                            <br>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Send Password</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!--Close Modal Forgot -->
        <!-- Modal exp -->
        <div class="modal fade" id="expired" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Update Expired</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="" method="POST">
                            <label>Select Username</label>
                            <select class="form-control" name="user">
                                <?php
                                $q = mysqli_query($koneksi, "SELECT * FROM account");

                                while ($row = mysqli_fetch_assoc($q)) {
                                    echo '<option value="' . $row['username'] . '">' . $row['username'] . '</option>';
                                }?>
                            </select>
                            <br>
                              <input name="exp" class="form-control" type="date">
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
        <!--Close Modal exp -->
    </div>
    <?php
    include_once('../templates/footer.php')
    ?>

</div>
<!-- Bootstrap core JavaScript-->
<script src="<?= $base_url; ?>vendor/jquery/jquery.min.js"></script>
<script src="<?= $base_url; ?>vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- Core plugin JavaScript-->
<script src="<?= $base_url; ?>vendor/jquery-easing/jquery.easing.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.1.5/dist/sweetalert2.all.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js" integrity="sha512-VEd+nq25CkR676O+pLBnDW09R7VQX9Mdiij052gVCp5yVH3jGtH70Ho/UUv4mJDsEdTvqRCFZg0NKGiojGnUCw==" crossorigin="anonymous"></script>
<script src="<?= $base_url; ?>assets/vendors/js/app/app.min.js"></script>
<script src="<?= $base_url; ?>assets/vendors/js/datatables/datatables.min.js"></script>
 <script src="<?= $base_url; ?>assets/vendors/js/datatables/tables.js"></script>
<script>
    <?php

    swal_show();

    ?>
    $(document).ready(function() {
        $('#title').html('PENTA PRIMA > Dashboard')
    });
    document.getElementById("dashboard-sid").classList.add("active");
</script>

</body>

</html>