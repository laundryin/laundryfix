<?php 
// 1. PROTEKSI LOGIN (Wajib Paling Atas)
session_start();
if($_SESSION['status'] != "login"){
    header("location:login.php?pesan=belum_login");
    exit;
}

include 'koneksi.php'; 
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aset Tetap - dilaundryin ERP</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; background-color: #fdf2f8; color: #334155; margin: 0; }
        
        .sidebar { width: 260px; height: 100vh; position: fixed; background: rgba(255, 255, 255, 0.8); backdrop-filter: blur(10px); border-right: 1px solid #fce7f3; z-index: 100; overflow-y: auto;}
        .sidebar-brand { font-size: 1.8rem; font-weight: 700; background: linear-gradient(90deg, #3b82f6, #ec4899); -webkit-background-clip: text; -webkit-text-fill-color: transparent; padding: 1.5rem; text-align: center; border-bottom: 1px solid #fce7f3; }
        
        .nav-link { color: #64748b; font-weight: 500; padding: 10px 20px; border-radius: 10px; margin: 4px 15px; transition: 0.3s; font-size: 0.9rem; text-decoration: none; display: block;}
        .nav-link:hover, .nav-link.active { background: linear-gradient(90deg, rgba(59, 130, 246, 0.1), rgba(236, 72, 153, 0.1)); color: #ec4899 !important; border-left: 4px solid #ec4899; }
        .nav-link i { margin-right: 10px; font-size: 1.1rem; }

        .main-content { margin-left: 260px; padding: 40px; min-height: 100vh; }
        
        .table-custom { background-color: white; border-radius: 20px; overflow: hidden; box-shadow: 0 10px 30px rgba(236, 72, 153, 0.05); }
        .table-custom thead th { background-color: #f8fafc; color: #64748b; font-weight: 600; padding: 15px; border-bottom: 2px solid #f1f5f9; }
        .table-custom td { padding: 15px; vertical-align: middle; border-bottom: 1px solid #f1f5f9; }
        
        .badge-status { border-radius: 8px; padding: 5px 12px; font-weight: 500; }
        .text-gradient { background: linear-gradient(90deg, #3b82f6, #ec4899); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
        .btn-gradient { background: linear-gradient(90deg, #3b82f6, #ec4899); border: none; color: white; transition: 0.3s; }
        .btn-gradient:hover { color: white; opacity: 0.9; transform: translateY(-2px); }
        
        @media (max-width: 768px) {
            .sidebar { width: 0; display: none; }
            .main-content { margin-left: 0; padding: 20px; }
        }
    </style>
</head>
<body>
    
    <?php include 'sidebar.php'; ?>
    
    <div class="main-content">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="fw-bold mb-0 text-gradient">Data Aset Tetap</h3>
                <!-- Tulisan manajemen inventaris sudah dihapus dari sini -->
            </div>
            <a href="tambah_aset.php" class="btn btn-gradient rounded-pill px-4 shadow-sm fw-bold">
                <i class="bi bi-plus-lg me-2"></i>Tambah Aset
            </a>
        </div>

        <div class="table-custom">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th width="10%">Kode</th>
                            <th>Nama Aset</th>
                            <th>Kategori</th>
                            <th>Tanggal Beli</th>
                            <th>Harga Beli</th>
                            <th>Status</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $query = mysqli_query($conn, "SELECT * FROM aset_tetap ORDER BY id_aset DESC");
                        if(mysqli_num_rows($query) > 0) {
                            while($d = mysqli_fetch_array($query)){
                        ?>
                        <tr>
                            <td class="fw-bold text-secondary"><?= htmlspecialchars($d['kode_aset']); ?></td>
                            <!-- Warna nama aset diubah jadi hitam (text-dark) -->
                            <td class="fw-semibold text-dark"><?= htmlspecialchars($d['nama_aset']); ?></td>
                            <td><span class="text-muted small"><?= htmlspecialchars($d['kategori_aset']); ?></span></td>
                            <td><?= date('d/m/Y', strtotime($d['tanggal_beli'])); ?></td>
                            <td class="fw-bold text-dark">Rp <?= number_format($d['harga_beli'], 0, ',', '.'); ?></td>
                            <td>
                                <?php if($d['status_aset'] == 'Aktif') { ?>
                                    <span class="badge bg-success bg-opacity-10 text-success badge-status">Aktif</span>
                                <?php } else { ?>
                                    <span class="badge bg-danger bg-opacity-10 text-danger badge-status">Rusak/Nonaktif</span>
                                <?php } ?>
                            </td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-2">
                                    <a href="edit_aset.php?id=<?= $d['id_aset']; ?>" class="btn btn-sm btn-outline-primary rounded-circle" title="Edit">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    <a href="hapus_aset.php?id=<?= $d['id_aset']; ?>" class="btn btn-sm btn-outline-danger rounded-circle" onclick="return confirm('Yakin mau hapus aset ini?')" title="Hapus">
                                        <i class="bi bi-trash"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <?php 
                            } 
                        } else { 
                        ?>
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                <div class="opacity-50">
                                    <i class="bi bi-box-seam d-block mb-2" style="font-size: 3rem;"></i>
                                    <p class="mb-0">Belum ada data aset tetap terdaftar.</p>
                                </div>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>