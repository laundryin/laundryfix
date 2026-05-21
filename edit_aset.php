<?php 
include 'koneksi.php'; 

$id = $_GET['id'];
$query = mysqli_query($conn, "SELECT * FROM aset_tetap WHERE id_aset = '$id'");
$d = mysqli_fetch_array($query);

if(isset($_POST['update'])) {
    $kode = mysqli_real_escape_string($conn, $_POST['kode_aset']);
    $nama = mysqli_real_escape_string($conn, $_POST['nama_aset']);
    $kategori = mysqli_real_escape_string($conn, $_POST['kategori_aset']);
    $tgl = mysqli_real_escape_string($conn, $_POST['tanggal_beli']);
    $harga = mysqli_real_escape_string($conn, $_POST['harga_beli']);
    $umur = mysqli_real_escape_string($conn, $_POST['umur_ekonomis_bulan']);
    $status = mysqli_real_escape_string($conn, $_POST['status_aset']);

    $update = mysqli_query($conn, "UPDATE aset_tetap SET 
        kode_aset='$kode', nama_aset='$nama', kategori_aset='$kategori', 
        tanggal_beli='$tgl', harga_beli='$harga', umur_ekonomis_bulan='$umur', status_aset='$status' 
        WHERE id_aset='$id'");

    if($update) {
        echo "<script>alert('Data aset berhasil di-update!'); window.location='aset.php';</script>";
    } else {
        echo "<script>alert('Gagal update data!');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Aset - Harum Laundry</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style> body { font-family: 'Poppins', sans-serif; background-color: #fdf2f8; } .card { border-radius: 20px; border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.05); } </style>
</head>
<body class="p-5">
    <div class="container" style="max-width: 700px;">
        <div class="card p-4">
            <h3 class="fw-bold mb-4 text-primary" style="color: #3b82f6 !important;">Edit Data Aset Tetap</h3>
            <form method="POST">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Kode Aset</label>
                        <input type="text" name="kode_aset" class="form-control rounded-pill" value="<?= $d['kode_aset']; ?>" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Nama Aset</label>
                        <input type="text" name="nama_aset" class="form-control rounded-pill" value="<?= $d['nama_aset']; ?>" required>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Kategori</label>
                        <select name="kategori_aset" class="form-select rounded-pill" required>
                            <option value="Mesin Cuci" <?= ($d['kategori_aset'] == 'Mesin Cuci') ? 'selected' : ''; ?>>Mesin Cuci</option>
                            <option value="Mesin Pengering" <?= ($d['kategori_aset'] == 'Mesin Pengering') ? 'selected' : ''; ?>>Mesin Pengering</option>
                            <option value="Peralatan Lainnya" <?= ($d['kategori_aset'] == 'Peralatan Lainnya') ? 'selected' : ''; ?>>Peralatan Lainnya</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Tanggal Beli</label>
                        <input type="date" name="tanggal_beli" class="form-control rounded-pill" value="<?= $d['tanggal_beli']; ?>" required>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Harga Beli</label>
                        <input type="number" name="harga_beli" class="form-control rounded-pill" value="<?= $d['harga_beli']; ?>" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Umur Ekonomis (Bulan)</label>
                        <input type="number" name="umur_ekonomis_bulan" class="form-control rounded-pill" value="<?= $d['umur_ekonomis_bulan']; ?>" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Status</label>
                        <select name="status_aset" class="form-select rounded-pill" required>
                            <option value="Aktif" <?= ($d['status_aset'] == 'Aktif') ? 'selected' : ''; ?>>Aktif</option>
                            <option value="Rusak" <?= ($d['status_aset'] == 'Rusak') ? 'selected' : ''; ?>>Rusak</option>
                        </select>
                    </div>
                </div>

                <div class="d-flex gap-2 mt-4">
                    <button type="submit" name="update" class="btn btn-primary rounded-pill px-4" style="background: linear-gradient(90deg, #3b82f6, #ec4899); border:none;">Update Data</button>
                    <a href="aset.php" class="btn btn-outline-secondary rounded-pill px-4">Batal</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>