<?php

require __DIR__.'/vendor/autoload.php';

use Aws\S3\S3Client;

/* ==========================
   KONEKSI RDS
========================== */

$conn = mysqli_connect(
    "dbpenjualan.cs16kc6eazau.us-east-1.rds.amazonaws.com",
    "admin",
    "admin2026",
    "dbabsenpegawai"
);

if(!$conn){
    die("Koneksi gagal : ".mysqli_connect_error());
}

/* ==========================
   KONEKSI S3
========================== */

$bucket = "penjualan-s3";

$s3 = new S3Client([
    'version' => 'latest',
    'region'  => 'us-east-1',

    // Jika pakai XAMPP
    'credentials' => [
        'key'    => 'AWS_ACCESS_KEY',
        'secret' => 'AWS_SECRET_KEY'
    ]
]);

/* ==========================
   SIMPAN DATA
========================== */

if(isset($_POST['simpan'])){

    $nip      = $_POST['nip'];
    $nama     = $_POST['nama_pegawai'];
    $jabatan  = $_POST['jabatan'];
    $tanggal  = $_POST['tanggal_absen'];
    $status   = $_POST['status_absen'];

    $fotoUrl = "";

    if(!empty($_FILES['foto']['name'])){

        $namaFile =
        time().'_'.basename($_FILES['foto']['name']);

        try{

            $upload = $s3->putObject([
                'Bucket'     => $bucket,
                'Key'        => 'pegawai/'.$namaFile,
                'SourceFile' => $_FILES['foto']['tmp_name']
            ]);

            $fotoUrl = $upload['ObjectURL'];

        }catch(Exception $e){

            die("Upload S3 gagal : ".$e->getMessage());
        }
    }

    mysqli_query($conn,"
    INSERT INTO tbabsen
    (
        nip,
        nama_pegawai,
        jabatan,
        tanggal_absen,
        status_absen,
        foto
    )
    VALUES
    (
        '$nip',
        '$nama',
        '$jabatan',
        '$tanggal',
        '$status',
        '$fotoUrl'
    )
    ");

    header("Location: absen.php");
    exit;
}

?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Data Absen Pegawai</title>

<style>

body{
    font-family:Arial;
    background:#f4f4f4;
    margin:20px;
}

.container{
    background:#fff;
    padding:20px;
    border-radius:10px;
}

input,select{
    width:100%;
    padding:10px;
    margin-bottom:10px;
}

button{
    padding:10px 20px;
}

table{
    width:100%;
    border-collapse:collapse;
    margin-top:20px;
}

table th,
table td{
    border:1px solid #ddd;
    padding:10px;
}

table th{
    background:#007bff;
    color:white;
}

img{
    border-radius:5px;
}

</style>
</head>

<body>

<div class="container">

<h2>Input Data Absen Pegawai</h2>

<form method="post" enctype="multipart/form-data">

<input type="text"
       name="nip"
       placeholder="NIP"
       required>

<input type="text"
       name="nama_pegawai"
       placeholder="Nama Pegawai"
       required>

<input type="text"
       name="jabatan"
       placeholder="Jabatan"
       required>

<input type="date"
       name="tanggal_absen"
       required>

<select name="status_absen">

<option value="Hadir">Hadir</option>
<option value="Izin">Izin</option>
<option value="Sakit">Sakit</option>

</select>

<input type="file"
       name="foto"
       required>

<button type="submit"
        name="simpan">
Simpan
</button>

</form>

<hr>

<h2>Data Absen Pegawai</h2>

<table>

<tr>
    <th>ID</th>
    <th>Foto</th>
    <th>NIP</th>
    <th>Nama</th>
    <th>Jabatan</th>
    <th>Tanggal</th>
    <th>Status</th>
</tr>

<?php

$data = mysqli_query(
$conn,
"SELECT * FROM tbabsen ORDER BY id DESC"
);

while($row=mysqli_fetch_assoc($data)){
?>

<tr>

<td><?= $row['id']; ?></td>

<td>
<?php if(!empty($row['foto'])){ ?>
<img src="<?= $row['foto']; ?>" width="80">
<?php } ?>
</td>

<td><?= $row['nip']; ?></td>
<td><?= $row['nama_pegawai']; ?></td>
<td><?= $row['jabatan']; ?></td>
<td><?= $row['tanggal_absen']; ?></td>
<td><?= $row['status_absen']; ?></td>

</tr>

<?php } ?>

</table>

</div>

</body>
</html>
