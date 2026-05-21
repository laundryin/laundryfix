<?php include 'koneksi.php'; ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Pesanan / Kasir - DILAUNDRYIN ERP</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    
    <style>
        body { font-family: 'Poppins', sans-serif; background-color: #fdf2f8; color: #334155; }
        
        /* CSS SIDEBAR - PENTING BIAR GAK BERANTAKAN */
        .sidebar { width: 260px; height: 100vh; position: fixed; background: #fff; border-right: 1px solid #fce7f3; z-index: 100; overflow-y: auto; top: 0; left: 0;}
        .sidebar-brand { padding: 1.5rem; text-align: center; border-bottom: 1px solid #fce7f3; }
        .nav-link { color: #64748b; font-weight: 500; padding: 10px 20px; border-radius: 10px; margin: 4px 15px; transition: 0.3s; font-size: 0.9rem; text-decoration: none; display: block;}
        .nav-link:hover, .nav-link.active { background: #fdf2f8; color: #ec4899 !important; border-left: 4px solid #ec4899; }
        .nav-link i { margin-right: 10px; font-size: 1.1rem; }
        .menu-title { font-size: 0.7rem; font-weight: 700; color: #475569; padding: 0 30px; margin-top: 20px; margin-bottom: 5px; text-transform: uppercase;}
        
        /* CSS MAIN CONTENT */
        .main-content { margin-left: 260px; padding: 40px; }
        .table-custom { background-color: white; border-radius: 20px; overflow: hidden; box-shadow: 0 10px 30px rgba(236, 72, 153, 0.05); }
        .table-custom thead th { background-color: #f8fafc; color: #64748b; padding: 15px; border-bottom: 2px solid #f1f5f9; }
        .table-custom td { padding: 15px; vertical-align: middle; border-bottom: 1px solid #f1f5f9; }
    </style>
</head>
<body>

    <?php include 'sidebar.php'; ?>

    <div class="main-content">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="fw-bold mb-0" style="color: #3b82f6;"><i class="bi bi-cart-check me-2"></i>Data Pesanan / Kasir</h3>
                <p class="text-muted mb-0" style="font-size: 0.9rem;">Monitoring antrean cucian Harum Laundry</p>
            </div>
            <a href="tambah_pesanan.php" class="btn rounded-pill px-4 text-white shadow-sm" style="background: linear-gradient(90deg, #3b82f6, #ec4899); border: none;">
                <i class="bi bi-plus-lg me-2"></i>Buat Pesanan Baru
            </a>
        </div>

        <div class="table-custom">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>No. Nota</th>
                        <th>Tgl Masuk</th>
                        <th>Pelanggan</th>
                        <th>Status Cucian</th>
                        <th>Pembayaran</th>
                        <th class="text-end">Total</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Pastikan koneksi.php sudah mengarah ke db_dilaundryin
                    $query = mysqli_query($conn, "
                        SELECT p.*, pel.nama_pelanggan 
                        FROM pesanan p 
                        LEFT JOIN pelanggan pel ON p.id_pelanggan = pel.id_pelanggan 
                        ORDER BY p.id_pesanan DESC
                    ");

                    if(mysqli_num_rows($query) > 0) {
                        while($d = mysqli_fetch_array($query)){
                            // Logika Warna Status
                            $cucian_class = ($d['status_cucian'] == 'Proses') ? 'bg-warning text-dark' : 'bg-success text-white';
                            $bayar_class = ($d['status_pembayaran'] == 'Lunas') ? 'text-success' : 'text-danger';
                            $pelanggan = ($d['nama_pelanggan']) ? htmlspecialchars($d['nama_pelanggan']) : '<i class="text-muted">Self-Service</i>';
                    ?>
                    <tr>
                        <td class="fw-bold text-primary"><?= htmlspecialchars($d['no_nota']); ?></td>
                        <td><?= date('d/m/y H:i', strtotime($d['tanggal_masuk'])); ?></td>
                        <td><?= $pelanggan; ?></td>
                        <td><span class="badge rounded-pill <?= $cucian_class; ?>"><?= htmlspecialchars($d['status_cucian']); ?></span></td>
                        <td class="fw-bold <?= $bayar_class; ?>"><?= htmlspecialchars($d['status_pembayaran']); ?></td>
                        <td class="text-end fw-bold">Rp <?= number_format($d['total_tagihan'], 0, ',', '.'); ?></td>
                        <td class="text-center">
                            <a href="detail_pesanan.php?id=<?= $d['id_pesanan']; ?>" class="btn btn-sm btn-outline-primary rounded-pill px-3 mb-1">Detail</a>
                            <a href="edit_pesanan.php?id=<?= $d['id_pesanan']; ?>" class="btn btn-sm btn-success rounded-pill px-3 mb-1">Update Status</a>
                        </td>
                    </tr>
                    <?php 
                        } 
                    } else { ?>
                    <tr><td colspan="7" class="text-center py-4 text-muted">Belum ada data pesanan hari ini.</td></tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>

</body>
</html>