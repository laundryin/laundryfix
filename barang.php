<?php 
include 'koneksi.php'; 
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Master Barang - DILAUNDRYIN</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    
    <style>
        body { font-family: 'Poppins', sans-serif; background-color: #fdf2f8; color: #334155; }
        .sidebar { width: 260px; height: 100vh; position: fixed; background: #fff; border-right: 1px solid #fce7f3; z-index: 100; overflow-y: auto;}
        .sidebar-brand { padding: 1.5rem; text-align: center; border-bottom: 1px solid #fce7f3; }
        .sidebar-brand img { max-width: 150px; } 
        .nav-link { color: #64748b; font-weight: 500; padding: 10px 20px; border-radius: 10px; margin: 4px 15px; transition: 0.3s; font-size: 0.9rem;}
        .nav-link:hover, .nav-link.active { background: #fdf2f8; color: #ec4899 !important; border-left: 4px solid #ec4899; }
        .nav-link i { margin-right: 10px; font-size: 1.1rem; }
        
        .main-content { margin-left: 260px; padding: 40px; }
        
        .table-custom { background-color: white; border-radius: 20px; overflow: hidden; box-shadow: 0 10px 30px rgba(236, 72, 153, 0.05); }
        
        /* Header tetap Bold & Center sesuai patokan */
        .table-custom thead th { 
            background-color: #f8fafc; 
            color: #64748b; 
            padding: 20px 15px; 
            border-bottom: 2px solid #f1f5f9; 
            text-align: center; 
            font-weight: 700 !important; 
            text-transform: uppercase;
            font-size: 1rem;
        }
        
        /* ISI TABEL: Font disamakan semua menjadi fw-medium agar rapi */
        .table-custom td { 
            padding: 18px 15px; 
            vertical-align: middle; 
            border-bottom: 1px solid #f1f5f9; 
            font-size: 1rem;
            font-weight: 500; /* Ini yang menyamakan semua ketebalan font isi tabel */
        }
        
        .stok-habis { background-color: #fff1f2 !important; color: #e11d48 !important; }
    </style>
</head>
<body>

    <?php include 'sidebar.php'; ?>

    <div class="main-content">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div class="d-flex align-items-center">
                <div class="bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 45px; height: 45px;">
                    <i class="bi bi-box-seam text-primary fs-4"></i>
                </div>
                <div>
                    <h3 class="fw-bold mb-0" style="color: #3b82f6;">DATA STOK BARANG</h3>
                    <p class="text-muted mb-0" style="font-size: 0.85rem;">Manajemen inventaris perlengkapan laundry</p>
                </div>
            </div>
            <a href="tambah_barang.php" class="btn rounded-pill px-4 text-white shadow-sm" style="background: linear-gradient(90deg, #3b82f6, #ec4899); border: none; font-weight: 600;">
                <i class="bi bi-plus-lg me-2"></i>TAMBAH BARANG
            </a>
        </div>

        <div class="table-custom">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th width="8%">ID</th>
                        <th class="text-start">NAMA BARANG</th>
                        <th width="12%">STOK</th>
                        <th width="12%">SATUAN</th>
                        <th width="20%">HARGA BELI AVG</th>
                        <th width="16%">AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $query = mysqli_query($conn, "SELECT * FROM barang ORDER BY id_barang DESC");
                    $no = 1; 
                    if(mysqli_num_rows($query) > 0) {
                        while($d = mysqli_fetch_array($query)){
                            $warning = ($d['stok'] <= 5) ? 'stok-habis' : '';
                    ?>
                    <tr class="<?= $warning; ?>">
                        <td class="text-center text-primary">#<?= $no++; ?></td>
                        <td class="text-start"><?= htmlspecialchars($d['nama_barang']); ?></td>
                        <td class="text-center"><?= $d['stok']; ?></td>
                        <td class="text-center text-muted"><?= htmlspecialchars($d['satuan']); ?></td>
                        <td class="text-center">
                            Rp <?= number_format($d['harga_beli'], 0, ',', '.'); ?>
                        </td>
                        <td class="text-center">
                            <div class="d-flex justify-content-center gap-1">
                                <a href="edit_barang.php?id=<?= $d['id_barang']; ?>" class="btn btn-sm btn-outline-primary rounded-pill px-3">Edit</a>
                                <a href="hapus_barang.php?id=<?= $d['id_barang']; ?>" class="btn btn-sm btn-outline-danger rounded-pill px-3" onclick="return confirm('Hapus barang ini dari gudang?')">Hapus</a>
                            </div>
                        </td>
                    </tr>
                    <?php 
                        } 
                    } else { ?>
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">
                            <i class="bi bi-emoji-frown d-block mb-2" style="font-size: 2rem;"></i>
                            Gudang kosong melompong. Silakan kulakan dulu!
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>