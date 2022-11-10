<?php
include_once("../helper/conn.php");
include_once("../helper/function.php");

$login = cekSession();
if ($login == 0) {
    redirect("login.php");
}
if ($_SESSION["level"] != 1) {
    redirect("../users/doc_api.php");
}

if (post("callback")) {
    $callback = post("callback");
    mysqli_query($koneksi, "UPDATE pengaturan SET callback = '$callback' WHERE id='1'");
    toastr_set("success", "Sukses edit callback");
}

if (get("act") == "cn") {
    mysqli_query($koneksi, "UPDATE pengaturan SET callback = NULL WHERE id='1'");
    toastr_set("success", "Sukses menonaktifkan callback");
    redirect("doc_api.php");
}
$username = $_SESSION['username'];

require_once('../templates/header.php');
require_once('../settings/doc_api.php');
include_once('../templates/footer.php')
?>
<!-- Bootstrap core JavaScript-->
<script src="<?= $base_url; ?>vendor/jquery/jquery.min.js"></script>
<script src="<?= $base_url; ?>vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

<!-- Core plugin JavaScript-->
<script src="<?= $base_url; ?>vendor/jquery-easing/jquery.easing.min.js"></script>
<script src="<?= $base_url; ?>assets/vendors/js/app/app.min.js"></script>
<!-- Page level plugins -->
<script src="<?= $base_url; ?>vendor/chart.js/Chart.min.js"></script>
<script src="<?= $base_url; ?>assets/vendors/prism.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js" integrity="sha512-VEd+nq25CkR676O+pLBnDW09R7VQX9Mdiij052gVCp5yVH3jGtH70Ho/UUv4mJDsEdTvqRCFZg0NKGiojGnUCw==" crossorigin="anonymous"></script>
<script>
    <?php

    toastr_show();

    ?>
    $(document).ready(function() {
        $('#title').html('BLASTJET > Doc API')
    });
    document.getElementById("rest-sid").classList.add("active");
</script>

</body>

</html>