<?php
include_once("conn.php");
session_start();

function get($param)
{
    global $koneksi;
    $d = isset($_GET[$param]) ? $_GET[$param] : null;
    $d = mysqli_real_escape_string($koneksi, $d);
    $d = filter_var($d, FILTER_SANITIZE_STRING);
    return $d;
}

function post($param)
{
    global $koneksi;
    $d = isset($_POST[$param]) ? $_POST[$param] : null;
    $d = mysqli_real_escape_string($koneksi, $d);
    $d = filter_var($d, FILTER_SANITIZE_STRING);
    return $d;
}

function login($u, $p)
{
    global $koneksi;
    $p = sha1($p);
    $q = mysqli_query($koneksi, "SELECT * FROM account WHERE username='$u' AND password='$p' AND aktif='1'");


    if (mysqli_num_rows($q)) {
        $_SESSION['login'] = true;
        $_SESSION['username'] = $u;
        $_SESSION['level'] = getSingleValDB("account", "username", $u, "level");
        return true;
    } else {
        return false;
    }
}

function cekSession()
{
    $login = isset($_SESSION['login']) ? $_SESSION['login'] : null;
    if ($login == true) {
        return 1;
    } else {
        return 0;
    }
}

function getSingleValDB($table, $where, $param, $target)
{
    global $koneksi;
    $q = mysqli_query($koneksi, "SELECT * FROM `$table` WHERE `$where`='$param'");
    $row = mysqli_fetch_assoc($q);
    return $row[$target];
}
function countNo($table, $where = null, $param = null, $and = null, $param2 = null)
{
    global $koneksi;
    if ($where == null && $param == null && $param2 == null && $and == null) {
        $q = mysqli_query($koneksi, "SELECT * FROM `$table`");
    } else {
        $q = mysqli_query($koneksi, "SELECT * FROM `$table` WHERE `$where`='$param' AND `$and`='$param2'");
    }
    $row = mysqli_num_rows($q);
    return $row;
}

function countDB($table, $where = null, $param = null)
{
    global $koneksi;
    if ($where == null && $param == null) {
        $q = mysqli_query($koneksi, "SELECT * FROM `$table`");
    } else {
        $q = mysqli_query($koneksi, "SELECT * FROM `$table` WHERE `$where`='$param'");
    }
    $row = mysqli_num_rows($q);
    return $row;
}

function countDD($table, $param = null)
{
    global $koneksi;
    if ($param == null) {
        $q = mysqli_query($koneksi, "SELECT * FROM `$table`");
    } else {
        $q = mysqli_query($koneksi, "SELECT * FROM `$table` ");
    }
    $row = mysqli_num_rows($q);
    return $row;
}

function countUS($table, $where, $param, $and = null, $param2 = null)
{
    global $koneksi;
    if ($and == null && $param2 == null) {
        $q = mysqli_query($koneksi, "SELECT * FROM `$table` WHERE `$where`='$param'");
    } else {
        $q = mysqli_query($koneksi, "SELECT * FROM `$table` WHERE `$where`='$param' AND `$and`='$param2'");
    }
    $row = mysqli_num_rows($q);
    return $row;
}

function countUser($where = null, $param = null)
{
    global $koneksi;
    if ($where == null && $param == null) {
        $q = mysqli_query($koneksi, "SELECT * FROM `account`");
    } else {
        $q = mysqli_query($koneksi, "SELECT * FROM `account`");
    }
    $row = mysqli_num_rows($q);
    return $row;
}

function countDev($where = null, $param = null)
{
    global $koneksi;
    if ($where == null && $param == null) {
        $q = mysqli_query($koneksi, "SELECT * FROM `device`");
    } else {
        $q = mysqli_query($koneksi, "SELECT * FROM `device`");
    }
    $row = mysqli_num_rows($q);
    return $row;
}

function countPresentase()
{
    $username = "admin";
    $a = countUS("kode_voucher", "stats", "used", "make_by", $username);
    $b = countDB("kode_voucher", "make_by", $username);
    if ($a > 0) {
        return (countUS("kode_voucher", "stats", "used", "make_by", $username) / $b) * 100;
    } else {
        return 0;
    }
}

// function getAllNumber()
// {
//     global $koneksi;
//     $q = mysqli_query($koneksi, "SELECT * FROM `nomor`");
//     $arr = [];

//     while ($row = mysqli_fetch_assoc($q)) {
//         array_push($arr, $row['nomor']);
//         array_push($arr, $row['nama']);
//     }
//     return $arr;
// }
function getAllNumberandmessage()
{
    global $koneksi;
    $q = mysqli_query($koneksi, "SELECT * FROM `nomor`");
    $arr = [];
    while ($row = mysqli_fetch_assoc($q)) {
        array_push($arr, $row);
    }
    return $arr;
}

function getLastID($table)
{
    global $koneksi;
    $q = mysqli_query($koneksi, "SELECT * FROM `$table` ORDER BY id DESC LIMIT 1");
    $row = mysqli_fetch_assoc($q);
    return $row['id'];
}

function url_wa()
{
    global $base_url;
    if ($_SERVER['HTTP_HOST'] == 'localhost'){
        return 'http://localhost:3000/';
    } else {
        return $base_url;
    }
}

function api_key($username)
{
    return getSingleValDB("account", "username", "$username", "api_key");
}

function redirect($target)
{
    echo '
    <script>
    window.location = "' . $target . '";
    </script>
    ';
    exit;
}

function toastr_set($status, $msg)
{
    $_SESSION['toastr'] = true;
    $_SESSION['toastr_status'] = $status;
    $_SESSION['toastr_msg'] = $msg;
}

function swal_set($status, $msg)
{
    $_SESSION['swal'] = true;
    $_SESSION['swal_status'] = $status;
    $_SESSION['swal_msg'] = $msg;
}

function notify_set($status, $msg)
{
    $_SESSION['notify'] = true;
    $_SESSION['notify_status'] = $status;
    $_SESSION['notify_msg'] = $msg;
}
function notify_show()
{
    $t = isset($_SESSION['notify']) ? $_SESSION['notify'] : null;
    $t_s = isset($_SESSION['notify_status']) ? $_SESSION['notify_status'] : null;
    $t_m = isset($_SESSION['notify_msg']) ? $_SESSION['notify_msg'] : null;
    if ($t == true) {
        if ($t_s == "success") {
            echo "
           const Toast = Swal.mixin({
  toast: true,
  position: 'top-end',
  showConfirmButton: false,
  timer: 3000,
  timerProgressBar: true,
  didOpen: (toast) => {
    toast.addEventListener('mouseenter', Swal.stopTimer)
    toast.addEventListener('mouseleave', Swal.resumeTimer)
  }
})

Toast.fire({
  icon: 'success',
  title: '" . $t_m . "'
});
            ";
        }

        if ($t_s == "error") {
            echo "
           const Toast = Swal.mixin({
  toast: true,
  position: 'top-end',
  showConfirmButton: false,
  timer: 3000,
  timerProgressBar: true,
  didOpen: (toast) => {
    toast.addEventListener('mouseenter', Swal.stopTimer)
    toast.addEventListener('mouseleave', Swal.resumeTimer)
  }
})

Toast.fire({
  icon: 'error',
  title: '" . $t_m . "'
});
            ";
        }

        unset($_SESSION['notify']);
        unset($_SESSION['notify_status']);
        unset($_SESSION['notify_msg']);
    }
}
function toastr_show()
{
    $t = isset($_SESSION['toastr']) ? $_SESSION['toastr'] : null;
    $t_s = isset($_SESSION['toastr_status']) ? $_SESSION['toastr_status'] : null;
    $t_m = isset($_SESSION['toastr_msg']) ? $_SESSION['toastr_msg'] : null;
    if ($t == true) {
        if ($t_s == "success") {
            echo "
            toastr.success('Sukses', '" . $t_m . "');
            ";
        }

        if ($t_s == "error") {
            echo "
            toastr.error('Error!', '" . $t_m . "');
            ";
        }

        unset($_SESSION['toastr']);
        unset($_SESSION['toastr_status']);
        unset($_SESSION['toastr_msg']);
    }
}
function swal_show()
{
    $t = isset($_SESSION['swal']) ? $_SESSION['swal'] : null;
    $t_s = isset($_SESSION['swal_status']) ? $_SESSION['swal_status'] : null;
    $t_m = isset($_SESSION['swal_msg']) ? $_SESSION['swal_msg'] : null;
    if ($t == true) {
        if ($t_s == "success") {
            echo "
             Swal.fire({
  icon: 'success',
  title: 'Success',
  text:'" . $t_m . "',
  showConfirmButton: false,
  timer: 2000
});
            ";
        }

        if ($t_s == "error") {
            echo "
           Swal.fire({
  icon: 'error',
  title: 'Error!',
  text:'" . $t_m . "',
  showConfirmButton: false,
  timer: 2000
});
            ";
        }

        unset($_SESSION['swal']);
        unset($_SESSION['swal_status']);
        unset($_SESSION['swal_msg']);
    }
}


function setHook($param, $param2)
{
    global $koneksi;
    $q = mysqli_query($koneksi, "UPDATE device SET link_webhook='$param' WHERE nomor='$param2'");
}
function cekSender($param)
{
    global $koneksi;
    $q = mysqli_query($koneksi, "SELECT * FROM account WHERE api_key='$param'");
    $row1 = mysqli_fetch_assoc($q);
    $cek1 = $row1['username'];
    $x = mysqli_query($koneksi, "SELECT * FROM device  WHERE pemilik='$cek1'");
    $row2 = mysqli_fetch_assoc($x);
    return $row2['nomor'];
}

function sendMSG($number, $msg, $sender)
{
    $url = "http://localhost:3000/send-message";  // jika instal di local
    $url = url_wa() . 'send-message';
    $data = [
        "sender" => $sender,
        "number" => $number,
        "message" => $msg
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/x-www-form-urlencoded']);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
    curl_setopt($ch, CURLOPT_URL, $url);
    //  curl_setopt($ch, CURLOPT_TIMEOUT_MS, 10000);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    $result = curl_exec($ch);
    curl_close($ch);
    return json_decode($result, true);
}
function sendBTN($number, $pesan, $sender, $ftr, $btn1, $btn2)
{
    $url = "http://localhost:3000/send-button";  // jika instal di local
    $url = url_wa() . 'send-button';
    $data = [
        "captionMsg" => $pesan,
        "footer" => $ftr,
        "number" => $number,
        "sender" => $sender,
        "button1" => $btn1,
        "button2" => $btn2
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/x-www-form-urlencoded']);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
    curl_setopt($ch, CURLOPT_URL, $url);
    //  curl_setopt($ch, CURLOPT_TIMEOUT_MS, 10000);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    $result = curl_exec($ch);
    curl_close($ch);
    return json_decode($result, true);
}

function sendMedia($number, $message, $sender, $filetype, $filename, $urll)
{
    $url = "http://localhost:3000/send-media";  // jika instal di local
    $url = url_wa() . 'send-media';
    $data = [
        'sender' => $sender,
        'number' => $number,
        'caption' => $message,
        'url' => $urll,
        'filename' => $filename,
        'filetype' => $filetype,
    ];
    //var_dump($data); die;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/x-www-form-urlencoded']);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    $result = curl_exec($ch);
    curl_close($ch);
    return json_decode($result, true);
}

function cekStatusWA()
{
    $url = url_wa() . "/status";

    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    //curl_setopt($curl, CURLOPT_POST, 1);
    //curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    //curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
    $response = curl_exec($curl);
    return json_decode($response, true);
}

function updateStatusMSG($id, $a)
{
    global $koneksi;
    $q = mysqli_query($koneksi, "UPDATE pesan SET status='$a' WHERE id='$id'");
}

function base64upload($base64_string, $output_file)
{
    $file = fopen($output_file, "wb");

    $data = explode(',', $base64_string);

    fwrite($file, base64_decode($data[1]));
    fclose($file);

    return $output_file;
}

function phoneToStandard($nomor)
{
    $nomor = explode("@", $nomor)[0];
    $nomor = substr($nomor, 2);
    $nomor = "0" . $nomor;

    return $nomor;
}

function sendApiUrl()
{
    global $base_url;
    return $base_url . "api/send.php?key=" . getSingleValDB("pengaturan", "id", "1", "api_key");
}

function syncMSG()
{
    global $koneksi;
    $url = url_wa() . "/getChat";
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    $response = curl_exec($curl);
    $res = json_decode($response, true);
    $final = [];
    $arr_p = [];
    foreach ($res['response'] as $sender) {
        foreach ($sender as $s) {
            $id_pesan = $s['id']['id'];
            if (checkExist("receive_chat", "id_pesan", $id_pesan) == false) {
                if ($s["fromMe"] == true) {
                    $nomor = phoneToStandard($s['to']);
                    $fromme = "1";
                } else {
                    $nomor = phoneToStandard($s['from']);
                    $fromme = "0";
                }
                if (in_array($nomor, $arr_p)) {
                } else {
                    $final[$nomor] = [];
                    array_push($arr_p, $nomor);
                }
                $pesan = $s['body'];
                $tanggal = date("Y-m-d H:i:s", $s['timestamp']);
                $ret = [
                    'id' => $id_pesan,
                    'nomor' => $nomor,
                    'pesan' => $pesan,
                    'fromMe' => $fromme,
                    'tanggal' => $tanggal
                ];

                $nomor_saya = getSingleValDB("pengaturan", "id", "1", "nomor");

                $q = mysqli_query($koneksi, "INSERT INTO receive_chat(`id_pesan`, `nomor`, `pesan`, `tanggal`, `from_me`, `nomor_saya`)
                                    VALUE('$id_pesan', '$nomor', '$pesan', '$tanggal', '$fromme', '$nomor_saya')");

                array_push($final[$nomor], $ret);
            }
        }
    }
}

function getContact()
{
    global $koneksi;

    $nomor_saya = getSingleValDB("pengaturan", "id", "1", "nomor");
    $q = mysqli_query($koneksi, "SELECT DISTINCT nomor, MAX(tanggal) FROM receive_chat WHERE nomor_saya='$nomor_saya' GROUP BY nomor ORDER BY MAX(tanggal) DESC");
    return $q;
}

function getLastMsg($nomor)
{
    global $koneksi;

    $nomor_saya = getSingleValDB("pengaturan", "id", "1", "nomor");
    $q = mysqli_query($koneksi, "SELECT * FROM receive_chat WHERE nomor='$nomor' AND nomor_saya='$nomor_saya' ORDER BY tanggal DESC LIMIT 1");
    $row = mysqli_fetch_assoc($q);
    if (date("Y-m-d", strtotime($row['tanggal'])) == date("Y-m-d")) {
        $row['tanggal'] = date("H:i", strtotime($row['tanggal']));
    } else {
        $row['tanggal'] = date("d M y H:i", strtotime($row['tanggal']));
    }
    return $row;
}

function checkExist($table, $where, $param)
{
    global $koneksi;
    $q = mysqli_query($koneksi, "SELECT * FROM `$table` WHERE `$where`='$param' LIMIT 1");
    $row = mysqli_num_rows($q);
    if ($row == 0) {
        return false;
    } else {
        return true;
    }
}
