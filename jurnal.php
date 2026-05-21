<?php 
include 'koneksi.php'; 

// 1. Ambil parameter filter tanggal jika ada
$tgl_mulai = isset($_GET['tgl_mulai']) ? $_GET['tgl_mulai'] : '';
$tgl_selesai = isset($_GET['tgl_selesai']) ? $_GET['tgl_selesai'] : '';

// Inisialisasi total akhir
$total_debit_akhir = 0;
$total_kredit_akhir = 0;
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jurnal Umum - dilaundryin ERP</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; background-color: #fdf2f8; color: #334155; margin: 0; }
        
        /* --- SIDEBAR AWAL (GLASSMORPHISM & LOGO ONLY) --- */
        .sidebar { 
            width: 260px; height: 100vh; position: fixed; 
            background: rgba(255, 255, 255, 0.8); backdrop-filter: blur(12px); 
            border-right: 1px solid #fce7f3; z-index: 100; overflow-y: auto;
        }

        .nav-link { 
            color: #64748b; font-weight: 500; padding: 12px 20px; border-radius: 12px; 
            margin: 4px 15px; transition: 0.3s; font-size: 0.9rem; display: flex; align-items: center; text-decoration: none;
        }
        .nav-link:hover, .nav-link.active { 
            background: linear-gradient(90deg, rgba(59, 130, 246, 0.1), rgba(236, 72, 153, 0.1)); 
            color: #ec4899 !important; border-left: 4px solid #ec4899; 
        }
        .nav-link i { margin-right: 12px; font-size: 1.2rem; }

        .main-content { margin-left: 260px; padding: 40px; }
        
        /* --- DESAIN TABEL DENGAN PEMBATAS JELAS --- */
        .table-jurnal { 
            background-color: white; border-radius: 20px; overflow: hidden; 
            box-shadow: 0 10px 30px rgba(236, 72, 153, 0.08); 
        }
        .table-jurnal th { 
            background-color: #f8fafc; color: #64748b; font-weight: 600; padding: 20px 15px; 
            border-bottom: 2px solid #f1f5f9; text-transform: uppercase; font-size: 0.8rem; letter-spacing: 0.5px;
        }
        
        .row-header { background-color: #fff9fb; border-top: 2px solid #fce7f3; }
        .row-header td { 
            font-weight: 700; color: #ec4899; font-size: 0.95rem; 
            padding-top: 15px !important; padding-bottom: 15px !important; 
        }
        
        .group-border { border-left: 5px solid #3b82f6; } 

        .akun-debit { font-weight: 500; color: #1e293b; padding-left: 40px !important; }
        .akun-kredit { padding-left: 80px !important; color: #64748b; font-style: italic; }
        
        .angka { font-family: 'Poppins', sans-serif; text-align: right; font-weight: 600; font-size: 0.95rem; }

        .footer-total { background-color: #1e293b; color: white; }
        .footer-total td { font-weight: 700 !important; font-size: 1rem; padding: 20px !important; border: none; }

        /* --- STYLE KHUSUS CETAK --- */
        @media print {
            .sidebar, .no-print { display: none !important; }
            .main-content { margin-left: 0 !important; padding: 0 !important; background-color: white !important; }
            .table-jurnal { box-shadow: none !important; border: 1px solid #eee; border-radius: 0; }
            body { background-color: white !important; }
            .group-border { border-left: 5px solid #3b82f6 !important; -webkit-print-color-adjust: exact; }
            @page { margin: 1.5cm; }
        }

        /* Kop Laporan Cetak */
        .kop-surat { text-align: center; margin-bottom: 30px; border-bottom: 3px double #334155; padding-bottom: 20px; }
        .kop-surat img { height: 80px; width: auto; margin-bottom: 10px; border-radius: 10px; }
        .kop-surat h2 { margin: 0; font-weight: 700; color: #1e293b; }
        .kop-surat p { margin: 0; color: #64748b; font-size: 0.9rem; }
    </style>
</head>
<body>

    <div class="sidebar no-print">
        <?php include 'sidebar.php'; ?>
    </div>

    <div class="main-content">
        
        <div class="d-none d-print-block">
            <div class="kop-surat">
                <img src="londriin.jpeg" alt="Logo">
                <h2>DILAUNDRYIN ERP</h2>
                <p>Laporan Jurnal Umum - Periode: <?= !empty($tgl_mulai) ? $tgl_mulai.' s/d '.$tgl_selesai : date('F Y'); ?></p>
                <p style="font-size: 0.8rem;">Sistem Informasi Laundry Modern & Profesional</p>
            </div>
        </div>

        <div class="d-flex justify-content-between align-items-center mb-4 no-print">
            <div>
                <h3 class="fw-bold mb-0" style="color: #3b82f6;">Buku Jurnal Umum</h3>
                <p class="text-muted small mb-0">Laporan Finansial <strong>DILAUNDRYIN</strong></p>
            </div>
            <div class="d-flex gap-2">
                <a href="tambah_jurnal.php" class="btn rounded-pill px-4 text-white shadow-sm" style="background: linear-gradient(90deg, #3b82f6, #ec4899); border: none;">
                    <i class="bi bi-plus-lg me-2"></i>Jurnal Manual
                </a>
                <button onclick="window.print()" class="btn rounded-pill px-4 shadow-sm text-primary" style="background: white; border: 1px solid #3b82f6;">
                    <i class="bi bi-printer me-2"></i>Cetak Laporan
                </button>
            </div>
        </div>

        <div class="card border-0 shadow-sm rounded-4 mb-4 no-print" style="background: white;">
            <div class="card-body p-3">
                <form method="GET" action="" class="row g-3 align-items-end">
                    <div class="col-md-3">
                        <label class="form-label small fw-bold text-muted">Dari Tanggal</label>
                        <input type="date" name="tgl_mulai" class="form-control rounded-pill border-light shadow-sm" value="<?= $tgl_mulai ?>">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small fw-bold text-muted">Sampai Tanggal</label>
                        <input type="date" name="tgl_selesai" class="form-control rounded-pill border-light shadow-sm" value="<?= $tgl_selesai ?>">
                    </div>
                    <div class="col-md-4">
                        <button type="submit" class="btn btn-primary rounded-pill px-4 shadow-sm">
                            <i class="bi bi-funnel me-1"></i> Filter
                        </button>
                        <a href="jurnal.php" class="btn btn-light rounded-pill px-4 border shadow-sm">Reset</a>
                    </div>
                    <div class="col-md-2 text-end">
                        <a href="export_jurnal.php?tgl_mulai=<?= $tgl_mulai ?>&tgl_selesai=<?= $tgl_selesai ?>" class="btn btn-success rounded-pill px-4 shadow-sm">
                            <i class="bi bi-file-earmark-excel me-1"></i> Excel
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <div class="table-jurnal">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th width="12%" class="text-center">Tanggal</th>
                        <th width="40%" class="text-start">Nama Akun & Keterangan</th>
                        <th width="12%" class="text-center">Ref</th>
                        <th width="18%" class="text-end">Debit</th>
                        <th width="18%" class="text-end">Kredit</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Logika Query dengan Filter
                    $sql = "SELECT * FROM jurnal_umum";
                    if(!empty($tgl_mulai) && !empty($tgl_selesai)){
                        $sql .= " WHERE tanggal_jurnal BETWEEN '$tgl_mulai' AND '$tgl_selesai'";
                    }
                    $sql .= " ORDER BY tanggal_jurnal DESC, id_jurnal DESC";
                    
                    $q_jurnal = mysqli_query($conn, $sql);
                    
                    if(mysqli_num_rows($q_jurnal) > 0) {
                        while($j = mysqli_fetch_array($q_jurnal)) {
                            $tgl = date('d/m/Y', strtotime($j['tanggal_jurnal']));
                            $no_ref = $j['no_referensi'] ? " (#".$j['no_referensi'].")" : "";
                    ?>
                            <tr class="row-header">
                                <td class="text-center group-border"><?= $tgl; ?></td>
                                <td colspan="4">
                                    <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-3 py-2 no-print">
                                        <i class="bi bi-journal-check me-1"></i> <?= htmlspecialchars($j['keterangan']) . $no_ref; ?>
                                    </span>
                                    <strong class="d-none d-print-inline" style="color: #334155;">
                                        <?= htmlspecialchars($j['keterangan']) . $no_ref; ?>
                                    </strong>
                                </td>
                            </tr>
                            
                            <?php
                            $id_jurnal = $j['id_jurnal'];
                            $q_detail = mysqli_query($conn, "
                                SELECT dj.*, a.nama_akun 
                                FROM detail_jurnal dj 
                                JOIN akun a ON dj.kode_akun = a.kode_akun 
                                WHERE dj.id_jurnal = '$id_jurnal' 
                                ORDER BY dj.posisi ASC
                            ");
                            
                            while($d = mysqli_fetch_array($q_detail)) {
                                $is_debit = ($d['posisi'] == 'Debit');
                                $class_akun = $is_debit ? 'akun-debit' : 'akun-kredit';
                                
                                if($is_debit) {
                                    $total_debit_akhir += $d['nominal'];
                                    $debit_v = "Rp " . number_format($d['nominal'], 0, ',', '.');
                                    $kredit_v = "-";
                                } else {
                                    $total_kredit_akhir += $d['nominal'];
                                    $debit_v = "-";
                                    $kredit_v = "Rp " . number_format($d['nominal'], 0, ',', '.');
                                }
                            ?>
                            <tr>
                                <td class="group-border border-0"></td>
                                <td class="<?= $class_akun; ?> border-0"><?= htmlspecialchars($d['nama_akun']); ?></td>
                                <td class="text-center text-muted border-0 small"><?= htmlspecialchars($d['kode_akun']); ?></td>
                                <td class="angka text-primary border-0"><?= $debit_v; ?></td>
                                <td class="angka text-danger border-0"><?= $kredit_v; ?></td>
                            </tr>
                            <?php } ?>
                            <tr><td colspan="5" style="border-bottom: 8px solid #fdf2f8; padding: 0;"></td></tr>
                    <?php 
                        } 
                    ?>
                        <tr class="footer-total">
                            <td colspan="3" class="text-end">TOTAL SALDO AKHIR</td>
                            <td class="angka">Rp <?= number_format($total_debit_akhir, 0, ',', '.'); ?></td>
                            <td class="angka">Rp <?= number_format($total_kredit_akhir, 0, ',', '.'); ?></td>
                        </tr>
                    <?php
                    } else { 
                    ?>
                    <tr><td colspan="5" class="text-center py-5">Belum ada data jurnal untuk periode ini.</td></tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>