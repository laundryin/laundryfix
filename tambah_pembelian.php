<?php 
include 'koneksi.php'; 

if(isset($_POST['simpan'])) {
    $no_faktur  = "INV-" . date('YmdHis');
    $tanggal      = $_POST['tanggal_beli'];
    $id_supplier  = $_POST['id_supplier'];
    $id_karyawan  = $_POST['id_karyawan'];
    $id_barang    = $_POST['id_barang'];
    $qty          = (float) $_POST['qty'];
    $harga_beli   = (int) $_POST['harga_beli_satuan'];
    $subtotal     = $qty * $harga_beli;

    $sql_beli = "INSERT INTO pembelian (no_faktur_beli, id_supplier, id_karyawan, tanggal_beli, total_biaya, status_pembayaran) 
                 VALUES ('$no_faktur', '$id_supplier', '$id_karyawan', '$tanggal', '$subtotal', 'Lunas')";
    $insert_beli = mysqli_query($conn, $sql_beli);
    
    if($insert_beli) {
        $id_pembelian = mysqli_insert_id($conn);

        // Insert Detail Pembelian
        mysqli_query($conn, "INSERT INTO detail_pembelian (id_pembelian, id_barang, qty, harga_beli_satuan, subtotal) VALUES ('$id_pembelian', '$id_barang', '$qty', '$harga_beli', '$subtotal')");

        // Update Stok
        mysqli_query($conn, "UPDATE barang SET stok = stok + $qty, harga_beli = '$harga_beli' WHERE id_barang = '$id_barang'");

        // Mutasi Stok
        $ket_stok = "Pembelian (Faktur: $no_faktur)";
        mysqli_query($conn, "INSERT INTO riwayat_stok (id_barang, tanggal, jenis_mutasi, qty_mutasi, referensi_dokumen, keterangan, saldo_akhir) VALUES ('$id_barang', '$tanggal', 'Masuk', '$qty', '$no_faktur', '$ket_stok', (SELECT stok FROM barang WHERE id_barang='$id_barang'))");

        // ==========================================
        // JURNAL OTOMATIS: PEMBELIAN PERSEDIAAN
        // ==========================================
        $ket_jurnal = "Pembelian Persediaan (Faktur: $no_faktur)";
        mysqli_query($conn, "INSERT INTO jurnal_umum (tanggal_jurnal, no_referensi, tipe_transaksi, keterangan, total_transaksi) VALUES ('$tanggal', '$no_faktur', 'Pembelian', '$ket_jurnal', '$subtotal')");
        $id_jurnal = mysqli_insert_id($conn);
        
        // Debit: 1130 Persediaan Barang
        mysqli_query($conn, "INSERT INTO detail_jurnal (id_jurnal, kode_akun, posisi, nominal) VALUES ('$id_jurnal', '1130', 'Debit', '$subtotal')");
        // Kredit: 1110 Kas di Tangan (Asumsi default bayar cash Lunas)
        mysqli_query($conn, "INSERT INTO detail_jurnal (id_jurnal, kode_akun, posisi, nominal) VALUES ('$id_jurnal', '1110', 'Kredit', '$subtotal')");

        echo "<script>alert('Sip! Barang masuk gudang & Jurnal otomatis tercatat.'); window.location='pembelian.php';</script>";
    } else {
        echo "Gagal: " . mysqli_error($conn);
    }
}
?><!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Input Belanja - Harum Laundry</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
    <style> body { font-family: 'Poppins', sans-serif; background-color: #fdf2f8; } .card { border-radius: 20px; border: none; } </style>
</head>
<body class="p-5">
    <div class="container" style="max-width: 800px;">
        <div class="card p-4 shadow-sm">
            <h3 class="fw-bold mb-4 text-primary">Input Kulakan Baru</h3>
            <form method="POST">
                <div class="row">
                    <!-- Kolom Kiri: Info Nota -->
                    <div class="col-md-6 border-end pe-4">
                        <h6 class="fw-bold text-secondary mb-3">Informasi Faktur</h6>
                        
                        <div class="mb-3">
                            <label class="form-label">Tanggal Belanja</label>
                            <input type="datetime-local" name="tanggal_beli" class="form-control rounded-pill" value="<?= date('Y-m-d\TH:i'); ?>" required>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Karyawan (Yang Beli)</label>
                            <select name="id_karyawan" class="form-select rounded-pill" required>
                                <option value="" disabled selected>Pilih Karyawan...</option>
                                <?php 
                                $qk = mysqli_query($conn, "SELECT * FROM karyawan");
                                while($k = mysqli_fetch_array($qk)){ echo "<option value='{$k['id_karyawan']}'>{$k['nama_karyawan']}</option>"; }
                                ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Supplier (Toko)</label>
                            <select name="id_supplier" class="form-select rounded-pill" required>
                                <option value="" disabled selected>Pilih Toko/Supplier...</option>
                                <?php 
                                $qs = mysqli_query($conn, "SELECT * FROM supplier");
                                while($s = mysqli_fetch_array($qs)){ echo "<option value='{$s['id_supplier']}'>{$s['nama_supplier']}</option>"; }
                                ?>
                            </select>
                        </div>
                    </div>

                    <!-- Kolom Kanan: Barang -->
                    <div class="col-md-6 ps-4">
                        <h6 class="fw-bold text-secondary mb-3">Detail Barang Masuk</h6>
                        
                        <div class="mb-3">
                            <label class="form-label">Pilih Barang yang Dibeli</label>
                            <select name="id_barang" class="form-select rounded-pill" required>
                                <option value="" disabled selected>-- Pilih Barang Gudang --</option>
                                <?php 
                                $q_brg = mysqli_query($conn, "SELECT * FROM barang ORDER BY nama_barang ASC");
                                // Variabel array-nya udah gw sesuain jadi 'stok' doang
                                while($brg = mysqli_fetch_array($q_brg)){
                                    echo "<option value='".$brg['id_barang']."'>".$brg['nama_barang']." (Stok: ".$brg['stok'].")</option>";
                                }
                                ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Jumlah / Qty Beli</label>
                            <input type="number" step="0.01" name="qty" class="form-control rounded-pill" required>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Harga Beli Satuan (Rp)</label>
                            <input type="number" name="harga_beli_satuan" class="form-control rounded-pill" placeholder="Contoh: 15000" required>
                        </div>

                        <div class="d-flex gap-2 mt-4">
                            <button type="submit" name="simpan" class="btn btn-primary rounded-pill px-4 w-100" style="background: linear-gradient(90deg, #3b82f6, #ec4899); border:none;">Simpan & Masuk Gudang</button>
                        </div>
                        <div class="text-center mt-2">
                            <a href="pembelian.php" class="btn btn-link text-secondary text-decoration-none small">Kembali ke Daftar</a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</body>
</html>