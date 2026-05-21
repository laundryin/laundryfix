<?php include 'koneksi.php'; ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Master Supplier - Harum Laundry ERP</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; background-color: #fdf2f8; color: #334155; }
        .sidebar { width: 260px; height: 100vh; position: fixed; background: rgba(255, 255, 255, 0.8); backdrop-filter: blur(10px); border-right: 1px solid #fce7f3; z-index: 100; overflow-y: auto;}
        .sidebar-brand { font-size: 1.8rem; font-weight: 700; background: linear-gradient(90deg, #3b82f6, #ec4899); -webkit-background-clip: text; -webkit-text-fill-color: transparent; padding: 1.5rem; text-align: center; border-bottom: 1px solid #fce7f3; }
        .nav-link { color: #64748b; font-weight: 500; padding: 10px 20px; border-radius: 10px; margin: 4px 15px; transition: 0.3s; font-size: 0.9rem;}
        .nav-link:hover, .nav-link.active { background: linear-gradient(90deg, rgba(59, 130, 246, 0.1), rgba(236, 72, 153, 0.1)); color: #ec4899 !important; border-left: 4px solid #ec4899; }
        .nav-link i { margin-right: 10px; font-size: 1.1rem; }
        .main-content { margin-left: 260px; padding: 40px; }
        .table-custom { background-color: white; border-radius: 20px; overflow: hidden; box-shadow: 0 10px 30px rgba(236, 72, 153, 0.05); }
        .table-custom thead th { background-color: #f8fafc; color: #64748b; font-weight: 600; padding: 15px; border-bottom: 2px solid #f1f5f9; }
        .table-custom td { padding: 15px; vertical-align: middle; border-bottom: 1px solid #f1f5f9; }
    </style>
</head>
<body>

    <?php include 'sidebar.php'; ?>

    <div class="main-content">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="fw-bold mb-0" style="color: #3b82f6;">Daftar Supplier</h3>
                <!-- Tulisan Kelola data mitra sudah dihapus -->
            </div>
            <a href="tambah_supplier.php" class="btn rounded-pill px-4 text-white shadow-sm" style="background: linear-gradient(90deg, #3b82f6, #ec4899); border: none;">
                <i class="bi bi-plus-lg me-2"></i>Tambah Supplier
            </a>
        </div>

        <div class="table-custom">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nama Supplier</th>
                            <th>Alamat</th>
                            <th>No. Telepon</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $query = mysqli_query($conn, "SELECT * FROM supplier ORDER BY id_supplier DESC");
                        if(mysqli_num_rows($query) > 0) {
                            while($d = mysqli_fetch_array($query)){
                        ?>
                        <tr>
                            <td class="fw-bold text-muted">#SUP-<?= $d['id_supplier']; ?></td>
                            <td class="fw-semibold"><?= htmlspecialchars($d['nama_supplier']); ?></td>
                            <td><?= htmlspecialchars($d['alamat']); ?></td>
                            <td><span class="badge bg-light text-primary rounded-pill px-3 py-2"><i class="bi bi-telephone me-1"></i> <?= $d['no_telepon']; ?></span></td>
                            <td class="text-center">
                                <div class="btn-group">
                                    <a href="edit_supplier.php?id=<?= $d['id_supplier']; ?>" class="btn btn-sm btn-outline-primary rounded-pill px-3 me-2">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <a href="hapus_supplier.php?id=<?= $d['id_supplier']; ?>" class="btn btn-sm btn-outline-danger rounded-pill px-3" onclick="return confirm('Hapus supplier ini?')">
                                        <i class="bi bi-trash"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <?php 
                            } 
                        } else { ?>
                        <tr><td colspan="5" class="text-center py-5 text-muted">Belum ada data supplier. Klik tombol tambah untuk memulai.</td></tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</body>
</html>