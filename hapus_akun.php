<?php 
include 'koneksi.php'; 
$id = $_GET['id'];
$del = mysqli_query($conn, "DELETE FROM akun WHERE kode_akun = '$id'");

if($del) {
    echo "<script>alert('Akun berhasil dihapus!'); window.location='akun.php';</script>";
} else {
    echo "<script>alert('Gagal hapus! Mungkin akun ini udah dipakai di Jurnal Umum.'); window.location='akun.php';</script>";
}
?>