<?php 
include 'koneksi.php'; 

// Ambil ID dari URL
$id = $_GET['id'];

// Tarik data lama
$query = mysqli_query($conn, "SELECT * FROM supplier WHERE id_supplier = '$id'");
$d = mysqli_fetch_array($query);

// Proses Update
if(isset($_POST['update'])) {
    $nama   = mysqli_real_escape_string($conn, $_POST['nama_supplier']);
    $telp   = mysqli_real_escape_string($conn, $_POST['no_telepon']);
    $alamat = mysqli_real_escape_string($conn, $_POST['alamat']);

    $update = mysqli_query($conn, "UPDATE supplier SET nama_supplier='$nama', no_telepon='$telp', alamat='$alamat' WHERE id_supplier='$id'");
    
    if($update) {
        echo "<script>alert('Data supplier berhasil diupdate!'); window.location='supplier.php';</script>";
    } else {
        echo "<script>alert('Gagal update data!');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Supplier - Harum Laundry</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style> body { font-family: 'Poppins', sans-serif; background-color: #fdf2f8; } .card { border-radius: 20px; border: none; } </style>
</head>
<body class="p-5">
    <div class="container" style="max-width: 600px;">
        <div class="card p-4 shadow-sm">
            <h4 class="fw-bold mb-4" style="color: #ec4899;">Edit Data Supplier</h4>
            <form method="POST">
                <div class="mb-3">
                    <label class="form-label fw-medium">Nama Supplier / Toko</label>
                    <input type="text" name="nama_supplier" class="form-control rounded-pill" value="<?= htmlspecialchars($d['nama_supplier']); ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-medium">Nomor Telepon / WhatsApp</label>
                    <input type="text" name="no_telepon" class="form-control rounded-pill" value="<?= htmlspecialchars($d['no_telepon']); ?>" required>
                </div>
                <div class="mb-4">
                    <label class="form-label fw-medium">Alamat Lengkap</label>
                    <textarea name="alamat" class="form-control" rows="3" style="border-radius: 15px;" required><?= htmlspecialchars($d['alamat']); ?></textarea>
                </div>
                <div class="d-flex gap-2">
                    <button type="submit" name="update" class="btn btn-primary rounded-pill px-4" style="background: linear-gradient(90deg, #ec4899, #3b82f6); border:none;">Update Data</button>
                    <a href="supplier.php" class="btn btn-outline-secondary rounded-pill px-4">Batal</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>