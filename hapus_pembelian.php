<?php 
include 'koneksi.php';
$id = $_GET['id'];
mysqli_query($conn, "DELETE FROM detail_pembelian WHERE id_pembelian = '$id'");
mysqli_query($conn, "DELETE FROM pembelian WHERE id_pembelian = '$id'");
header("location:pembelian.php");
?>