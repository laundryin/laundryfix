<?php 
include 'koneksi.php'; 
$id_pesanan = isset($_GET['id']) ? $_GET['id'] : '';

if (!$id_pesanan) { header("location:pesanan.php"); exit; }

// Ambil data kepala nota (header)
$q_head = mysqli_query($conn, "
    SELECT p.*, pel.nama_pelanggan, pel.no_telepon, k.nama_karyawan 
    FROM pesanan p 
    LEFT JOIN pelanggan pel ON p.id_pelanggan = pel.id_pelanggan 
    LEFT JOIN karyawan k ON p.id_karyawan = k.id_karyawan 
    WHERE p.id_pesanan = '$id_pesanan'
");
$head = mysqli_fetch_array($q_head);

$nama_pelanggan = $head['nama_pelanggan'] ? $head['nama_pelanggan'] : 'Walk-in (Self-Service)';
$no_telepon = $head['no_telepon'] ? $head['no_telepon'] : '-';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Nota #<?= $head['no_nota']; ?> - DILAUNDRYIN</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    
    <style>
        body { font-family: 'Poppins', sans-serif; background-color: #fdf2f8; color: #334155; }
        
        /* SIDEBAR TETAP (Sesuai Desain Awal) */
        .sidebar { width: 260px; height: 100vh; position: fixed; background: #fff; border-right: 1px solid #fce7f3; z-index: 100; overflow-y: auto; top: 0; left: 0;}
        .sidebar-brand { padding: 1.5rem; text-align: center; border-bottom: 1px solid #fce7f3; }
        .sidebar-brand img { max-width: 150px; }
        .nav-link { color: #64748b; font-weight: 500; padding: 10px 20px; border-radius: 10px; margin: 4px 15px; transition: 0.3s; font-size: 0.9rem; text-decoration: none; display: block;}
        .nav-link:hover, .nav-link.active { background: #fdf2f8; color: #ec4899 !important; border-left: 4px solid #ec4899; }
        
        /* MAIN CONTENT */
        .main-content { padding: 40px; margin-left: 260px; }
        
        /* NOTA DESIGN (Dibuat Kayak Yang Awal) */
        .nota-card { background: white; border-radius: 20px; box-shadow: 0 10px 30px rgba(236, 72, 153, 0.05); border: 2px dashed #fce7f3; position: relative; }
        .table-nota th { background: #f8fafc; color: #64748b; padding: 15px; border-bottom: 2px solid #f1f5f9; }
        .table-nota td { padding: 15px; border-bottom: 1px solid #f1f5f9; vertical-align: middle; }

        /* KHUSUS PRINT */
        @media print {
            .sidebar, .btn-no-print { display: none !important; }
            .main-content { margin-left: 0 !important; padding: 0 !important; }
            body { background-color: white !important; }
            .nota-card { box-shadow: none !important; border: 1px solid #eee !important; margin-top: 0 !important; max-width: 100% !important; }
            @page { margin: 1cm; }
        }
    </style>
</head>
<body>

    <?php include 'sidebar.php'; ?>

    <div class="main-content">
        <div class="d-flex justify-content-between align-items-center mb-4 btn-no-print">
            <a href="pesanan.php" class="btn btn-light rounded-pill px-4 shadow-sm">
                <i class="bi bi-arrow-left me-2"></i>Kembali
            </a>
            <button onclick="window.print()" class="btn btn-primary rounded-pill px-4 shadow-sm" style="background: linear-gradient(90deg, #3b82f6, #ec4899); border:none;">
                <i class="bi bi-printer me-2"></i>Cetak Sekarang
            </button>
        </div>

        <div class="nota-card p-5 mx-auto" style="max-width: 850px;">
            <div class="text-center mb-5">
                <h2 class="fw-bold mb-0" style="color: #ec4899;">DI<span style="color: #3b82f6;">LAUNDRYIN</span></h2>
                <p class="text-muted">Jl. Tembalang Raya, Semarang | Telp: 0812-XXXX-XXXX</p>
            </div>

            <div class="row mb-4">
                <div class="col-6">
                    <h6 class="fw-bold text-secondary mb-1">DATA PELANGGAN</h6>
                    <p class="mb-0 fw-bold fs-5" style="color: #334155;"><?= htmlspecialchars($nama_pelanggan); ?></p>
                    <p class="text-muted mb-0"><i class="bi bi-telephone me-2"></i><?= htmlspecialchars($no_telepon); ?></p>
                </div>
                <div class="col-6 text-end">
                    <h6 class="fw-bold text-secondary mb-1">DETAIL TRANSAKSI</h6>
                    <p class="mb-0 fw-bold text-primary fs-5">#<?= htmlspecialchars($head['no_nota']); ?></p>
                    <p class="mb-0 text-muted">Tanggal: <?= date('d M Y, H:i', strtotime($head['tanggal_masuk'])); ?></p>
                    <p class="text-muted mb-0">Operator: <?= htmlspecialchars($head['nama_karyawan']); ?></p>
                </div>
            </div>

            <hr style="border-top: 2px solid #f1f5f9; opacity: 1;">

            <div class="row mb-4">
                <div class="col-6">
                    <span class="badge rounded-pill px-3 py-2" style="background-color: #fdf2f8; color: #ec4899; border: 1px solid #fbcfe8;">
                        Tipe: <?= htmlspecialchars($head['jenis_transaksi']); ?>
                    </span>
                </div>
                <div class="col-6 text-end">
                    <span class="badge rounded-pill px-3 py-2 <?= $head['status_pembayaran'] == 'Lunas' ? 'bg-success' : 'bg-danger'; ?>">
                        <?= htmlspecialchars($head['status_pembayaran']); ?>
                    </span>
                </div>
            </div>

            <table class="table table-nota mb-4">
                <thead>
                    <tr>
                        <th>ITEM / LAYANAN</th>
                        <th class="text-center">QTY</th>
                        <th class="text-end">HARGA</th>
                        <th class="text-end">SUBTOTAL</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $q_detail = mysqli_query($conn, "
                        SELECT dp.*, l.nama_layanan, b.nama_barang 
                        FROM detail_pesanan dp 
                        LEFT JOIN tb_layanan l ON dp.id_layanan = l.id_layanan 
                        LEFT JOIN barang b ON dp.id_barang = b.id_barang 
                        WHERE dp.id_pesanan = '$id_pesanan'
                    ");

                    while($d = mysqli_fetch_array($q_detail)){
                        $nama_item = $d['nama_layanan'] ? $d['nama_layanan'] : $d['nama_barang'];
                    ?>
                    <tr>
                        <td class="fw-medium"><?= htmlspecialchars($nama_item); ?></td>
                        <td class="text-center"><?= (float) $d['qty']; ?></td>
                        <td class="text-end">Rp <?= number_format($d['harga_satuan'], 0, ',', '.'); ?></td>
                        <td class="text-end fw-bold text-primary">Rp <?= number_format($d['subtotal'], 0, ',', '.'); ?></td>
                    </tr>
                    <?php } ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="3" class="text-end py-4 fs-5">TOTAL TAGIHAN</th>
                        <th class="text-end py-4 fs-4 fw-bold text-success">Rp <?= number_format($head['total_tagihan'], 0, ',', '.'); ?></th>
                    </tr>
                </tfoot>
            </table>

            <div class="text-center mt-5 text-muted">
                <p class="mb-1 fw-bold">~ Terima Kasih Atas Kepercayaan Anda ~</p>
                <small>Pakaian Bersih, Wangi, dan Rapih adalah Prioritas Kami.</small>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>