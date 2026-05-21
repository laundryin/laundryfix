<?php 
include 'koneksi.php'; 

// 1. Ambil parameter filter
$tgl_mulai = isset($_GET['tgl_mulai']) ? $_GET['tgl_mulai'] : '';
$tgl_selesai = isset($_GET['tgl_selesai']) ? $_GET['tgl_selesai'] : '';

// 2. Pengambilan data dengan Filter Logika
$sql = "SELECT r.*, b.nama_barang, b.satuan 
        FROM riwayat_stok r
        JOIN barang b ON r.id_barang = b.id_barang";

if(!empty($tgl_mulai) && !empty($tgl_selesai)){
    $sql .= " WHERE DATE(r.tanggal) BETWEEN '$tgl_mulai' AND '$tgl_selesai'";
}

$sql .= " ORDER BY r.tanggal DESC, r.id_riwayat DESC";
$query = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Riwayat Stok - DILAUNDRYIN</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body { font-family: 'Poppins', sans-serif; background-color: #fdf2f8; color: #334155; }
        
        /* --- SIDEBAR DISAMAKAN DENGAN KODINGAN 2 --- */
        .sidebar { 
            width: 260px; height: 100vh; position: fixed; 
            background: #fff; 
            border-right: 1px solid #fce7f3; z-index: 100; overflow-y: auto; top: 0; left: 0;
        }
        .sidebar-brand { padding: 1.5rem; text-align: center; border-bottom: 1px solid #fce7f3; }
        .sidebar-brand img { max-width: 150px; } 

        .nav-link { 
            color: #64748b; font-weight: 500; padding: 10px 20px; 
            border-radius: 10px; margin: 4px 15px; transition: 0.3s; 
            font-size: 0.9rem; text-decoration: none; display: block;
        }
        .nav-link:hover, .nav-link.active { 
            background: #fdf2f8; 
            color: #ec4899 !important; border-left: 4px solid #ec4899; 
        }
        .nav-link i { margin-right: 10px; font-size: 1.1rem; }
        .menu-title { font-size: 0.7rem; font-weight: 700; color: #475569; padding: 0 30px; margin-top: 20px; margin-bottom: 5px; text-transform: uppercase;}

        .sidebar::-webkit-scrollbar { width: 5px; }
        .sidebar::-webkit-scrollbar-thumb { background: #fbcfe8; border-radius: 10px; }

        /* --- UTAMA & TABEL KODINGAN 1 --- */
        .main-content { margin-left: 260px; padding: 40px; transition: 0.3s; }
        
        .table-custom { 
            background: white; border-radius: 15px; overflow: hidden; 
            box-shadow: 0 10px 30px rgba(236, 72, 153, 0.05); 
        }
        .table-custom thead th { background: #f8fafc; color: #64748b; padding: 15px; border-bottom: 2px solid #f1f5f9; }
        .table-custom td { padding: 12px 15px; border-bottom: 1px solid #f1f5f9; vertical-align: middle; }

        /* --- KOP SURAT PRINT --- */
        .kop-surat { text-align: center; margin-bottom: 30px; border-bottom: 3px double #334155; padding-bottom: 20px; }
        .kop-surat img { height: 70px; margin-bottom: 10px; border-radius: 8px; }

        @media print {
            @page { size: A4 landscape; margin: 1.5cm; }
            body { background: white !important; }
            .sidebar, .btn, .no-print { display: none !important; }
            .main-content { margin-left: 0 !important; padding: 0 !important; }
            .table-custom { box-shadow: none !important; border: 1px solid #000 !important; }
            .table-custom td, .table-custom th { border: 1px solid #ddd !important; }
        }
    </style>
</head>
<body>

    <div class="no-print">
        <?php include 'sidebar.php'; ?>
    </div>

    <div class="main-content">
        
        <div class="d-none d-print-block">
            <div class="kop-surat">
                <img src="londriin.jpeg" alt="Logo">
                <h2 class="fw-bold mb-0">DILAUNDRYIN ERP</h2>
                <h4 class="mb-1">LAPORAN KARTU RIWAYAT STOK</h4>
                <p class="mb-0 small">Periode: <?= !empty($tgl_mulai) ? $tgl_mulai.' s/d '.$tgl_selesai : 'Semua Data'; ?></p>
            </div>
        </div>

        <div class="d-flex justify-content-between align-items-center mb-4 no-print">
            <div>
                <h3 class="fw-bold mb-0" style="color: #3b82f6;">Kartu Riwayat Stok</h3>
                <p class="text-muted small mb-0">Manajemen pergerakan logistik laundry</p>
            </div>
            <div class="d-flex gap-2">
                <button class="btn rounded-pill px-4 text-white shadow-sm" style="background: linear-gradient(90deg, #3b82f6, #ec4899); border: none;" onclick="window.print()">
                    <i class="bi bi-printer me-2"></i>Cetak Laporan
                </button>
            </div>
        </div>

        <div class="card border-0 shadow-sm rounded-4 mb-4 no-print" style="background: white;">
            <div class="card-body p-3">
                <form method="GET" action="" class="row g-3 align-items-end">
                    <div class="col-md-3">
                        <label class="form-label small fw-bold text-muted">Dari Tanggal</label>
                        <input type="date" name="tgl_mulai" class="form-control rounded-pill border-light" value="<?= $tgl_mulai ?>">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small fw-bold text-muted">Sampai Tanggal</label>
                        <input type="date" name="tgl_selesai" class="form-control rounded-pill border-light" value="<?= $tgl_selesai ?>">
                    </div>
                    <div class="col-md-4">
                        <button type="submit" class="btn btn-primary rounded-pill px-4 shadow-sm">
                            <i class="bi bi-filter me-1"></i> Filter
                        </button>
                        <a href="riwayat_stok.php" class="btn btn-light rounded-pill px-4 border">Reset</a>
                    </div>
                    <div class="col-md-2 text-end">
                        <a href="export_stok.php?tgl_mulai=<?= $tgl_mulai ?>&tgl_selesai=<?= $tgl_selesai ?>" class="btn btn-success rounded-pill px-4 shadow-sm">
                            <i class="bi bi-file-earmark-excel me-1"></i> Excel
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <div class="table-custom">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th width="15%">Waktu</th>
                        <th>Barang / Bahan</th>
                        <th width="10%">Jenis</th>
                        <th class="text-center">Masuk</th>
                        <th class="text-center">Keluar</th>
                        <th class="text-center">Saldo</th>
                        <th>Referensi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if($query && mysqli_num_rows($query) > 0): ?>
                        <?php while($d = mysqli_fetch_array($query)): 
                            $is_masuk = ($d['jenis_mutasi'] == 'Masuk');
                        ?>
                            <tr>
                                <td><?= date('d/m/y H:i', strtotime($d['tanggal'])); ?></td>
                                <td class="fw-bold text-primary"><?= htmlspecialchars($d['nama_barang']); ?></td>
                                <td>
                                    <span class="badge border <?= $is_masuk ? 'text-success border-success' : 'text-danger border-danger'; ?>">
                                        <?= $d['jenis_mutasi']; ?>
                                    </span>
                                </td>
                                <td class="text-center text-success">
                                    <?= $is_masuk ? '+ '.$d['qty_mutasi'].' '.$d['satuan'] : '-'; ?>
                                </td>
                                <td class="text-center text-danger">
                                    <?= !$is_masuk ? '- '.$d['qty_mutasi'].' '.$d['satuan'] : '-'; ?>
                                </td>
                                <td class="text-center fw-bold text-dark">
                                    <?= $d['saldo_akhir'].' '.$d['satuan']; ?>
                                </td>
                                <td class="text-muted small">
                                    <?= htmlspecialchars($d['referensi_dokumen']); ?>
                                    <br><small><?= htmlspecialchars($d['keterangan']); ?></small>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="text-center py-5">Data tidak ditemukan untuk periode ini.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</body>
</html>