<?php 
include 'koneksi.php'; 

$id_pesanan = isset($_GET['id']) ? $_GET['id'] : '';
if (!$id_pesanan) { header("location:pesanan.php"); exit; }

// Ambil data header
$q_head = mysqli_query($conn, "
    SELECT p.*, pel.nama_pelanggan, pel.no_telepon, k.nama_karyawan 
    FROM pesanan p 
    LEFT JOIN pelanggan pel ON p.id_pelanggan = pel.id_pelanggan 
    LEFT JOIN karyawan k ON p.id_karyawan = k.id_karyawan 
    WHERE p.id_pesanan = '$id_pesanan'
");
$head = mysqli_fetch_array($q_head);

$nama_pelanggan = $head['nama_pelanggan'] ? $head['nama_pelanggan'] : 'Walk-in (Self-Service)';
$no_telepon = $head['no_telepon'] ? $head['no_telepon'] : '';

// --- LOGIKA PESAN WHATSAPP DETIL ---
$rincian_wa = "";
$q_det = mysqli_query($conn, "
    SELECT dp.*, l.nama_layanan, b.nama_barang 
    FROM detail_pesanan dp 
    LEFT JOIN tb_layanan l ON dp.id_layanan = l.id_layanan 
    LEFT JOIN barang b ON dp.id_barang = b.id_barang 
    WHERE dp.id_pesanan = '$id_pesanan'
");

while($d = mysqli_fetch_array($q_det)){
    $item = $d['nama_layanan'] ? $d['nama_layanan'] : $d['nama_barang'];
    $rincian_wa .= "- " . $item . " (" . (float)$d['qty'] . "x)%0A";
}

$no_wa = preg_replace('/[^0-9]/', '', $no_telepon);
if (substr($no_wa, 0, 1) === '0') { $no_wa = '62' . substr($no_wa, 1); }

$pesan = "*DILAUNDRYIN - NOTA DIGITAL*%0A";
$pesan .= "------------------------------------%0A";
$pesan .= "Halo *" . $nama_pelanggan . "*,%0A";
$pesan .= "Terima kasih telah mencuci di tempat kami.%0A%0A";
$pesan .= "*RINCIAN PESANAN:*%0A" . $rincian_wa;
$pesan .= "%0A*TOTAL TAGIHAN: Rp " . number_format($head['total_tagihan'], 0, ',', '.') . "*%0A";
$pesan .= "Status: _" . strtoupper($head['status_pembayaran']) . "_%0A";
$pesan .= "------------------------------------%0A";
$pesan .= "Lihat nota lengkap di sini:%0A";
$pesan .= "http://localhost/laundry/cetak_nota.php?id=" . $id_pesanan;
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Detail Pesanan -DILAUNDRYIN</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    
    <style>
        /* CSS SIDEBAR BALIK KE AWAL PERSIS */
        body { font-family: 'Poppins', sans-serif; background-color: #fdf2f8; color: #334155; }
        .sidebar { width: 260px; height: 100vh; position: fixed; background: #fff; border-right: 1px solid #fce7f3; z-index: 100; overflow-y: auto; top: 0; left: 0;}
        .sidebar-brand { padding: 1.5rem; text-align: center; border-bottom: 1px solid #fce7f3; }
        .sidebar-brand img { max-width: 150px; }
        .nav-link { color: #64748b; font-weight: 500; padding: 10px 20px; border-radius: 10px; margin: 4px 15px; transition: 0.3s; font-size: 0.9rem; text-decoration: none; display: block;}
        .nav-link:hover, .nav-link.active { background: #fdf2f8; color: #ec4899 !important; border-left: 4px solid #ec4899; }
        .nav-link i { margin-right: 10px; font-size: 1.1rem; }
        .menu-title { font-size: 0.7rem; font-weight: 700; color: #475569; padding: 0 30px; margin-top: 20px; margin-bottom: 5px; text-transform: uppercase;}
        
        /* MAIN CONTENT */
        .main-content { padding: 40px; margin-left: 260px; }
        .card-custom { background: white; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.03); border: 1px solid #fce7f3; margin-bottom: 20px; padding: 25px; }
        .table-nota th { background: #f8fafc; color: #64748b; border-bottom: 2px solid #f1f5f9; padding: 12px; }
        .table-nota td { padding: 12px; border-bottom: 1px solid #f1f5f9; vertical-align: middle; }
    </style>
</head>
<body>

    <?php include 'sidebar.php'; ?>

    <div class="main-content">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="fw-bold mb-0">Rincian Transaksi</h3>
                <p class="text-muted">Nota #<?= $head['no_nota']; ?></p>
            </div>
            <div class="d-flex gap-2">
                <?php if($no_wa): ?>
                <a href="https://api.whatsapp.com/send?phone=<?= $no_wa; ?>&text=<?= $pesan; ?>" target="_blank" class="btn btn-success rounded-pill px-4 shadow-sm">
                    <i class="bi bi-whatsapp me-2"></i>Kirim WA
                </a>
                <?php endif; ?>
                <a href="cetak_nota.php?id=<?= $id_pesanan; ?>" target="_blank" class="btn btn-primary rounded-pill px-4 shadow-sm" style="background: linear-gradient(90deg, #3b82f6, #ec4899); border:none;">
                    <i class="bi bi-printer me-2"></i>Cetak Nota
                </a>
                <a href="pesanan.php" class="btn btn-light rounded-pill px-4 border shadow-sm">Kembali</a>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4">
                <div class="card-custom">
                    <small class="text-muted fw-bold d-block mb-1">PELANGGAN</small>
                    <h5 class="fw-bold mb-0"><?= htmlspecialchars($nama_pelanggan); ?></h5>
                    <p class="text-muted small mb-0"><?= $no_telepon ?: 'No. Telp Tidak Ada'; ?></p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card-custom">
                    <small class="text-muted fw-bold d-block mb-1">TOTAL BAYAR</small>
                    <h5 class="fw-bold text-primary mb-0">Rp <?= number_format($head['total_tagihan'], 0, ',', '.'); ?></h5>
                    <span class="badge <?= $head['status_pembayaran'] == 'Lunas' ? 'bg-success' : 'bg-danger'; ?>"><?= $head['status_pembayaran']; ?></span>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card-custom">
                    <small class="text-muted fw-bold d-block mb-1">TANGGAL MASUK</small>
                    <h5 class="fw-bold mb-0"><?= date('d/m/Y H:i', strtotime($head['tanggal_masuk'])); ?></h5>
                    <p class="text-muted small mb-0">Kasir: <?= $head['nama_karyawan']; ?></p>
                </div>
            </div>
        </div>

        <div class="card-custom mt-2">
            <h6 class="fw-bold mb-4">Daftar Item / Layanan</h6>
            <div class="table-responsive">
                <table class="table table-nota mb-0">
                    <thead>
                        <tr>
                            <th>Item / Layanan</th>
                            <th class="text-center">Qty</th>
                            <th class="text-end">Harga Satuan</th>
                            <th class="text-end">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        mysqli_data_seek($q_det, 0); // Reset pointer query rincian
                        while($d = mysqli_fetch_array($q_det)){
                            $item_name = $d['nama_layanan'] ? $d['nama_layanan'] : $d['nama_barang'];
                        ?>
                        <tr>
                            <td class="fw-medium"><?= htmlspecialchars($item_name); ?></td>
                            <td class="text-center"><?= (float)$d['qty']; ?></td>
                            <td class="text-end">Rp <?= number_format($d['harga_satuan'], 0, ',', '.'); ?></td>
                            <td class="text-end fw-bold text-primary">Rp <?= number_format($d['subtotal'], 0, ',', '.'); ?></td>
                        </tr>
                        <?php } ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="3" class="text-end py-3">GRAND TOTAL :</th>
                            <th class="text-end py-3 fs-5 text-success">Rp <?= number_format($head['total_tagihan'], 0, ',', '.'); ?></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

</body>
</html>