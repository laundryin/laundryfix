<?php 
session_start();
include 'koneksi.php'; // Pastikan file koneksi.php kamu sudah benar

$username = $_POST['username'];
$password = $_POST['password'];

// Cari user di tabel yang barusan kita buat
$query = mysqli_query($conn, "SELECT * FROM tb_pengguna WHERE username='$username' AND password='$password'");
$cek = mysqli_num_rows($query);

if($cek > 0){
    $data = mysqli_fetch_assoc($query);
    
    // Simpan tanda pengenal di session
    $_SESSION['username'] = $username;
    $_SESSION['nama'] = $data['nama_lengkap'];
    $_SESSION['status'] = "login";

    header("location:index.php"); // Lempar ke dashboard jika benar
} else {
    header("location:login.php?pesan=gagal"); // Balikin ke login jika salah
}
?>