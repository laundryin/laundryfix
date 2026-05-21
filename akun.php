<?php 
include 'koneksi.php'; 

// =========================================================================
// PAKSA UPDATE KATEGORI COA LAUNDRY LENGKAP (Disesuaikan dengan Nama Klasifikasi Akuntansi) bilaaaaaa
// =========================================================================
$coa_laundry = [
    // 1. ASET (Disamakan menjadi 'Aset' agar terbaca sempurna)
    ['1110', 'Aset'], ['1111', 'Aset'], ['1120', 'Aset'], 
    ['1121', 'Aset'], ['1130', 'Aset'], ['1140', 'Aset'], 
    ['1141', 'Aset'], ['1150', 'Aset'], ['1160', 'Aset'], 
    ['1161', 'Aset'], ['1162', 'Aset'], ['1170', 'Aset'], 
    ['1210', 'Aset'], ['1211', 'Aset'], ['1220', 'Aset'],  
    ['1221', 'Aset'], ['1230', 'Aset'], ['1231', 'Aset'],  
    ['1240', 'Aset'], ['1241', 'Aset'], ['1250', 'Aset'],  
    ['1251', 'Aset'], ['1260', 'Aset'], ['1261', 'Aset'],  
    
    // 2. KEWAJIBAN
    ['2110', 'Kewajiban'], ['2120', 'Kewajiban'], ['2130', 'Kewajiban'], 
    ['2140', 'Kewajiban'], ['2150', 'Kewajiban'], ['2210', 'Kewajiban'], 
    
    // 3. EKUITAS
    ['3110', 'Ekuitas'], ['3120', 'Ekuitas'], ['3130', 'Ekuitas'], ['3140', 'Ekuitas'], 
    
    // 4. PENDAPATAN
    ['4110', 'Pendapatan'], ['4111', 'Pendapatan'], ['4120', 'Pendapatan'], 
    ['4130', 'Pendapatan'], ['4131', 'Pendapatan'], ['4132', 'Pendapatan'], 
    ['4140', 'Pendapatan'], ['4210', 'Pendapatan'], ['4290', 'Pendapatan'], 
    
    // 5. BEBAN
    ['5110', 'Beban'], ['5111', 'Beban'], ['5112', 'Beban'], ['5120', 'Beban'], 
    ['5210', 'Beban'], ['5211', 'Beban'], ['5212', 'Beban'], ['5220', 'Beban'], 
    ['5240', 'Beban'], ['5310', 'Beban'], ['5320', 'Beban'], ['5330', 'Beban'], 
    ['5340', 'Beban'], ['5350', 'Beban'], ['5410', 'Beban'], ['5412', 'Beban'], 
    ['5430', 'Beban'], ['5510', 'Beban'], ['5610', 'Beban'], ['5620', 'Beban'], 
    ['5630', 'Beban'], ['5640', 'Beban'], ['5699', 'Beban']
];

// Cek nama kolom asli di database kamu secara dinamis
$cek_kolom = mysqli_query($conn, "SHOW COLUMNS FROM akun LIKE 'kategori_akun'");
$nama_kolom = (mysqli_num_rows($cek_kolom) > 0) ? 'kategori_akun' : 'kategori';

// Jalankan update paksa ke database untuk mengisi kategori yang kosong
foreach ($coa_laundry as $val) {
    $kd = $val[0];
    $kat = $val[1];
    mysqli_query($conn, "UPDATE akun SET $nama_kolom = '$kat' WHERE kode_akun = '$kd'");
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Master Akun - Harum Laundry ERP</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
    body { font-family: 'Poppins', sans-serif; background-color: #fdf2f8; color: #334155; }
    
    .sidebar { width: 260px; height: 100vh; position: fixed; background: #fff; border-right: 1px solid #fce7f3; z-index: 100; overflow-y: auto; top: 0; left: 0;}
    .sidebar-brand { padding: 1.5rem; text-align: center; border-bottom: 1px solid #fce7f3; }
    .nav-link { color: #64748b; font-weight: 500; padding: 10px 20px; border-radius: 10px; margin: 4px 15px; transition: 0.3s; font-size: 0.9rem; text-decoration: none; display: block;}
    .nav-link:hover, .nav-link.active { background: #fdf2f8; color: #ec4899 !important; border-left: 4px solid #ec4899; }
    .nav-link i { margin-right: 10px; font-size: 1.1rem; }
    
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
                <h3 class="fw-bold mb-0" style="color: #3b82f6;"><i class="bi bi-journal-text me-2"></i>Chart of Accounts</h3>
                <p class="text-muted mb-0" style="font-size: 0.9rem;">Saldo otomatis terhitung dari Jurnal Umum</p>
            </div>
            <a href="tambah_akun.php" class="btn rounded-pill px-4 text-white shadow-sm" style="background: linear-gradient(90deg, #3b82f6, #ec4899); border: none;">
                <i class="bi bi-plus-lg me-2"></i>Tambah Akun
            </a>
        </div>

        <div class="table-custom">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Kode Akun</th>
                        <th>Nama Akun</th>
                        <th>Kategori</th>
                        <th>Saldo Normal</th>
                        <th class="text-end">Saldo Berjalan</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $query = mysqli_query($conn, "SELECT * FROM akun ORDER BY kode_akun ASC");
                    if(mysqli_num_rows($query) > 0) {
                        while($d = mysqli_fetch_array($query)){
                            $kode = $d['kode_akun'];
                            
                            // --- LOGIKA OTOMATIS: Hitung saldo dari tabel detail_jurnal ---
                            $q_saldo = mysqli_query($conn, "SELECT 
                                        SUM(CASE WHEN posisi = 'Debit' THEN nominal ELSE 0 END) AS total_debit,
                                        SUM(CASE WHEN posisi = 'Kredit' THEN nominal ELSE 0 END) AS total_kredit 
                                        FROM detail_jurnal WHERE kode_akun = '$kode'");
                            $s = mysqli_fetch_array($q_saldo);
                            
                            // Tentukan rumus berdasarkan Saldo Normal Akun
                            if($d['saldo_normal'] == 'Debit') {
                                $saldo_akhir = $s['total_debit'] - $s['total_kredit'];
                            } else {
                                $saldo_akhir = $s['total_kredit'] - $s['total_debit'];
                            }

                            $badge_saldo = $d['saldo_normal'] == 'Debit' ? 'bg-primary' : 'bg-warning text-dark';
                    ?>
                    <tr>
                        <td class="fw-bold text-primary"><?= htmlspecialchars($d['kode_akun']); ?></td>
                        <td class="fw-medium"><?= htmlspecialchars($d['nama_akun']); ?></td>
                        
                        <td><?= htmlspecialchars($d[$nama_kolom] ?? '-'); ?></td>
                        
                        <td><span class="badge <?= $badge_saldo; ?>"><?= htmlspecialchars($d['saldo_normal']); ?></span></td>
                        <td class="text-end fw-bold <?= $saldo_akhir < 0 ? 'text-danger' : ''; ?>">
                            Rp <?= number_format($saldo_akhir, 0, ',', '.'); ?>
                        </td>
                        <td class="text-center">
                            <a href="edit_akun.php?id=<?= $d['kode_akun']; ?>" class="btn btn-sm btn-outline-primary rounded-pill px-3">Edit</a>
                            <a href="hapus_akun.php?id=<?= $d['kode_akun']; ?>" class="btn btn-sm btn-outline-danger rounded-pill px-3" onclick="return confirm('Yakin mau hapus akun ini?')">Hapus</a>
                        </td>
                    </tr>
                    <?php 
                        } 
                    } else { ?>
                    <tr><td colspan="6" class="text-center py-4 text-muted">Belum ada akun.</td></tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>

</body>
</html>