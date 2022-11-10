<div class="col-xl-6 mb-4">
    <!-- Sizing -->
    <div class="widget has-shadow">
        <div class="widget-header">

            <div class="scanqr-api">

            </div>
            <div class="card-header">
                <div class="icon-api">
                    <i class="la ion-social-whatsapp"></i>
                </div>
            </div>
        </div> <?php
                $username = $_SESSION['username'];

                ?>
        <form action="" method="post">
            <div class="widget-body  text-center">
                <div class="progress progress-sm mb-3">
                    <div class="progress-bar bg-info" role="progressbar" style="width: 50%" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
                <label>Your Api Key</label>
                <input type="text" class="form-control text-center" id="apiKey" onclick="copyText()" style="cursor: pointer;" name="apikey" readonly value="<?= getSingleValDB("account", "username", "$username", "api_key") ?>">
                <div class="mt-5">
                    <button class="btn btn-primary"> Generate Api Key </button>
                </div>
            </div>
        </form>
    </div>
    <!-- End Sizing -->
</div>
<div class="col">
    <!-- Begin Widget -->
    <div class="widget has-shadow">
        <div class="widget-body">
            <form action="" method="post">
                <label> Batas Pengiriman per menit </label>
                <input type="text" class="form-control" name="chunk" value="<?= getSingleValDB("account", "username", "$username", "chunk") ?>">
                <br>
                <div class="text-center">
                    <button class="btn btn-gradient-01"> Simpan </button>
                </div>
            </form>
        </div>
    </div>
    <!-- End Widget -->
</div>
<script>
    function copyText() {
        /* Get the text field */
        var copyText = document.getElementById("apiKey");

        /* Select the text field */
        copyText.select();

        /* Copy the text inside the text field */
        document.execCommand("copy");

        /* Alert the copied text */
        toastr["success"]("Api Key Berhasil di Salin: " + copyText.value);
    }
</script>