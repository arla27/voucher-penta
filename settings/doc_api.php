<!-- Plugins CSS -->

<!-- Begin Page Content -->
<div class="content-inner">
    <div class="container-fluid">
        <div class="widget-header bg-primary">
            <h2 class="section-title text-white">DOC API BLASTJET</h2>
        </div>
        <div class="widget-body">
            <h5>BlastJET adalah layanan Whatsapp Blast API Gateway (unofficial) untuk mengirim pesan, pemberitahuan, penjadwal, pengingat, pesan grup, dan chatbots dengan integrasi sederhana untuk mempermudah promosi bisnis Anda.</h5>
            <?php
                $username = $_SESSION['username'];
                $q = mysqli_query($koneksi, "SELECT * FROM account WHERE username = '$username'");
                while ($row = mysqli_fetch_assoc($q)) { ?>
            <div class="section-block mt-5">
                <h3 class="block-title">Send Text Message</h3>
                <code class="btn" style="background-color:#2d2d2d ">
                    <span style="background-color:white;padding:2px;margin-right:5px">POST</span>/api/send-message
                </code>
                <br>
                <div class="code-block">
                    <pre><code class="language-php">&lt;?php

$data = [
    'api_key' => '<?= $row['api_key']; ?>',
    'sender'  => '628xxxxxxxx', // nomor yang sudah di scan
    'number'  => '08xxxxxxxxx', // nomor tujuan
    'message' => 'Isi Pesan' // isi pesan
];

$curl = curl_init();
curl_setopt_array($curl, array(
  CURLOPT_URL => "<?= $base_url; ?>api/send-message.php",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "POST",
  CURLOPT_POSTFIELDS => json_encode($data))
);

$response = curl_exec($curl);

curl_close($curl);
echo $response;

?&gt;</code></pre>
                </div>
                <p style="color:black">Parameter permintaan</p>
                <hr>
                <table class="table table-bordered">
                    <tbody>
                        <tr>
                            <th class="col-1 text-dark">api_key</th>
                            <td class="col-1 text-danger" style="font-size: 12px;">Dibutuhkan</td>
                            <td>Api key bisa dilihat di halaman settings</td>
                        </tr>
                        <tr>
                            <th class="col-1 text-dark">sender</th>
                            <td class="col-1 text-danger" style="font-size: 12px;">Dibutuhkan</td>
                            <td>Sender dapat Anda daftarkan di Add Number pada dashboard dan pastikan sudah scan nomor tersebut dengan status berhasil terhubung</td>
                        </tr>
                        <tr>
                            <th class="col-1 text-dark">number</th>
                            <td class="col-1 text-danger" style="font-size: 12px;">Dibutuhkan</td>
                            <td>Nomor whatsapp yang akan di hubungi</td>
                        </tr>
                        <tr>
                            <th class="col-1 text-dark">message</th>
                            <td class="col-1 text-danger" style="font-size: 12px;">Dibutuhkan</td>
                            <td>Isi dari pesan</td>
                        </tr>
                    </tbody>
                </table>
                <p style="color:black" class="mt-4">Response</p>
                <hr>
                <div class="code-block">
                    <pre><code class="language-json">
{
  "status":true,
  "result":{
       "from":{
         "phone":"628xxxxxxxxxx",
         "name":"Username"
        },
         "chat":{
            "sending":"08xxxxxxxxxxx",
            "message":"isi Pesannya"
        },
         "date":"20:11:49"
    }
 }
                    </code></pre>
                </div>
                <code class="btn" style="background-color:#2d2d2d ">
                    <span style="background-color:white;padding:2px;margin-right:5px">GET</span>/api/send
                </code>
                <hr>
                <table class="table table-bordered">
                    <tbody>
                        <tr>
                            <th class="col-1 text-dark">api_key</th>
                            <td class="col-1 text-danger" style="font-size: 12px;">Dibutuhkan</td>
                            <td>Api key bisa dilihat di halaman settings</td>
                        </tr>
                        <tr>
                            <th class="col-1 text-dark">sender</th>
                            <td class="col-1 text-danger" style="font-size: 12px;">Dibutuhkan</td>
                            <td>Sender dapat Anda daftarkan di Add Number pada dashboard dan pastikan sudah scan nomor tersebut dengan status berhasil terhubung</td>
                        </tr>
                        <tr>
                            <th class="col-1 text-dark">nomor</th>
                            <td class="col-1 text-danger" style="font-size: 12px;">Dibutuhkan</td>
                            <td>Nomor whatsapp yang akan di hubungi</td>
                        </tr>
                        <tr>
                            <th class="col-1 text-dark">pesan</th>
                            <td class="col-1 text-danger" style="font-size: 12px;">Dibutuhkan</td>
                            <td>Isi dari pesan</td>
                        </tr>
                    </tbody>
                </table>
                <p style="color:black" class="mt-4">Response</p>
                <hr>
                <div class="code-block">
                    <pre><code class="language-json">
{
  "status":true,
  "result":{
       "from":{
         "phone":"628xxxxxxxxxx",
         "name":"Username"
        },
         "chat":{
            "sending":"08xxxxxxxxxxx",
            "message":"isi Pesannya"
        },
         "date":"20:01:49"
    }
 }
                    </code></pre>
                </div>
                <p style="color:black">Example: <br><?= $base_url ?>api/send.php?api_key=<?= $row['api_key']; ?>&sender=NOMORTERKONEKSI<br>&nomor=08xxxxxxxx&pesan=ISIPESAN </p>
                <hr>
                <h3 class="block-title">Send Media</h3>
                <code class="btn" style="background-color:#2d2d2d ">
                    <span style="background-color:white;padding:2px;margin-right:5px">POST</span>/api/send-media
                </code>
                <br>
                <div class="code-block">
                    <pre><code class="language-php">&lt;?php

$data = [
    'api_key' => '<?= $row['api_key']; ?>',
    'sender'  => '628xxxxxxxx', // nomor yang sudah di scan
    'number'  => '08xxxxxxxxx', // nomor tujuan
    'message' => 'Isi Pesan' // isi pesan
    'url' => 'Link gambar/pdf' // link gambar
];
$curl = curl_init();
curl_setopt_array($curl, array(
  CURLOPT_URL => "<?= $base_url; ?>api/send-media.php",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "POST",
  CURLOPT_POSTFIELDS => json_encode($data))
);

$response = curl_exec($curl);

curl_close($curl);
echo $response;

?&gt;</code></pre>
                </div>
                <p style="color:black">Parameter permintaan</p>
                <hr>
                <table class="table table-bordered">
                    <tbody>
                        <tr>
                            <th class="col-1 text-dark">api_key</th>
                            <td class="col-1 text-danger" style="font-size: 12px;">Dibutuhkan</td>
                            <td>Api key bisa dilihat di halaman settings</td>
                        </tr>
                        <tr>
                            <th class="col-1 text-dark">sender</th>
                            <td class="col-1 text-danger" style="font-size: 12px;">Dibutuhkan</td>
                            <td>Sender dapat Anda daftarkan di Add Number pada dashboard dan pastikan sudah scan nomor tersebut dengan status berhasil terhubung</td>
                        </tr>
                        <tr>
                            <th class="col-1 text-dark">number</th>
                            <td class="col-1 text-danger" style="font-size: 12px;">Dibutuhkan</td>
                            <td>Nomor whatsapp yang akan di hubungi</td>
                        </tr>
                        <tr>
                            <th class="col-1 text-dark">message</th>
                            <td class="col-1 text-danger" style="font-size: 12px;">Dibutuhkan</td>
                            <td>Isi dari pesan</td>
                        </tr>
                        <tr>
                            <th class="col-1 text-dark">url</th>
                            <td class="col-1 text-danger" style="font-size: 12px;">Dibutuhkan</td>
                            <td>Url/link media tertaut</td>
                        </tr>
                    </tbody>
                </table>
                <p style="color:black" class="mt-4">Response</p>
                <hr>
                <div class="code-block">
                    <pre><code class="language-json">
{
  "status":true,
  "result":{
       "from":{
         "phone":"628xxxxxxxxxx",
         "name":"Username"
        },
         "chat":{
            "sending":"08xxxxxxxxxxx",
            "message":"isi Pesannya"
            "media":$url
        },
         "date":"20:11:49"
    }
 }
                    </code></pre>
                </div>

                <h3 class="block-title">Error Responses</h3>
                <hr>
                <table class="table table-bordered">
                    <tbody>
                        <tr>
                            <th class="col-1 text-dark">400</th>
                            <td class="col-1 text-danger" style="font-size: 12px;">Bad Request</td>
                            <td>Server tidak dapat memahami permintaan karena sintaks yang tidak valid.</td>
                        </tr>
                        <tr>
                            <th class="col-1 text-dark">401</th>
                            <td class="col-1 text-danger" style="font-size: 12px;">Parameter tidak sah</td>
                            <td>Parameter yang dituju tidak sah</td>
                        </tr>
                        <tr>
                            <th class="col-1 text-dark">403</th>
                            <td class="col-1 text-danger" style="font-size: 12px;">Forbidden</td>
                            <td>Klien tidak memiliki hak akses ke konten; yaitu, tidak sah, sehingga server menolak untuk memberikan sumber daya yang diminta. Tidak seperti 401, identitas klien diketahui server.</td>
                        </tr>
                        <tr>
                            <th class="col-1 text-dark">405</th>
                            <td class="col-1 text-danger" style="font-size: 12px;">Method Not Allowed</td>
                            <td>Metode permintaan diketahui oleh server tetapi tidak didukung oleh sumber daya target.</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <?php }  ?>
    </div>