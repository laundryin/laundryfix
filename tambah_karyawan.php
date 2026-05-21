<?php 
include 'koneksi.php'; 

if(isset($_POST['simpan'])) {
    $nama    = mysqli_real_escape_string($conn, $_POST['nama_karyawan']);
    $jabatan = mysqli_real_escape_string($conn, $_POST['jabatan']);

    $insert = mysqli_query($conn, "INSERT INTO karyawan (nama_karyawan, jabatan) VALUES ('$nama', '$jabatan')");

    if($insert) {
        echo "<script>alert('Karyawan baru berhasil direkrut!'); window.location='karyawan.php';</script>";
    } else {
        echo "Gagal simpan: " . mysqli_error($conn);
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Karyawan - Harum Laundry</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
    <style> body { font-family: 'Poppins', sans-serif; background-color: #fdf2f8; } .card { border-radius: 20px; border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.05); } </style>
</head>
<body class="p-5">
    <div class="container" style="max-width: 600px;">
        <div class="card p-4">
            <h3 class="fw-bold mb-4 text-primary">Tambah Karyawan Baru</h3>
            <form method="POST">
                <div class="mb-3">
                    <label class="form-label">Nama Karyawan</label>
                    <input type="text" name="nama_karyawan" class="form-control rounded-pill" placeholder="Siapa namanya?" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Jabatan</label>
                    <input type="text" name="jabatan" class="form-control rounded-pill" placeholder="Contoh: Kasir, Operator Cuci, Kang Setrika" required>
                </div>
                <div class="d-flex gap-2 mt-4">
                    <button type="submit" name="simpan" class="btn btn-primary rounded-pill px-4" style="background: linear-gradient(90deg, #3b82f6, #ec4899); border:none;">Simpan Data</button>
                    <a href="karyawan.php" class="btn btn-outline-secondary rounded-pill px-4">Batal</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>