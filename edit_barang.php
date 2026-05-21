<?php 
include 'koneksi.php'; 
$id = $_GET['id'];
$d = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM barang WHERE id_barang = '$id'"));

if(isset($_POST['update'])) {
    $nama   = mysqli_real_escape_string($conn, $_POST['nama_barang']);
    $stok   = (int) $_POST['stok'];
    $satuan = mysqli_real_escape_string($conn, $_POST['satuan']);
    $harga  = (int) $_POST['harga_beli'];

    $update = mysqli_query($conn, "UPDATE barang SET nama_barang='$nama', stok='$stok', satuan='$satuan', harga_beli='$harga' WHERE id_barang='$id'");
    if($update) echo "<script>alert('Data barang di-update!'); window.location='barang.php';</script>";
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Barang - Harum Laundry</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style> body { font-family: 'Poppins', sans-serif; background-color: #fdf2f8; } .card { border-radius: 20px; border: none; } </style>
</head>
<body class="p-5">
    <div class="container" style="max-width: 600px;">
        <div class="card p-4 shadow-sm">
            <h3 class="fw-bold mb-4 text-primary">Edit Barang</h3>
            <form method="POST">
                <div class="mb-3">
                    <label class="form-label">Nama Barang</label>
                    <input type="text" name="nama_barang" class="form-control rounded-pill" value="<?= $d['nama_barang']; ?>" required>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Stok</label>
                        <input type="number" name="stok" class="form-control rounded-pill" value="<?= $d['stok']; ?>" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Satuan</label>
                        <input type="text" name="satuan" class="form-control rounded-pill" value="<?= $d['satuan']; ?>" required>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Harga Beli (Rp)</label>
                    <input type="number" name="harga_beli" class="form-control rounded-pill" value="<?= $d['harga_beli']; ?>" required>
                </div>
                <button type="submit" name="update" class="btn btn-primary rounded-pill px-4">Update</button>
                <a href="barang.php" class="btn btn-link">Batal</a>
            </form>
        </div>
    </div>
</body>
</html>