<?php 
include 'koneksi.php'; 

if(isset($_POST['simpan'])) {
    $nama   = mysqli_real_escape_string($conn, $_POST['nama_supplier']);
    $telp   = mysqli_real_escape_string($conn, $_POST['no_telepon']);
    $alamat = mysqli_real_escape_string($conn, $_POST['alamat']);

    $insert = mysqli_query($conn, "INSERT INTO supplier (nama_supplier, no_telepon, alamat) VALUES ('$nama', '$telp', '$alamat')");
    
    if($insert) {
        echo "<script>alert('Supplier berhasil ditambahkan!'); window.location='supplier.php';</script>";
    } else {
        echo "<script>alert('Gagal menambahkan data!');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Supplier - Harum Laundry</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style> body { font-family: 'Poppins', sans-serif; background-color: #fdf2f8; } .card { border-radius: 20px; border: none; } </style>
</head>
<body class="p-5">
    <div class="container" style="max-width: 600px;">
        <div class="card p-4 shadow-sm">
            <h4 class="fw-bold mb-4" style="color: #3b82f6;">Tambah Supplier Baru</h4>
            <form method="POST">
                <div class="mb-3">
                    <label class="form-label fw-medium">Nama Supplier / Toko</label>
                    <input type="text" name="nama_supplier" class="form-control rounded-pill" placeholder="Contoh: Toko Sabun Makmur" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-medium">Nomor Telepon / WhatsApp</label>
                    <input type="text" name="no_telepon" class="form-control rounded-pill" placeholder="Contoh: 081234567890" required>
                </div>
                <div class="mb-4">
                    <label class="form-label fw-medium">Alamat Lengkap</label>
                    <textarea name="alamat" class="form-control" rows="3" style="border-radius: 15px;" placeholder="Masukkan alamat lengkap..." required></textarea>
                </div>
                <div class="d-flex gap-2">
                    <button type="submit" name="simpan" class="btn btn-primary rounded-pill px-4" style="background: linear-gradient(90deg, #3b82f6, #ec4899); border:none;">Simpan Data</button>
                    <a href="supplier.php" class="btn btn-outline-secondary rounded-pill px-4">Batal</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>