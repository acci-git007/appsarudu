<?php

require __DIR__ . '/vendor/autoload.php';

use Aws\S3\S3Client;

/* =====================
   KONEKSI RDS
===================== */

$conn = mysqli_connect(
    "RDS_ENDPOINT",
    "admin",
    "DB_PASSWORD",
    "dbsiswa"
);

if (!$conn) {
    die("Koneksi RDS gagal : " . mysqli_connect_error());
}

/* =====================
   KONEKSI S3
===================== */

$bucket = "NAMA_BUCKET_S3";

$s3 = new S3Client([
    'version' => 'latest',
    'region'  => 'us-east-1',

    // Jika pakai XAMPP/localhost
    'credentials' => [
        'key'    => 'AWS_ACCESS_KEY',
        'secret' => 'AWS_SECRET_KEY'
    ]
]);

/* =====================
   SIMPAN DATA
===================== */

if(isset($_POST['simpan'])){

    $nis    = $_POST['nis'];
    $nama   = $_POST['nama'];
    $kelas  = $_POST['kelas'];
    $alamat = $_POST['alamat'];

    $fotoUrl = "";

    if($_FILES['foto']['name']!=""){

        $namaFile =
        time().'_'.basename($_FILES['foto']['name']);

        try{

            $upload = $s3->putObject([
                'Bucket'     => $bucket,
                'Key'        => 'siswa/'.$namaFile,
                'SourceFile' => $_FILES['foto']['tmp_name']
            ]);

            $fotoUrl = $upload['ObjectURL'];

        }catch(Exception $e){

            die(
            "Upload S3 gagal : "
            .$e->getMessage()
            );
        }
    }

    mysqli_query($conn,"
    INSERT INTO siswa
    (nis,nama,kelas,alamat,foto)
    VALUES
    (
    '$nis',
    '$nama',
    '$kelas',
    '$alamat',
    '$fotoUrl'
    )
    ");

    header("Location:index.php");
    exit;
}

?>

<!DOCTYPE html>
<html>
<head>

<title>Data Siswa AWS</title>

<style>

body{
font-family:Arial;
background:#f2f2f2;
padding:20px;
}

.container{
background:white;
padding:20px;
border-radius:10px;
}

input,
textarea{
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
border:1px solid #ccc;
padding:10px;
}

img{
border-radius:5px;
}

</style>

</head>

<body>

<div class="container">

<h2>Input Data Siswa</h2>

<form method="post"
enctype="multipart/form-data">

<input
type="text"
name="nis"
placeholder="NIS"
required>

<input
type="text"
name="nama"
placeholder="Nama Siswa"
required>

<input
type="text"
name="kelas"
placeholder="Kelas"
required>

<textarea
name="alamat"
placeholder="Alamat"></textarea>

<input
type="file"
name="foto"
required>

<button
type="submit"
name="simpan">
Simpan
</button>

</form>

<hr>

<h2>Daftar Siswa</h2>

<table>

<tr>
<th>ID</th>
<th>Foto</th>
<th>NIS</th>
<th>Nama</th>
<th>Kelas</th>
<th>Alamat</th>
</tr>

<?php

$data =
mysqli_query(
$conn,
"SELECT * FROM siswa
ORDER BY id DESC"
);

while(
$row =
mysqli_fetch_assoc($data)
){

?>

<tr>

<td><?= $row['id']; ?></td>

<td>

<?php if(!empty($row['foto'])){ ?>

<img
src="<?= $row['foto']; ?>"
width="100">

<?php } ?>

</td>

<td><?= $row['nis']; ?></td>
<td><?= $row['nama']; ?></td>
<td><?= $row['kelas']; ?></td>
<td><?= $row['alamat']; ?></td>

</tr>

<?php } ?>

</table>

</div>

</body>
</html>
