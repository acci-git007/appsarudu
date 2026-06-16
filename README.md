1. CREATE DATABASE dbpenjualan;

USE dbpenjualan;

CREATE TABLE tbstock (
    id INT AUTO_INCREMENT PRIMARY KEY,
    kode_barang VARCHAR(20),
    nama_barang VARCHAR(100),
    kategori VARCHAR(50),
    stok INT,
    harga DECIMAL(12,2),
    foto VARCHAR(500),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);



2. USE dbpenjualan;

CREATE TABLE tbpenjualan (
    id INT AUTO_INCREMENT PRIMARY KEY,
    no_transaksi VARCHAR(30),
    tanggal DATE,
    nama_barang VARCHAR(100),
    jumlah INT,
    harga DECIMAL(12,2),
    total DECIMAL(12,2),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);


3. CREATE DATABASE dbabsenpegawai;

USE dbabsenpegawai;

CREATE TABLE tbabsen (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nip VARCHAR(30),
    nama_pegawai VARCHAR(100),
    jabatan VARCHAR(100),
    tanggal_absen DATE,
    status_absen VARCHAR(20),
    foto VARCHAR(500),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);


4.IAM ROLE-EC2

{
  "Version": "2012-10-17",
  "Statement": [
    {
      "Sid": "ListBucket",
      "Effect": "Allow",
      "Action": [
        "s3:ListBucket"
      ],
      "Resource": [
        "arn:aws:s3:::project-arsyad-bucket"
      ]
    },
    {
      "Sid": "ManageObjects",
      "Effect": "Allow",
      "Action": [
        "s3:GetObject",
        "s3:PutObject",
        "s3:DeleteObject"
      ],
      "Resource": [
        "arn:aws:s3:::project-arsyad-bucket/*"
      ]
    }
  ]
}



BUCKET POLICY DI S3

{
  "Version": "2012-10-17",
  "Statement": [
    {
      "Sid": "PublicRead",
      "Effect": "Allow",
      "Principal": "*",
      "Action": "s3:GetObject",
      "Resource": "arn:aws:s3:::project-arsyad-bucket/*"
    }
  ]
}
