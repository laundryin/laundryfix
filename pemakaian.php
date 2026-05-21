<?php include 'koneksi.php'; ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Pemakaian Barang - DILAUNDRYIN </title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        /* CSS Utama tetep copas aja */
        body { font-family: 'Poppins', sans-serif; background-color: #fdf2f8; color: #334155; }
        
        /* CSS Sidebar (Karena include sidebar.php, ini cuma butuh stylingnya aja) */
        .sidebar { width: 260px; height: 100vh; position: fixed; background: #fff; border-right: 1px solid #fce7f3; z-index: 100; overflow-y: auto;}
        .sidebar-brand { padding: 1.5rem; text-align: center; border-bottom: 1px solid #fce7f3; }
        .nav-link { color: #64748b; font-weight: 500; padding: 10px 20px; border-radius: 10px; margin: 4px 15px; transition: 0.3s; font-size: 0.9rem;}
        .nav-link:hover, .nav-link.active { background: #fdf2f8; color: #ec4899 !important; border-left: 4px solid #ec4899; }
        .nav-link i { margin-right: 10px; font-size: 1.1rem; }
        .menu-title { font-size: 0.7rem; font-weight: 700; color: #475569; padding: 0 30px; margin-top: 20px; margin-bottom: 5px; text-transform: uppercase;}
        .sidebar::-webkit-scrollbar { width: 5px; }
        .sidebar::-webkit-scrollbar-thumb { background: #fbcfe8; border-radius: 10px; }

        .main-content { margin-left: 260px; padding: 40px; }
        .table-custom { background-color: white; border-radius: 20px; overflow: hidden; box-shadow: 0 10px 30px rgba(236, 72, 153, 0.05); }
        .table-custom thead th { background-color: #f8fafc; color: #64748b; padding: 15px; }
        .table-custom td { padding: 15px; vertical-align: middle; border-bottom: 1px solid #f1f5f9;}
    </style>
</head>
<body>

    <!-- Panggil file sidebar yang barusan lo bikin -->
    <?php include 'sidebar.php'; ?>

    <div class="main-content">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="fw-bold mb-0" style="color: #3b82f6;">Riwayat Pakai Barang</h3>
            <a href="tambah_pemakaian.php" class="btn rounded-pill px-4 text-white shadow-sm" style="background: linear-gradient(90deg, #3b82f6, #ec4899); border: none;">
                <i class="bi bi-droplet me-2"></i>Input Pemakaian
            </a>
        </div>

        <div class="table-custom">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Tanggal Keluar</th>
                        <th>Barang</th>
                        <th>Qty Keluar</th>
                        <th>Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Asumsi struktur tabel lo: id_pemakaian, tanggal, id_barang, qty, keterangan
                    // Kalau beda, tolong disesuain ya Wan!
                    $query = mysqli_query($conn, "
                        SELECT p.id_pemakaian, p.tanggal_pakai, p.qty_dipakai, b.nama_barang, b.satuan 
                        FROM pemakaian_bahan p 
                        JOIN barang b ON p.id_barang = b.id_barang
                        ORDER BY p.tanggal_pakai DESC, p.id_pemakaian DESC
                    ");

                    if(mysqli_num_rows($query) > 0) {
                        while($d = mysqli_fetch_array($query)){
                    ?>
                    <tr>
                        <td class="fw-bold text-secondary">#PK-<?= $d['id_pemakaian']; ?></td>
                        <td><?= date('d/m/Y', strtotime($d['tanggal_pakai'])); ?></td>
                        <td class="fw-medium text-primary"><?= htmlspecialchars($d['nama_barang']); ?></td>
                        <td class="fw-bold text-danger">- <?= $d['qty_dipakai']; ?> <?= $d['satuan']; ?></td>
                    </tr>
                    <?php 
                        } 
                    } else { ?>
                    <tr><td colspan="5" class="text-center py-4">Belum ada bahan yang dipakai.</td></tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>

</body>
</html>