<?php 
include 'koneksi.php'; 

$id = $_GET['id'];
$query = mysqli_query($conn, "SELECT * FROM pelanggan WHERE id_pelanggan = '$id'");
$d = mysqli_fetch_array($query);

if(isset($_POST['update'])) {
    $nama   = mysqli_real_escape_string($conn, $_POST['nama_pelanggan']);
    $telp   = mysqli_real_escape_string($conn, $_POST['no_telepon']);
    $alamat = mysqli_real_escape_string($conn, $_POST['alamat']);

    $update = mysqli_query($conn, "UPDATE pelanggan SET nama_pelanggan='$nama', no_telepon='$telp', alamat='$alamat' WHERE id_pelanggan='$id'");

    if($update) {
        echo "<script>alert('Data udah di-update, mantap!'); window.location='pelanggan.php';</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Pelanggan - Harum Laundry</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
    <style>body { font-family: 'Poppins', sans-serif; background-color: #fdf2f8; }</style>
</head>
<body class="p-5">
    <div class="container" style="max-width: 600px;">
        <div class="card p-4 shadow-sm border-0" style="border-radius: 20px;">
            <h3 class="fw-bold mb-4 text-primary">Edit Data Pelanggan</h3>
            <form method="POST">
                <div class="mb-3">
                    <label class="form-label">Nama Pelanggan</label>
                    <input type="text" name="nama_pelanggan" class="form-control rounded-pill" value="<?= $d['nama_pelanggan']; ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Nomor Telepon</label>
                    <input type="text" name="no_telepon" class="form-control rounded-pill" value="<?= $d['no_telepon']; ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Alamat</label>
                    <textarea name="alamat" class="form-control" rows="3" style="border-radius: 15px;" required><?= $d['alamat']; ?></textarea>
                </div>
                <div class="d-flex gap-2">
                    <button type="submit" name="update" class="btn btn-primary rounded-pill px-4" style="background: linear-gradient(90deg, #3b82f6, #ec4899); border:none;">Update Data</button>
                    <a href="pelanggan.php" class="btn btn-outline-secondary rounded-pill px-4">Batal</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>