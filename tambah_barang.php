<?php 
include 'koneksi.php'; 
if(isset($_POST['simpan'])) {
    $nama   = mysqli_real_escape_string($conn, $_POST['nama_barang']);
    $stok   = (int) $_POST['stok'];
    $satuan = mysqli_real_escape_string($conn, $_POST['satuan']);
    $harga  = (int) $_POST['harga_beli'];

    $insert = mysqli_query($conn, "INSERT INTO barang (nama_barang, stok, satuan, harga_beli) VALUES ('$nama', '$stok', '$satuan', '$harga')");
    if($insert) echo "<script>alert('Barang terdaftar!'); window.location='barang.php';</script>";
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Barang - Harum Laundry</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
    <style> body { font-family: 'Poppins', sans-serif; background-color: #fdf2f8; } .card { border-radius: 20px; border: none; } </style>
</head>
<body class="p-5">
    <div class="container" style="max-width: 600px;">
        <div class="card p-4 shadow-sm">
            <h3 class="fw-bold mb-4 text-primary">Tambah Barang Baru</h3>
            <form method="POST">
                <div class="mb-3">
                    <label class="form-label">Nama Barang / Bahan</label>
                    <input type="text" name="nama_barang" class="form-control rounded-pill" placeholder="Misal: Deterjen Cair Rose" required>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Stok Awal</label>
                        <input type="number" name="stok" class="form-control rounded-pill" value="0" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Satuan</label>
                        <input type="text" name="satuan" class="form-control rounded-pill" placeholder="Liter, Kg, Pcs" required>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Harga Beli Per Satuan (Rp)</label>
                    <input type="number" name="harga_beli" class="form-control rounded-pill" placeholder="Contoh: 15000" required>
                </div>
                <div class="d-flex gap-2">
                    <button type="submit" name="simpan" class="btn btn-primary rounded-pill px-4" style="background: linear-gradient(90deg, #3b82f6, #ec4899); border:none;">Simpan</button>
                    <a href="barang.php" class="btn btn-outline-secondary rounded-pill px-4">Batal</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>