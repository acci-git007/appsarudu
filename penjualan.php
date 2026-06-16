<?php

$conn=mysqli_connect(
"RDS_ENDPOINT",
"admin",
"PASSWORD_RDS",
"dbpenjualan"
);

if(isset($_POST['simpan'])){

    $notransaksi=$_POST['no_transaksi'];
    $tanggal=$_POST['tanggal'];
    $barang=$_POST['nama_barang'];
    $jumlah=$_POST['jumlah'];
    $harga=$_POST['harga'];

    $total=$jumlah*$harga;

    mysqli_query($conn,"
    INSERT INTO tbpenjualan
    (no_transaksi,tanggal,nama_barang,jumlah,harga,total)
    VALUES
    ('$notransaksi','$tanggal','$barang','$jumlah','$harga','$total')
    ");
}
?>

<form method="post">

<input type="text" name="no_transaksi" placeholder="No Transaksi"><br><br>

<input type="date" name="tanggal"><br><br>

<input type="text" name="nama_barang" placeholder="Nama Barang"><br><br>

<input type="number" name="jumlah" placeholder="Jumlah"><br><br>

<input type="number" name="harga" placeholder="Harga"><br><br>

<button name="simpan">Simpan</button>

</form>

<hr>

<table border="1">

<tr>
<th>ID</th>
<th>No Transaksi</th>
<th>Tanggal</th>
<th>Barang</th>
<th>Jumlah</th>
<th>Harga</th>
<th>Total</th>
</tr>

<?php

$data=mysqli_query(
$conn,
"SELECT * FROM tbpenjualan ORDER BY id DESC"
);

while($row=mysqli_fetch_assoc($data)){
?>

<tr>

<td><?= $row['id']; ?></td>
<td><?= $row['no_transaksi']; ?></td>
<td><?= $row['tanggal']; ?></td>
<td><?= $row['nama_barang']; ?></td>
<td><?= $row['jumlah']; ?></td>
<td><?= $row['harga']; ?></td>
<td><?= $row['total']; ?></td>

</tr>

<?php } ?>

</table>
