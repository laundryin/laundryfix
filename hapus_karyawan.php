<?php 
include 'koneksi.php'; 

$id = $_GET['id'];
$delete = mysqli_query($conn, "DELETE FROM karyawan WHERE id_karyawan = '$id'");

if($delete) {
    echo "<script>alert('Sukses mecat karyawan!'); window.location='karyawan.php';</script>";
} else {
    echo "Gagal hapus, mungkin dia masih nanganin cucian atau pembelian: " . mysqli_error($conn);
}
?>