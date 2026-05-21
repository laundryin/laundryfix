<?php include 'koneksi.php'; ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembelian - Harum Laundry ERP</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; background-color: #fdf2f8; color: #334155; }
        .sidebar { width: 260px; height: 100vh; position: fixed; background: #fff; border-right: 1px solid #fce7f3; z-index: 100; overflow-y: auto;}
        .sidebar-brand { padding: 1.5rem; text-align: center; border-bottom: 1px solid #fce7f3; }
        .sidebar-brand img { max-width: 150px; } 
        .nav-link { color: #64748b; font-weight: 500; padding: 10px 20px; border-radius: 10px; margin: 4px 15px; transition: 0.3s; font-size: 0.9rem;}
        .nav-link:hover, .nav-link.active { background: #fdf2f8; color: #ec4899 !important; border-left: 4px solid #ec4899; }
        .nav-link i { margin-right: 10px; font-size: 1.1rem; }
        .menu-title { font-size: 0.7rem; font-weight: 700; color: #475569; padding: 0 30px; margin-top: 20px; margin-bottom: 5px; text-transform: uppercase;}
        
        .main-content { margin-left: 260px; padding: 40px; }
        .table-custom { background-color: white; border-radius: 20px; overflow: hidden; box-shadow: 0 10px 30px rgba(236, 72, 153, 0.05); }
        .table-custom thead th { background-color: #f8fafc; color: #64748b; padding: 15px; }
        .table-custom td { padding: 15px; vertical-align: middle; border-bottom: 1px solid #f1f5f9;}
        
        /* Tombol Aksi */
        .btn-edit { color: #3b82f6; background: rgba(59, 130, 246, 0.1); border: none; padding: 5px 10px; border-radius: 8px; transition: 0.3s; }
        .btn-edit:hover { background: #3b82f6; color: white; }
        .btn-hapus { color: #ef4444; background: rgba(239, 68, 68, 0.1); border: none; padding: 5px 10px; border-radius: 8px; transition: 0.3s; }
        .btn-hapus:hover { background: #ef4444; color: white; }

        .sidebar::-webkit-scrollbar { width: 5px; }
        .sidebar::-webkit-scrollbar-thumb { background: #fbcfe8; border-radius: 10px; }
    </style>
</head>
<body>

    <?php include 'sidebar.php'; ?>

    <div class="main-content">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="fw-bold mb-0" style="color: #3b82f6;">Riwayat Pembelian</h3>
            <a href="tambah_pembelian.php" class="btn rounded-pill px-4 text-white shadow-sm" style="background: linear-gradient(90deg, #3b82f6, #ec4899); border: none;">
                <i class="bi bi-plus-lg me-2"></i>Tambah Pembelian
            </a>
        </div>

        <div class="table-custom">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>No. Faktur</th>
                        <th>Tanggal</th>
                        <th>Barang yang Dibeli</th>
                        <th>Qty</th>
                        <th>Total Biaya</th>
                        <th>Status</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Ditambahkan p.id_pembelian untuk kebutuhan edit & hapus
                    $query = mysqli_query($conn, "
                        SELECT p.id_pembelian, p.no_faktur_beli, p.tanggal_beli, p.total_biaya, p.status_pembayaran, b.nama_barang, dp.qty 
                        FROM pembelian p 
                        JOIN detail_pembelian dp ON p.id_pembelian = dp.id_pembelian
                        JOIN barang b ON dp.id_barang = b.id_barang
                        ORDER BY p.id_pembelian DESC
                    ");

                    if(mysqli_num_rows($query) > 0) {
                        while($d = mysqli_fetch_array($query)){
                            $badge = $d['status_pembayaran'] == 'Lunas' ? 'text-success' : 'text-danger';
                    ?>
                    <tr>
                        <td class="fw-bold text-primary"><?= htmlspecialchars($d['no_faktur_beli']); ?></td>
                        <td><?= date('d/m/Y H:i', strtotime($d['tanggal_beli'])); ?></td>
                        <td class="fw-medium"><?= htmlspecialchars($d['nama_barang']); ?></td>
                        <td><?= $d['qty']; ?></td>
                        <td class="fw-bold">- Rp <?= number_format($d['total_biaya'], 0, ',', '.'); ?></td>
                        <td class="fw-bold <?= $badge; ?>"><?= htmlspecialchars($d['status_pembayaran']); ?></td>
                        <td class="text-center">
                            <div class="d-flex justify-content-center gap-2">
                                <a href="edit_pembelian.php?id=<?= $d['id_pembelian']; ?>" class="btn-edit" title="Edit">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                                <a href="hapus_pembelian.php?id=<?= $d['id_pembelian']; ?>" class="btn-hapus" title="Hapus" onclick="return confirm('Yakin mau hapus data pembelian ini?')">
                                    <i class="bi bi-trash"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    <?php 
                        } 
                    } else { ?>
                    <tr><td colspan="7" class="text-center py-4">Belum ada riwayat pembelian barang.</td></tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>