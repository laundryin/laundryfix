<?php 
include 'koneksi.php'; 

// Tangkap ID dari URL
$id = $_GET['id'];

// Ambil data layanan berdasarkan ID
$query = mysqli_query($conn, "SELECT * FROM tb_layanan WHERE id_layanan = '$id'");
$d = mysqli_fetch_array($query);

if(isset($_POST['update'])) {
    $nama_layanan = mysqli_real_escape_string($conn, $_POST['nama_layanan']);
    $kategori     = mysqli_real_escape_string($conn, $_POST['kategori_layanan']);
    $satuan       = mysqli_real_escape_string($conn, $_POST['satuan']);
    $harga_jual   = (int) $_POST['harga_jual'];

    // Query Update
    $update = mysqli_query($conn, "UPDATE tb_layanan SET 
                                    nama_layanan = '$nama_layanan', 
                                    kategori_layanan = '$kategori', 
                                    satuan = '$satuan', 
                                    harga_jual = '$harga_jual' 
                                    WHERE id_layanan = '$id'");

    if($update) {
        echo "<script>alert('Data layanan berhasil di-update!'); window.location='layanan.php';</script>";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Layanan - Harum Laundry</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    
    <style>
        body { font-family: 'Poppins', sans-serif; background-color: #fdf2f8; color: #334155; }
        .sidebar { width: 260px; height: 100vh; position: fixed; background: #fff; border-right: 1px solid #fce7f3; z-index: 100; overflow-y: auto; top: 0; left: 0;}
        .sidebar-brand { padding: 1.5rem; text-align: center; border-bottom: 1px solid #fce7f3; }
        .sidebar-brand img { max-width: 150px; }
        .nav-link { color: #64748b; font-weight: 500; padding: 10px 20px; border-radius: 10px; margin: 4px 15px; transition: 0.3s; font-size: 0.9rem; text-decoration: none; display: block;}
        .nav-link:hover, .nav-link.active { background: #fdf2f8; color: #ec4899 !important; border-left: 4px solid #ec4899; }
        .nav-link i { margin-right: 10px; font-size: 1.1rem; }
        .menu-title { font-size: 0.7rem; font-weight: 700; color: #475569; padding: 0 30px; margin-top: 20px; margin-bottom: 5px; text-transform: uppercase;}
        .sidebar::-webkit-scrollbar { width: 5px; }
        .sidebar::-webkit-scrollbar-thumb { background: #fbcfe8; border-radius: 10px; }
        .main-content { margin-left: 260px; padding: 40px; }
        .card-form { border-radius: 20px; border: none; background: white; padding: 40px; box-shadow: 0 10px 30px rgba(236, 72, 153, 0.05); }
        .form-control, .form-select { border-radius: 10px; padding: 12px 15px; border: 1px solid #e2e8f0; font-size: 0.95rem; }
        .form-control:focus, .form-select:focus { border-color: #10b981; box-shadow: 0 0 0 0.25rem rgba(16, 185, 129, 0.1); }
        .form-label { font-weight: 500; color: #475569; font-size: 0.9rem; margin-bottom: 8px; }
    </style>
</head>
<body>

    <?php include 'sidebar.php'; ?>

    <div class="main-content">
        <div class="container" style="max-width: 700px; margin: 0;">
            <div class="card-form">
                
                <div class="d-flex align-items-center mb-4 pb-3 border-bottom">
                    <div class="bg-success bg-opacity-10 text-success rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px;">
                        <i class="bi bi-pencil-square fs-4"></i>
                    </div>
                    <div>
                        <h3 class="fw-bold mb-0" style="color: #10b981;">Edit Master Layanan</h3>
                        <p class="text-muted mb-0" style="font-size: 0.85rem;">Ubah nama, kategori, satuan, atau update harga terbaru</p>
                    </div>
                </div>

                <form method="POST">
                    
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label class="form-label">Kode Layanan</label>
                            <input type="text" name="id_layanan" class="form-control bg-light text-secondary fw-bold" value="<?= htmlspecialchars($d['id_layanan']); ?>" readonly>
                            <small class="text-muted" style="font-size: 0.75rem;">*Kode tidak bisa diubah</small>
                        </div>
                        <div class="col-md-8">
                            <label class="form-label">Nama Layanan</label>
                            <input type="text" name="nama_layanan" class="form-control" value="<?= htmlspecialchars($d['nama_layanan']); ?>" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Kategori</label>
                            <select name="kategori_layanan" class="form-select" required>
                                <option value="Drop-off" <?= ($d['kategori_layanan'] == 'Drop-off') ? 'selected' : ''; ?>>Drop-off (Titip Cuci)</option>
                                <option value="Self-Service" <?= ($d['kategori_layanan'] == 'Self-Service') ? 'selected' : ''; ?>>Self-Service</option>
                                <option value="Lainnya" <?= ($d['kategori_layanan'] == 'Lainnya') ? 'selected' : ''; ?>>Lainnya</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Satuan</label>
                            <select name="satuan" class="form-select" required>
                                <option value="Kg" <?= ($d['satuan'] == 'Kg') ? 'selected' : ''; ?>>Kilogram (Kg)</option>
                                <option value="Pcs" <?= ($d['satuan'] == 'Pcs') ? 'selected' : ''; ?>>Satuan (Pcs)</option>
                                <option value="Meter" <?= ($d['satuan'] == 'Meter') ? 'selected' : ''; ?>>Meter (Karpet dll)</option>
                                <option value="Load 8Kg" <?= ($d['satuan'] == 'Load 8Kg') ? 'selected' : ''; ?>>1 Load / Mesin (Max 8 Kg)</option>
                                <option value="Load 10Kg" <?= ($d['satuan'] == 'Load 10Kg') ? 'selected' : ''; ?>>1 Load / Mesin (Max 10 Kg)</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Harga Jual (Rp)</label>
                        <input type="number" name="harga_jual" class="form-control fw-bold text-primary" value="<?= $d['harga_jual']; ?>" required>
                    </div>

                    <div class="d-flex gap-3 mt-4 pt-3 justify-content-end border-top">
                        <a href="layanan.php" class="btn btn-light px-4" style="border-radius: 10px; font-weight: 500;">Batal</a>
                        <button type="submit" name="update" class="btn btn-success px-5 shadow-sm" style="border-radius: 10px; font-weight: 500;">
                            <i class="bi bi-check2-circle me-2"></i>Update Layanan
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>

</body>
</html>