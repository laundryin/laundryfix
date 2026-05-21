<?php 
include 'koneksi.php'; 
$id = $_GET['id'];
$d = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM akun WHERE kode_akun = '$id'"));

if(isset($_POST['update'])) {
    $nama     = mysqli_real_escape_string($conn, $_POST['nama_akun']);
    $kategori = mysqli_real_escape_string($conn, $_POST['kategori_akun']);
    $saldo_n  = mysqli_real_escape_string($conn, $_POST['saldo_normal']);

    $update = mysqli_query($conn, "UPDATE akun SET nama_akun='$nama', kategori_akun='$kategori', saldo_normal='$saldo_n' WHERE kode_akun='$id'");
    
    if($update) {
        echo "<script>alert('Akun berhasil di-update!'); window.location='akun.php';</script>";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Akun - Harum Laundry</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
    <style> body { font-family: 'Poppins', sans-serif; background-color: #fdf2f8; } .card { border-radius: 20px; border: none; } </style>
</head>
<body class="p-4">
    <div class="container" style="max-width: 600px;">
        <div class="card p-4 shadow-sm">
            <h3 class="fw-bold mb-4 text-primary">Edit Master Akun</h3>
            <form method="POST">
                
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Kode Akun</label>
                        <!-- Sengaja dikasih readonly biar nggak bisa diubah -->
                        <input type="text" name="kode_akun" class="form-control rounded-pill bg-light" value="<?= $d['kode_akun']; ?>" readonly>
                        <small class="text-muted" style="font-size: 11px;">*Kode tidak bisa diubah</small>
                    </div>
                    <div class="col-md-8 mb-3">
                        <label class="form-label">Nama Akun</label>
                        <input type="text" name="nama_akun" class="form-control rounded-pill" value="<?= $d['nama_akun']; ?>" required>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Kategori Akun</label>
                        <select name="kategori_akun" class="form-select rounded-pill" required>
                            <option value="Aset" <?= ($d['kategori_akun'] == 'Aset') ? 'selected' : ''; ?>>Aset</option>
                            <option value="Kewajiban" <?= ($d['kategori_akun'] == 'Kewajiban') ? 'selected' : ''; ?>>Kewajiban</option>
                            <option value="Ekuitas" <?= ($d['kategori_akun'] == 'Ekuitas') ? 'selected' : ''; ?>>Ekuitas</option>
                            <option value="Pendapatan" <?= ($d['kategori_akun'] == 'Pendapatan') ? 'selected' : ''; ?>>Pendapatan</option>
                            <option value="Beban" <?= ($d['kategori_akun'] == 'Beban') ? 'selected' : ''; ?>>Beban</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Saldo Normal</label>
                        <select name="saldo_normal" class="form-select rounded-pill" required>
                            <option value="Debit" <?= ($d['saldo_normal'] == 'Debit') ? 'selected' : ''; ?>>Debit</option>
                            <option value="Kredit" <?= ($d['saldo_normal'] == 'Kredit') ? 'selected' : ''; ?>>Kredit</option>
                        </select>
                    </div>
                </div>

                <div class="d-flex gap-2 mt-4 justify-content-end">
                    <a href="akun.php" class="btn btn-outline-secondary rounded-pill px-4">Batal</a>
                    <button type="submit" name="update" class="btn btn-primary rounded-pill px-5">Update Akun</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>