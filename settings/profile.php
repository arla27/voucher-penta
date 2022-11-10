<div class="col-xl-9">
    <!-- Sizing -->
    <div class="widget has-shadow">
        <div class="widget-header bordered no-actions d-flex align-items-center">
            <h4>Profile</h4>
        </div><?php
                $username = $_SESSION['username'];
                $q = mysqli_query($koneksi, "SELECT * FROM account WHERE username = '$username'");
                while ($row = mysqli_fetch_assoc($q)) { ?>
            <div class="widget-body">
                <div class="container">
                    <div class="row">
                        <form action="" method="post" enctype="multipart/form-data">
                            <div class="card poto text-center">
                                <label class="cabinet center-block" style="width: 210px;height:205px;">
                                    <figure>
                                        <img src="<?= $row['photo']; ?>" alt="" style="width: 200px;height:200px;margin-top:4px">
                                        <figcaption class="card-foto"><i class="la la-camera"></i></figcaption>
                                    </figure>
                                    <input type="file" name="upload_image" id="upload_image" class="item-img file center-block" />
                                </label>
                                <div id="uploaded_image"></div>
                            </div>
                        </form>

                        <div class="col">
                            <div class="card">
                                <div class="container mt-2 mb-2">
                                    <div class="form-group row d-flex align-items-center mb-2">
                                        <label class="col-lg-3 form-control-label">Username</label>
                                        <div class="col-lg-9">
                                            <input type="text" readonly class="form-control" value="<?= $row['username']; ?>">
                                        </div>
                                    </div>
                                    <div class="form-group row d-flex align-items-center mb-2">
                                        <label class="col-lg-3 form-control-label">Whatsapp</label>
                                        <div class="col-lg-9">
                                            <input type="text" readonly class="form-control" value="<?= $row['whatsapp']; ?>">
                                        </div>
                                    </div>
                                    <div class="form-group row d-flex align-items-center mb-2">
                                        <label class="col-lg-3 form-control-label">Masa Aktif</label>
                                        <div class="col-lg-9">
                                            <input type="text" readonly class="form-control" value="<?= $row['date_pro']; ?>">
                                        </div>
                                    </div>
                                <?php }
                            $q = mysqli_query($koneksi, "SELECT * FROM device WHERE pemilik = '$username'");
                            while ($row = mysqli_fetch_assoc($q)) {
                                ?>
                                    <div class="form-group row d-flex align-items-center mb-2">
                                        <label class="col-lg-3 form-control-label">WA Terkoneksi</label>
                                        <div class="col-lg-9">
                                            <input type="text" readonly class="form-control" value="<?= $row['nomor']; ?>">
                                        </div>
                                    </div><?php }  ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </div>
</div>