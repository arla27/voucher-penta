   <?php 
        $u = $_SESSION['username'];
        $now = strtotime(date("Y-m-d"));
        $q = mysqli_query($koneksi,"SELECT * FROM `account` WHERE `username`='$u'");
while ($data = mysqli_fetch_assoc($q)) { 
    $user_date = strtotime($data['date_pro']);
    if($now === $user_date){
        echo '<div class="alert alert-warning alert-dissmissible fade show" role="alert">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close"></button>
    <ul class="mt-2">
        <li>Halo, <b>'.$data['username'].'.</b></li>
        <li>Kami Informasikan Bahwa Masa Aktif Akun Anda Akan Segera Berakhir. Silahkan Untuk Perpanjang Masa Aktif Akun Anda</li>
        <li><br>Selengkapnya</li>
    </ul>
</div>';
        
    }
}
    