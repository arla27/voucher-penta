<div class="col">
    <!-- Sizing -->
    <div class="widget has-shadow">
        <div class="widget-header bordered no-actions d-flex align-items-center">
            <h4>Set Webhook</h4>
        </div>

        <div class="widget-body"> <?php
                                    $username = $_SESSION['username'];
                                    $q = mysqli_query($koneksi, "SELECT * FROM device WHERE pemilik = '$username'");
                                    while ($row = mysqli_fetch_assoc($q)) { ?>
                <form action="" method="post">
                    <input type="text" name="idnomor" hidden value="<?= $row['nomor']; ?>">
                    <label>URL Webhook</label>
                    <div class="input-group">
                        <input type="text" value="<?= getSingleValDB("device", "pemilik", "$username", "link_webhook") ?>" class="form-control" name="urlwebhook">
                        <button class="btn-gradient-01" type="submit">Set Webhook</button>
                    </div>
                    <div class="progress progress-sm mb-3">
                        <div class="progress-bar bg-primary" role="progressbar" style="width: 100%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </form>
            <?php } ?>
        </div>
    </div>
</div>
<!-- End Sizing -->