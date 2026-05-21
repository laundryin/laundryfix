<?php 
include 'koneksi.php'; 

if(isset($_POST['simpan'])) {
    $tanggal    = $_POST['tanggal_jurnal'];
    $tipe       = mysqli_real_escape_string($conn, $_POST['tipe_transaksi']);
    $keterangan = mysqli_real_escape_string($conn, $_POST['keterangan']);
    
    $akun_debit  = $_POST['akun_debit'];
    $nominal_deb = (int) $_POST['nominal_debit'];
    
    $akun_kredit = $_POST['akun_kredit'];
    $nominal_kre = (int) $_POST['nominal_kredit'];

    // ATURAN EMAS AKUNTANSI: HARUS BALANCE!
    if($nominal_deb !== $nominal_kre) {
        echo "<script>alert('Gagal! Debit dan Kredit nominalnya nggak Balance bos! Cek lagi angkanya.'); window.history.back();</script>";
    } 
    // Cek biar nggak masukin akun yang sama di debit & kredit
    elseif ($akun_debit == $akun_kredit) {
        echo "<script>alert('Akun Debit dan Kredit nggak boleh sama!'); window.history.back();</script>";
    } 
    else {
        // 1. Insert Header Jurnal
        $query_head = "INSERT INTO jurnal_umum (tanggal_jurnal, no_referensi, tipe_transaksi, keterangan, total_transaksi) 
                       VALUES ('$tanggal', NULL, '$tipe', '$keterangan', '$nominal_deb')";
        $insert_head = mysqli_query($conn, $query_head);
        
        if($insert_head) {
            $id_jurnal = mysqli_insert_id($conn);

            // 2. Insert Detail Jurnal (Sisi Debit)
            mysqli_query($conn, "INSERT INTO detail_jurnal (id_jurnal, kode_akun, posisi, nominal) VALUES ('$id_jurnal', '$akun_debit', 'Debit', '$nominal_deb')");
            
            // 3. Insert Detail Jurnal (Sisi Kredit)
            mysqli_query($conn, "INSERT INTO detail_jurnal (id_jurnal, kode_akun, posisi, nominal) VALUES ('$id_jurnal', '$akun_kredit', 'Kredit', '$nominal_kre')");

            echo "<script>alert('Jurnal berhasil dicatat dengan Balance yang sempurna!'); window.location='jurnal.php';</script>";
        } else {
            echo "Gagal insert jurnal: " . mysqli_error($conn);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Input Jurnal - Harum Laundry</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
    <style> body { font-family: 'Poppins', sans-serif; background-color: #fdf2f8; } .card { border-radius: 20px; border: none; } </style>
</head>
<body class="p-4">
    <!-- Nggak perlu include sidebar di sini gapapa, biar layarnya lega buat nginput -->
    <div class="container" style="max-width: 800px;">
        <div class="card p-4 shadow-sm">
            <h3 class="fw-bold mb-4 text-primary">Entry Jurnal Manual</h3>
            <form method="POST">
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Tanggal Transaksi</label>
                        <input type="date" name="tanggal_jurnal" class="form-control rounded-pill" value="<?= date('Y-m-d'); ?>" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Tipe Transaksi</label>
                        <select name="tipe_transaksi" class="form-select rounded-pill" required>
                            <option value="Lainnya">Lainnya (Modal/Prive/dll)</option>
                            <option value="Biaya">Biaya Operasional</option>
                        </select>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label">Keterangan Jurnal</label>
                    <input type="text" name="keterangan" class="form-control rounded-pill" placeholder="Misal: Bayar tagihan listrik bulan ini" required>
                </div>

                <hr class="text-muted mb-4">
                <h6 class="fw-bold text-success mb-3">Posisi DEBIT</h6>
                <div class="row mb-4">
                    <div class="col-md-7">
                        <select name="akun_debit" class="form-select rounded-pill" required>
                            <option value="" disabled selected>-- Pilih Akun Debit --</option>
                            <?php 
                            $q_akun = mysqli_query($conn, "SELECT * FROM akun ORDER BY kode_akun ASC");
                            while($a = mysqli_fetch_array($q_akun)){ echo "<option value='".$a['kode_akun']."'>".$a['kode_akun']." - ".$a['nama_akun']."</option>"; }
                            ?>
                        </select>
                    </div>
                    <div class="col-md-5">
                        <input type="number" name="nominal_debit" class="form-control rounded-pill" placeholder="Rp (Tanpa Titik)" required>
                    </div>
                </div>

                <h6 class="fw-bold text-danger mb-3">Posisi KREDIT</h6>
                <div class="row mb-4">
                    <div class="col-md-7">
                        <select name="akun_kredit" class="form-select rounded-pill" required>
                            <option value="" disabled selected>-- Pilih Akun Kredit --</option>
                            <?php 
                            // Panggil query lagi buat ngisi dropdown kredit
                            $q_akun2 = mysqli_query($conn, "SELECT * FROM akun ORDER BY kode_akun ASC");
                            while($a = mysqli_fetch_array($q_akun2)){ echo "<option value='".$a['kode_akun']."'>".$a['kode_akun']." - ".$a['nama_akun']."</option>"; }
                            ?>
                        </select>
                    </div>
                    <div class="col-md-5">
                        <input type="number" name="nominal_kredit" class="form-control rounded-pill" placeholder="Rp (Tanpa Titik)" required>
                    </div>
                </div>

                <div class="d-flex gap-2 mt-4 justify-content-end">
                    <a href="jurnal.php" class="btn btn-outline-secondary rounded-pill px-4">Batal</a>
                    <button type="submit" name="simpan" class="btn btn-primary rounded-pill px-5 shadow-sm">Simpan Jurnal</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>