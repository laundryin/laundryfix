<?php 
// 1. PROTEKSI HALAMAN
session_start();
if($_SESSION['status'] != "login"){
    header("location:login.php?pesan=belum_login");
    exit;
}

include 'koneksi.php'; 

// --- LOGIKA DASHBOARD ---
$q_masuk = mysqli_query($conn, "SELECT COUNT(*) as total FROM pesanan WHERE status_cucian = 'Baru' AND DATE(tanggal_masuk) = CURDATE()");
$order_masuk = mysqli_fetch_assoc($q_masuk)['total'];

$q_proses = mysqli_query($conn, "SELECT COUNT(*) as total FROM pesanan WHERE status_cucian = 'Proses'");
$cucian_proses = mysqli_fetch_assoc($q_proses)['total'];

$q_selesai = mysqli_query($conn, "SELECT COUNT(*) as total FROM pesanan WHERE status_cucian = 'Selesai'");
$selesai = mysqli_fetch_assoc($q_selesai)['total'];

// Tetap Omzet Harian (Murni Hari Ini)
$q_omzet = mysqli_query($conn, "SELECT SUM(total_tagihan) as total FROM pesanan WHERE status_pembayaran = 'Lunas' AND DATE(tanggal_masuk) = CURDATE()");
$res_omzet = mysqli_fetch_assoc($q_omzet)['total'];
$omzet_harian = $res_omzet ? $res_omzet : 0; 

// Kueri Tambahan: Menghitung Akumulasi Omzet Bulan Ini untuk Box di Samping Grafik
$q_omzet_bulan = mysqli_query($conn, "SELECT SUM(total_tagihan) as total FROM pesanan WHERE status_pembayaran = 'Lunas' AND DATE_FORMAT(tanggal_masuk, '%Y-%m') = DATE_FORMAT(CURDATE(), '%Y-%m')");
$res_omzet_bulan = mysqli_fetch_assoc($q_omzet_bulan)['total'];
$omzet_bulanan = $res_omzet_bulan ? $res_omzet_bulan : 0;

// --- LOGIKA ANALITIK GRAFIK (BULAN BERJALAN LENGKAP) ---
$label_grafik = [];
$data_grafik = [];

$jumlah_hari = date('t'); 
$bulan_tahun_sekarang = date('Y-m');

for ($i = 1; $i <= $jumlah_hari; $i++) {
    $tgl_target = $bulan_tahun_sekarang . '-' . sprintf('%02d', $i);
    $label_grafik[] = $i; 
    
    $q_jual = mysqli_query($conn, "SELECT SUM(total_tagihan) as total FROM pesanan WHERE DATE(tanggal_masuk) = '$tgl_target' AND status_pembayaran = 'Lunas'");
    $res_jual = mysqli_fetch_assoc($q_jual);
    $data_grafik[] = $res_jual['total'] ? (int)$res_jual['total'] : 0;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - DILAUNDRYIN</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    
    <style>
        body { font-family: 'Poppins', sans-serif; background-color: #fdf2f8; color: #334155; margin: 0; }
        
        /* --- SIDEBAR REVISI (SAMA DENGAN KARYAWAN) --- */
        .sidebar { 
            width: 260px; height: 100vh; position: fixed; 
            background: rgba(255, 255, 255, 0.8); backdrop-filter: blur(10px); 
            border-right: 1px solid #fce7f3; z-index: 100; 
            overflow-y: auto;
            -ms-overflow-style: none; scrollbar-width: none; 
        }
        .sidebar::-webkit-scrollbar { display: none; }

        .sidebar-brand { 
            padding: 1.5rem; text-align: center; border-bottom: 1px solid #fce7f3; 
        }
        .sidebar-brand img { max-width: 160px; height: auto; border-radius: 10px; }

        .nav-link { 
            color: #64748b; font-weight: 500; padding: 10px 20px; border-radius: 10px; 
            margin: 4px 15px; transition: 0.3s; font-size: 0.9rem; display: flex; align-items: center; text-decoration: none;
        }
        .nav-link:hover, .nav-link.active { 
            background: linear-gradient(90deg, rgba(59, 130, 246, 0.1), rgba(236, 72, 153, 0.1)); 
            color: #ec4899 !important; border-left: 4px solid #ec4899; 
        }
        .nav-link i { margin-right: 10px; font-size: 1.1rem; }

        /* --- KONTEN UTAMA --- */
        .main-content { margin-left: 260px; padding: 40px; min-height: 100vh; }
        
        .card-stat { 
            background: white; border-radius: 20px; padding: 20px; border: none; 
            box-shadow: 0 10px 30px rgba(236, 72, 153, 0.05); transition: 0.3s;
        }
        .card-stat:hover { transform: translateY(-5px); }
        
        .icon-box {
            width: 45px; height: 45px; border-radius: 12px;
            display: flex; align-items: center; justify-content: center; font-size: 1.2rem;
        }

        .table-custom { background-color: white; border-radius: 20px; overflow: hidden; box-shadow: 0 10px 30px rgba(236, 72, 153, 0.05); }
        .text-gradient { background: linear-gradient(90deg, #3b82f6, #ec4899); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
    </style>
</head>
<body>

    <div class="sidebar">
        <?php include 'sidebar.php'; ?>
    </div>

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

        <div class="row g-4 mb-4">
            <div class="col-md-3">
                <div class="card-stat">
                    <div class="d-flex justify-content-between">
                        <div>
                            <p class="text-secondary mb-1" style="font-size: 0.85rem;">Order Baru</p>
                            <h3 class="fw-bold mb-0 text-primary"><?= $order_masuk; ?></h3>
                        </div>
                        <div class="icon-box bg-primary bg-opacity-10 text-primary"><i class="bi bi-basket2"></i></div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card-stat">
                    <div class="d-flex justify-content-between">
                        <div>
                            <p class="text-secondary mb-1" style="font-size: 0.85rem;">Proses Cuci</p>
                            <h3 class="fw-bold mb-0 text-warning"><?= $cucian_proses; ?></h3>
                        </div>
                        <div class="icon-box bg-warning bg-opacity-10 text-warning"><i class="bi bi-hourglass-split"></i></div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card-stat">
                    <div class="d-flex justify-content-between">
                        <div>
                            <p class="text-secondary mb-1" style="font-size: 0.85rem;">Selesai</p>
                            <h3 class="fw-bold mb-0 text-success"><?= $selesai; ?></h3>
                        </div>
                        <div class="icon-box bg-success bg-opacity-10 text-success"><i class="bi bi-check2-circle"></i></div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card-stat">
                    <div class="d-flex justify-content-between">
                        <div>
                            <p class="text-secondary mb-1" style="font-size: 0.85rem;">Omzet Harian</p>
                            <h5 class="fw-bold mb-0 text-gradient">Rp <?= number_format($omzet_harian, 0, ',', '.'); ?></h5>
                        </div>
                        <div class="icon-box bg-purple bg-opacity-10" style="color: #a855f7; background: #f3e8ff;"><i class="bi bi-wallet2"></i></div>
                    </div>
                </div>
            </div>
        </div>

        <h5 class="fw-bold mb-3 text-secondary">Transaksi Terakhir </h5>
        <div class="table-custom mb-5">
            <table class="table table-hover mb-0 w-100">
                <thead>
                    <tr>
                        <th width="15%">No. Nota</th>
                        <th>Tanggal Masuk</th>
                        <th>Status Cucian</th>
                        <th>Status Bayar</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $q_transaksi = mysqli_query($conn, "SELECT * FROM pesanan WHERE status_cucian != 'Diambil' ORDER BY id_pesanan DESC LIMIT 5");
                    while($t = mysqli_fetch_array($q_transaksi)){
                        $badge_cucian = $t['status_cucian'] == 'Proses' ? 'bg-warning text-dark' : ($t['status_cucian'] == 'Selesai' ? 'bg-success' : 'bg-secondary');
                        $badge_bayar = $t['status_pembayaran'] == 'Lunas' ? 'text-success' : 'text-danger';
                    ?>
                    <tr>
                        <td class="fw-bold text-primary"><?= $t['no_nota']; ?></td>
                        <td><?= date('d/m/Y H:i', strtotime($t['tanggal_masuk'])); ?></td>
                        <td><span class="badge rounded-pill <?= $badge_cucian; ?>"><?= $t['status_cucian']; ?></span></td>
                        <td class="fw-bold <?= $badge_bayar; ?>"><?= $t['status_pembayaran']; ?></td>
                        <td class="fw-bold">Rp <?= number_format($t['total_tagihan'], 0, ',', '.'); ?></td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>

        <div class="row g-4 mb-4">
            <div class="col-lg-9">
                <div class="card border-0 shadow-sm p-4 h-100" style="border-radius: 20px; background: white;">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="fw-bold mb-0 text-secondary"><i class="bi bi-graph-up-arrow me-2" style="color: #ec4899;"></i>Tren Pendapatan Lunas</h5>
                        <span class="badge bg-light text-dark rounded-pill px-3">Bulan Ini (<?= date('F Y'); ?>)</span>
                    </div>
                    <div style="height: 300px;">
                        <canvas id="salesChart"></canvas>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-3">
                <div class="card border-0 shadow-sm p-4 h-100 d-flex flex-column justify-content-center align-items-center text-center" style="border-radius: 20px; background: white; border-left: 5px solid #ec4899 !important;">
                    <div class="icon-box mb-3 shadow-sm" style="color: #ec4899; background: #fce7f3; width: 60px; height: 60px; font-size: 1.6rem; border-radius: 15px;">
                        <i class="bi bi-cash-coin"></i>
                    </div>
                    <p class="text-secondary fw-medium mb-1" style="font-size: 0.9rem;">Total Omzet Bulan Ini</p>
                    <h4 class="fw-bold text-gradient mb-2">Rp <?= number_format($omzet_bulanan, 0, ',', '.'); ?></h4>
                    <span class="small text-muted" style="font-size: 0.75rem;">Akumulasi otomatis dari seluruh transaksi lunas</span>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('salesChart').getContext('2d');
        let gradient = ctx.createLinearGradient(0, 0, 0, 400);
        gradient.addColorStop(0, 'rgba(236, 72, 153, 0.3)');
        gradient.addColorStop(1, 'rgba(236, 72, 153, 0)');

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: <?= json_encode($label_grafik); ?>,
                datasets: [{
                    label: 'Pendapatan Tanggal',
                    data: <?= json_encode($data_grafik); ?>,
                    borderColor: '#ec4899',
                    borderWidth: 3,
                    backgroundColor: gradient,
                    fill: true,
                    tension: 0.4
                }]
            },
            options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } } }
        });
    </script>
</body>
</html>