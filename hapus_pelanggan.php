<?php 
include 'koneksi.php'; 

$id = $_GET['id'];
$delete = mysqli_query($conn, "DELETE FROM pelanggan WHERE id_pelanggan = '$id'");

if($delete) {
    echo "<script>alert('Data Dihapus!'); window.location='pelanggan.php';</script>";
} else {
    echo "Gagal menghapus" . mysqli_error($conn);
}
?>