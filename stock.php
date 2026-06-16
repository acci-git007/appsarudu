<?php

require __DIR__.'/vendor/autoload.php';

use Aws\S3\S3Client;

$conn = mysqli_connect(
    "RDS_ENDPOINT",
    "admin",
    "PASSWORD_RDS",
    "dbpenjualan"
);

$bucket = "NAMA_BUCKET_S3";

$s3 = new S3Client([
    'version'=>'latest',
    'region'=>'us-east-1'
]);

if(isset($_POST['simpan'])){

    $kode = $_POST['kode_barang'];
    $nama = $_POST['nama_barang'];
    $kategori = $_POST['kategori'];
    $stok = $_POST['stok'];
    $harga = $_POST['harga'];

    $fotoUrl="";

    if($_FILES['foto']['name']!=""){

        $namaFile=time().'_'.$_FILES['foto']['name'];

        $upload=$s3->putObject([
            'Bucket'=>$bucket,
            'Key'=>'stock/'.$namaFile,
            'SourceFile'=>$_FILES['foto']['tmp_name']
        ]);

        $fotoUrl=$upload['ObjectURL'];
    }

    mysqli_query($conn,"
    INSERT INTO tbstock
    (kode_barang,nama_barang,kategori,stok,harga,foto)
    VALUES
    ('$kode','$nama','$kategori','$stok','$harga','$fotoUrl')
    ");
}
?>

<form method="post" enctype="multipart/form-data">

<input type="text" name="kode_barang" placeholder="Kode Barang"><br><br>

<input type="text" name="nama_barang" placeholder="Nama Barang"><br><br>

<input type="text" name="kategori" placeholder="Kategori"><br><br>

<input type="number" name="stok" placeholder="Stok"><br><br>

<input type="number" name="harga" placeholder="Harga"><br><br>

<input type="file" name="foto"><br><br>

<button name="simpan">Simpan</button>

</form>

<hr>

<table border="1">

<tr>
<th>ID</th>
<th>Foto</th>
<th>Kode</th>
<th>Nama</th>
<th>Kategori</th>
<th>Stok</th>
<th>Harga</th>
</tr>

<?php

$data=mysqli_query(
$conn,
"SELECT * FROM tbstock ORDER BY id DESC"
);

while($row=mysqli_fetch_assoc($data)){
?>

<tr>

<td><?= $row['id']; ?></td>

<td>
<img src="<?= $row['foto']; ?>" width="80">
</td>

<td><?= $row['kode_barang']; ?></td>
<td><?= $row['nama_barang']; ?></td>
<td><?= $row['kategori']; ?></td>
<td><?= $row['stok']; ?></td>
<td><?= $row['harga']; ?></td>

</tr>

<?php } ?>

</table>
