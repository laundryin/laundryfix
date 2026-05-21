<?php include 'koneksi.php'; ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Jurnal Umum - DILAUNDRYIN</title>
    <!-- Font Poppins agar sama dengan kodingan sebelumnya -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    
    <style>
        body { 
            font-family: 'Poppins', sans-serif; 
            background-color: white; 
            color: #334155; 
        }
        
        .header-cetak { 
            text-align: center; 
            border-bottom: 2px solid #f1f5f9; 
            padding-bottom: 20px; 
            margin-bottom: 30px; 
        }
        
        /* Judul Biru sesuai kodingan Harum Laundry */
        .title-erp { 
            color: #3b82f6; 
            font-weight: 700; 
            letter-spacing: 1px;
        }

        .table-custom { 
            width: 100%; 
            border-collapse: collapse; 
        }
        
        .table-custom thead th { 
            background-color: #f8fafc !important; 
            color: #64748b; 
            font-weight: 600; 
            padding: 12px; 
            border: 1px solid #e2e8f0; 
            text-align: center;
            font-size: 0.85rem;
        }
        
        .table-custom td { 
            padding: 10px; 
            vertical-align: middle; 
            border: 1px solid #e2e8f0; 
            font-size: 0.85rem;
        }

        .row-header { 
            background-color: #fdf2f8; /* Warna pink muda khas Harum Laundry */
            font-weight: 600; 
        }

        .akun-kredit { 
            padding-left: 30px !important; 
            font-style: italic;
        }

        .angka { 
            text-align: right; 
            font-family: 'Courier New', Courier, monospace; /* Agar angka sejajar rata kanan */
        }

        .text-gradient { 
            background: linear-gradient(90deg, #3b82f6, #ec4899); 
            -webkit-background-clip: text; 
            -webkit-text-fill-color: transparent; 
            font-weight: 700;
        }

        /* Aturan Cetak */
        @media print {
            body { background-color: white; }
            .no-print { display: none !important; }
            .container { width: 100% !important; max-width: 100% !important; }
            @page { margin: 1.5cm; }
        }
    </style>
</head>
<body onload="window.print()"> 

    <div class="container mt-4">
        <div class="header-cetak">
            <h2 class="title-erp mb-0">DILAUNDRYIN</h2>
            <p class="text-muted mb-1">Laporan Jurnal Umum Keuangan</p>
            <div class="badge rounded-pill px-3 py-2" style="background: #f1f5f9; color: #475569; font-weight: 500;">
                <i class="bi bi-calendar3 me-2"></i>Dicetak pada: <?= date('d/m/Y H:i:s'); ?>
            </div>
        </div>

        <table class="table-custom">
            <thead>
                <tr>
                    <th width="12%">Tanggal</th>
                    <th width="45%">Keterangan / Nama Akun</th>
                    <th width="10%">Ref</th>
                    <th width="16%">Debit (Rp)</th>
                    <th width="16%">Kredit (Rp)</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $q_jurnal = mysqli_query($conn, "SELECT * FROM jurnal_umum ORDER BY tanggal_jurnal ASC, id_jurnal ASC");
                if(mysqli_num_rows($q_jurnal) > 0) {
                    while($j = mysqli_fetch_array($q_jurnal)) {
                        $tgl = date('d/m/Y', strtotime($j['tanggal_jurnal']));
                ?>
                        <!-- Baris Keterangan Jurnal -->
                        <tr class="row-header">
                            <td class="text-center text-primary"><?= $tgl; ?></td>
                            <td colspan="4" class="text-dark"><?= htmlspecialchars($j['keterangan']); ?></td>
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
                            $debit_val = $is_debit ? number_format($d['nominal'], 0, ',', '.') : '-';
                            $kredit_val = !$is_debit ? number_format($d['nominal'], 0, ',', '.') : '-';
                        ?>
                        <tr>
                            <td></td>
                            <td class="<?= !$is_debit ? 'akun-kredit' : 'fw-medium'; ?>">
                                <?= htmlspecialchars($d['nama_akun']); ?>
                            </td>
                            <td class="text-center text-muted small"><?= $d['kode_akun']; ?></td>
                            <td class="angka"><?= $debit_val; ?></td>
                            <td class="angka"><?= $kredit_val; ?></td>
                        </tr>
                        <?php } ?>
                <?php 
                    } 
                } else { 
                    echo "<tr><td colspan='5' class='text-center py-4'>Data tidak ditemukan</td></tr>";
                } 
                ?>
            </tbody>
        </table>

        <!-- Bagian Tanda Tangan -->
        <div class="row mt-5">
            <div class="col-8"></div>
            <div class="col-4 text-center">
                <p class="mb-5">Semarang, <?= date('d F Y'); ?></p>
                <p class="mb-0 fw-bold text-decoration-underline">Bagian Keuangan</p>
                <p class="text-muted small">DILAUNDRYIN</p>
            </div>
        </div>
    </div>

    <!-- Tombol Navigasi (Hilang saat diprint) -->
    <div class="text-center no-print mt-5 mb-5">
        <hr class="mx-auto w-50">
        <button class="btn btn-outline-primary rounded-pill px-4 me-2" onclick="window.print()">
            <i class="bi bi-printer me-2"></i>Cetak Ulang
        </button>
        <button class="btn btn-secondary rounded-pill px-4" onclick="window.close()">
            <i class="bi bi-x-circle me-2"></i>Tutup Halaman
        </button>
    </div>

</body>
</html>