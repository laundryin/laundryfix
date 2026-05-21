<?php 
include 'koneksi.php'; 

// Mengambil ID dari URL
$id = $_GET['id'];

// Perbaikan: Ganti 'layanan' menjadi 'tb_layanan' sesuai nama tabel di database kamu
$delete = mysqli_query($conn, "DELETE FROM tb_layanan WHERE id_layanan = '$id'");

if($delete) {
    echo "<script>alert('Layanan berhasil dihapus!'); window.location='layanan.php';</script>";
} else {
    // Menampilkan error lebih detail jika gagal
    echo "Layanan gagal dihapus: " . mysqli_error($conn);
}
?>