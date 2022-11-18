<?php
include_once("../helper/conn.php");
include_once("../helper/function.php");
$login = cekSession();
if ($login == 0) {
    redirect("../auth/login.php");
}
if ($_SESSION["level"] != 2) {
    redirect("../admin/dashboard.php");
}



require_once('../templates/header.php');
?>

<div class="content-inner">
    <div class="container-fluid">
        <!-- Begin Page Header-->
        <div class="row">
            <div class="page-header">
                <div class="d-flex align-items-center">
                    <h2 class="page-header-title">Penerima Voucher</h2>
                    <div>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="dashboard.html"><i class="ti ti-home"></i></a></li>
                            <li class="breadcrumb-item active">Penerima Voucher</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Page Header -->
        <div class="mb-4"></div>
        <div class="row">
            <div class="col-xl-12">
                <!-- Sorting -->
                <div class="widget has-shadow">
                    <div class="widget-header bordered no-actions d-flex align-items-center">
                        <div class="col"> Data penerima Voucher</div>
                        <!-- <a class="btn btn-danger text-end" href="broadcast.php?del=all"><i class="ion-trash-b"></i>(Terkirim)</a> -->
                    </div>
                    <div class="widget-body">
                        <div class="table-responsive">
                            <table id="sorting-table" class="table mb-0">
                                <thead>
                                    <tr>
                                        <th>NIK</th>
                                        <th>NAMA</th>
                                        <th>Alamat</th>
                                        <th>No. Tlp</th>
                                        <th>Email</th>
                                        <th>Cabang</th>
                                        <!-- <th>Voucher</th> -->
                                        <th>Tgl Terima</th>
                                        <!-- <th>Action</th> -->
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php

                                    $username = $_SESSION['username'];
                                    $q = mysqli_query($koneksi, "SELECT * FROM user WHERE make_by='$username' ORDER BY tgl_pakai DESC");
                                    while ($row = mysqli_fetch_assoc($q)) {
                                        echo '<tr>';
                                        echo '<td>' . $row['nik'] . '</td>';
                                        echo '<td>' . $row['nama'] . '</td>';
                                        echo '<td>' . $row['alamat'] . '</td>';
                                        echo '<td>' . $row['no_tlp'] . '</td>';
                                        echo '<td>' . $row['email'] . '</td>';
                                        echo '<td>' . $row['cabang'] . '</td>';
                                        // echo '<td>' . $row['kode'] . '</td>';
                                        echo '<td>' . $row['tgl_pakai'] . '</td>';

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
                                <div class="number"><?= countDB("kode_voucher", "stats", "not used") ?> Voucher</div>
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
        <!-- End Row -->
        <div style="height: 200px;">
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
            $('#title').html('PENTA PRIMA> Penerima Voucher')
        });
        document.getElementById("message-sid").classList.add("active");
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js" integrity="sha512-qTXRIMyZIFb8iQcfjXWCO8+M5Tbc38Qi5WzdPOYZHIlZpzBHG3L3by84BBBOiRGiEb7KKtAOAs5qYdUiZiQNNQ==" crossorigin="anonymous"></script>
    </body>

    </html>