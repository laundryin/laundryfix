<?php 
include 'koneksi.php'; 

// Ambil ID dari URL
$id = $_GET['id'];

// Proses Delete
$hapus = mysqli_query($conn, "DELETE FROM supplier WHERE id_supplier = '$id'");

if($hapus) {
    echo "<script>alert('Supplier berhasil dihapus dari daftar!'); window.location='supplier.php';</script>";
} else {
    // Kalau error biasanya karena ID ini dipakai di tabel pembelian (Foreign Key constraint)
    echo "<script>alert('Gagal! Supplier ini tidak bisa dihapus karena masih terikat dengan data kulakan.'); window.location='supplier.php';</script>";
}
?>