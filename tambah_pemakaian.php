<?php 
include 'koneksi.php'; 

if(isset($_POST['simpan'])) {
    $tanggal_pakai = $_POST['tanggal_pakai'];
    
    // Bikin nomor referensi otomatis dari tanggal buat di jurnal & riwayat
    // Format: REKAP-20260512-1430
    $no_rekap = 'REKAP-' . date('Ymd-Hi', strtotime($tanggal_pakai));
    
    $id_barang_arr   = $_POST['id_barang'];
    $qty_dipakai_arr = $_POST['qty_dipakai'];

    $total_nilai_pemakaian = 0; 
    $pesan_error = "";
    $ada_yang_berhasil = false;

    foreach($id_barang_arr as $index => $id_barang) {
        $qty_dipakai = (float) $qty_dipakai_arr[$index];

        if(empty($id_barang) || $qty_dipakai <= 0) continue;

        // Cek sisa stok
        $cek_stok = mysqli_query($conn, "SELECT stok, nama_barang, harga_beli FROM barang WHERE id_barang = '$id_barang'");
        $dt_stok = mysqli_fetch_assoc($cek_stok);

        if($dt_stok['stok'] < $qty_dipakai) {
            $pesan_error .= "Stok ".$dt_stok['nama_barang']." sisa ".$dt_stok['stok'].", lu minta ".$qty_dipakai."! Batal masuk. \\n";
        } else {
            // 1. Simpan ke tabel pemakaian_bahan (Udah ga ada id_pesanan ya!)
            $sql_pakai = "INSERT INTO pemakaian_bahan (id_barang, qty_dipakai, tanggal_pakai, keterangan) 
                          VALUES ('$id_barang', '$qty_dipakai', '$tanggal_pakai', '$no_rekap')";
            
            if(mysqli_query($conn, $sql_pakai)) {
                // 2. Kurangi stok
                mysqli_query($conn, "UPDATE barang SET stok = stok - $qty_dipakai WHERE id_barang = '$id_barang'");

                // 3. Catat mutasi keluar
                $keterangan_riwayat = "Pemakaian bahan harian ($no_rekap)";
                $sql_riwayat = "INSERT INTO riwayat_stok (id_barang, tanggal, jenis_mutasi, qty_mutasi, referensi_dokumen, keterangan, saldo_akhir) 
                                VALUES ('$id_barang', '$tanggal_pakai', 'Keluar', '$qty_dipakai', '$no_rekap', '$keterangan_riwayat', (SELECT stok FROM barang WHERE id_barang='$id_barang'))";
                mysqli_query($conn, $sql_riwayat);

                // Tambahin ke total uang buat di jurnal
                $total_nilai_pemakaian += ($qty_dipakai * (int)$dt_stok['harga_beli']);
                $ada_yang_berhasil = true;
            } else {
                $pesan_error .= "Gagal nyimpen ".$dt_stok['nama_barang']." ke database. \\n";
            }
        }
    }

    // ==========================================
    // JURNAL OTOMATIS: REKAP HARIAN
    // ==========================================
    if($ada_yang_berhasil && $total_nilai_pemakaian > 0) {
        $ket_jurnal = "Beban pemakaian bahan baku harian ($no_rekap)";
        
        mysqli_query($conn, "INSERT INTO jurnal_umum (tanggal_jurnal, no_referensi, tipe_transaksi, keterangan, total_transaksi) 
                             VALUES ('$tanggal_pakai', '$no_rekap', 'Biaya', '$ket_jurnal', '$total_nilai_pemakaian')");
        $id_jurnal = mysqli_insert_id($conn);
        
        // Debit: Beban Pemakaian Bahan (5120)
        mysqli_query($conn, "INSERT INTO detail_jurnal (id_jurnal, kode_akun, posisi, nominal) VALUES ('$id_jurnal', '5120', 'Debit', '$total_nilai_pemakaian')");
        // Kredit: Persediaan Barang (1130)
        mysqli_query($conn, "INSERT INTO detail_jurnal (id_jurnal, kode_akun, posisi, nominal) VALUES ('$id_jurnal', '1130', 'Kredit', '$total_nilai_pemakaian')");
    }

    if($pesan_error != "") {
        echo "<script>alert('Ada masalah nih:\\n" . $pesan_error . "'); window.history.back();</script>";
    } else if($ada_yang_berhasil) {
        echo "<script>alert('Sip! Rekap harian pemakaian bahan berhasil dicatat. Jurnal udah beres.'); window.location='pemakaian.php';</script>";
    } else {
        echo "<script>alert('Lah, ga ada data valid yang diproses.'); window.history.back();</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Input Pemakaian Bahan Harian - Harum Laundry</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style> body { font-family: 'Poppins', sans-serif; background-color: #fdf2f8; } .card { border-radius: 20px; border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.05); } </style>
</head>
<body class="p-5">
    <div class="container" style="max-width: 700px;">
        <div class="card p-4">
            <h3 class="fw-bold mb-4" style="color: #3b82f6;">Rekap Pemakaian Bahan Harian</h3>
            <div class="alert alert-info py-2" role="alert">
                <small>Input bahan yang dipakai seharian ini. Jurnal akan digabung jadi satu rekap per simpan.</small>
            </div>
            <form method="POST">
                
                <div class="mb-4">
                    <label class="form-label">Tanggal & Waktu Rekap</label>
                    <input type="datetime-local" name="tanggal_pakai" class="form-control rounded-pill" value="<?= date('Y-m-d\TH:i'); ?>" required>
                </div>

                <hr class="text-muted">

                <div class="mb-2 d-flex justify-content-between align-items-center">
                    <label class="form-label mb-0 fw-bold">Daftar Bahan yang Dipakai</label>
                    <button type="button" id="btn-tambah" class="btn btn-sm btn-outline-success rounded-pill">+ Tambah Baris</button>
                </div>

                <div id="tempat-barang">
                    <div class="row mb-3 baris-barang">
                        <div class="col-md-7">
                            <select name="id_barang[]" class="form-select rounded-pill" required>
                                <option value="" disabled selected>Pilih Bahan...</option>
                                <?php 
                                $q_brg = mysqli_query($conn, "SELECT id_barang, nama_barang, stok, satuan FROM barang WHERE tipe_barang IN ('Bahan Baku', 'Keduanya') ORDER BY nama_barang ASC");
                                
                                $opsi_barang = "";
                                while($brg = mysqli_fetch_array($q_brg)){
                                    $opsi_barang .= "<option value='".$brg['id_barang']."'>".$brg['nama_barang']." (Sisa: ".$brg['stok']." ".$brg['satuan'].")</option>";
                                }
                                echo $opsi_barang;
                                ?>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <input type="number" step="0.01" name="qty_dipakai[]" class="form-control rounded-pill" placeholder="Qty" required>
                        </div>
                        <div class="col-md-1 d-flex align-items-center">
                            <button type="button" class="btn btn-sm btn-danger rounded-circle hapus-baris">X</button>
                        </div>
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" name="simpan" class="btn btn-primary rounded-pill px-4 w-100" style="background: linear-gradient(90deg, #3b82f6, #ec4899); border:none;">Simpan Rekap Harian</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.getElementById('btn-tambah').addEventListener('click', function() {
            let wadah = document.getElementById('tempat-barang');
            let barisBaru = wadah.querySelector('.baris-barang').cloneNode(true);
            
            barisBaru.querySelector('input').value = '';
            barisBaru.querySelector('select').selectedIndex = 0;
            
            wadah.appendChild(barisBaru);
        });

        document.getElementById('tempat-barang').addEventListener('click', function(e) {
            if(e.target.classList.contains('hapus-baris')) {
                if(document.querySelectorAll('.baris-barang').length > 1) {
                    e.target.closest('.baris-barang').remove();
                } else {
                    alert('Yakali dihapus semua. Terus mau input pake apa lu?');
                }
            }
        });
    </script>
</body>
</html>