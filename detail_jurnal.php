<?php 
include 'koneksi.php'; 
$id_jurnal = $_GET['id'];

// Ambil data header (kepala jurnal)
$q_head = mysqli_query($conn, "SELECT * FROM jurnal_umum WHERE id_jurnal = '$id_jurnal'");
$head = mysqli_fetch_array($q_head);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Detail Jurnal - Harum Laundry ERP</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; background-color: #fdf2f8; color: #334155; }
        .main-content { padding: 40px; max-width: 900px; margin: 0 auto; }
        .card-jurnal { background-color: white; border-radius: 20px; box-shadow: 0 10px 30px rgba(236, 72, 153, 0.05); border: none; }
        .table-jurnal th { background-color: #f8fafc; color: #64748b; border-bottom: 2px solid #f1f5f9; }
        .table-jurnal td { padding: 12px 15px; border-bottom: 1px solid #f1f5f9; }
        .kredit-text { padding-left: 40px !important; font-style: italic; color: #64748b; }
    </style>
</head>
<body>

    <div class="main-content">
        <a href="jurnal.php" class="btn btn-light rounded-pill mb-4 shadow-sm"><i class="bi bi-arrow-left me-2"></i>Kembali ke Buku Jurnal</a>
        
        <div class="card card-jurnal p-5">
            <div class="text-center mb-4">
                <h4 class="fw-bold text-primary mb-1">BUKTI JURNAL UMUM</h4>
                <p class="text-muted mb-0">No. Jurnal: #JU-<?= $head['id_jurnal']; ?> | Ref: <?= $head['no_referensi'] ? $head['no_referensi'] : '-'; ?></p>
            </div>

            <div class="row mb-4">
                <div class="col-md-6">
                    <p class="mb-1"><strong>Tanggal:</strong> <?= date('d F Y', strtotime($head['tanggal_jurnal'])); ?></p>
                    <p class="mb-1"><strong>Tipe:</strong> <?= $head['tipe_transaksi']; ?></p>
                </div>
                <div class="col-md-6 text-md-end">
                    <p class="mb-1"><strong>Keterangan:</strong></p>
                    <p class="mb-0 text-muted"><?= $head['keterangan']; ?></p>
                </div>
            </div>

            <table class="table table-jurnal mb-4">
                <thead>
                    <tr>
                        <th width="15%">Kode Akun</th>
                        <th width="45%">Nama Akun</th>
                        <th width="20%" class="text-end">Debit</th>
                        <th width="20%" class="text-end">Kredit</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $total_debit = 0;
                    $total_kredit = 0;
                    // Query ngambil detail_jurnal di-join sama tabel akun biar dapet namanya
                    $q_detail = mysqli_query($conn, "
                        SELECT dj.*, a.nama_akun 
                        FROM detail_jurnal dj 
                        JOIN akun a ON dj.kode_akun = a.kode_akun 
                        WHERE dj.id_jurnal = '$id_jurnal' 
                        ORDER BY dj.posisi ASC -- Debit di atas, Kredit di bawah
                    ");

                    while($d = mysqli_fetch_array($q_detail)){
                        if($d['posisi'] == 'Debit') {
                            $debit = $d['nominal'];
                            $kredit = 0;
                            $total_debit += $debit;
                            $class_nama = "fw-bold text-dark";
                        } else {
                            $debit = 0;
                            $kredit = $d['nominal'];
                            $total_kredit += $kredit;
                            $class_nama = "kredit-text"; // Dikasi indent menjorok ke kanan ala akuntansi
                        }
                    ?>
                    <tr>
                        <td class="text-primary"><?= $d['kode_akun']; ?></td>
                        <td class="<?= $class_nama; ?>"><?= $d['nama_akun']; ?></td>
                        <td class="text-end"><?= $debit > 0 ? 'Rp '.number_format($debit, 0, ',', '.') : '-'; ?></td>
                        <td class="text-end"><?= $kredit > 0 ? 'Rp '.number_format($kredit, 0, ',', '.') : '-'; ?></td>
                    </tr>
                    <?php } ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="2" class="text-end">TOTAL BALANCE</th>
                        <th class="text-end text-success">Rp <?= number_format($total_debit, 0, ',', '.'); ?></th>
                        <th class="text-end text-success">Rp <?= number_format($total_kredit, 0, ',', '.'); ?></th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

</body>
</html>