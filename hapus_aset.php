<?php 
include 'koneksi.php';

$id = $_GET['id'];

// Proses Hapus Data berdasarkan id_aset
$hapus = mysqli_query($conn, "DELETE FROM aset_tetap WHERE id_aset='$id'");

if($hapus) {
    echo "<script>alert('Aset berhasil dihapus!'); window.location='aset.php';</script>";
} else {
    echo "<script>alert('Gagal menghapus aset!'); window.location='aset.php';</script>";
}
?>