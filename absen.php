<?php

require __DIR__.'/vendor/autoload.php';

use Aws\S3\S3Client;

$conn=mysqli_connect(
"RDS_ENDPOINT",
"admin",
"PASSWORD_RDS",
"dbabsenpegawai"
);

$bucket="NAMA_BUCKET_S3";

$s3=new S3Client([
'version'=>'latest',
'region'=>'us-east-1'
]);

if(isset($_POST['simpan'])){

    $nip=$_POST['nip'];
    $nama=$_POST['nama_pegawai'];
    $jabatan=$_POST['jabatan'];
    $tanggal=$_POST['tanggal_absen'];
    $status=$_POST['status_absen'];

    $fotoUrl="";

    if($_FILES['foto']['name']!=""){

        $namaFile=time().'_'.$_FILES['foto']['name'];

        $upload=$s3->putObject([
            'Bucket'=>$bucket,
            'Key'=>'pegawai/'.$namaFile,
            'SourceFile'=>$_FILES['foto']['tmp_name']
        ]);

        $fotoUrl=$upload['ObjectURL'];
    }

    mysqli_query($conn,"
    INSERT INTO tbabsen
    (nip,nama_pegawai,jabatan,tanggal_absen,status_absen,foto)
    VALUES
    ('$nip','$nama','$jabatan','$tanggal','$status','$fotoUrl')
    ");
}
?>

<form method="post" enctype="multipart/form-data">

<input type="text" name="nip" placeholder="NIP"><br><br>

<input type="text" name="nama_pegawai" placeholder="Nama Pegawai"><br><br>

<input type="text" name="jabatan" placeholder="Jabatan"><br><br>

<input type="date" name="tanggal_absen"><br><br>

<input type="text" name="status_absen" placeholder="Hadir/Izin"><br><br>

<input type="file" name="foto"><br><br>

<button name="simpan">Simpan</button>

</form>
