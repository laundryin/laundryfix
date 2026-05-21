<?php 
include 'koneksi.php'; 
$id = $_GET['id'];
$del = mysqli_query($conn, "DELETE FROM barang WHERE id_barang = '$id'");
if($del) echo "<script>alert('Barang dihapus!'); window.location='barang.php';</script>";
?>