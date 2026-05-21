<?php 
include 'koneksi.php'; 

// Tangkap ID yang dilempar dari tambah_pelanggan
$new_pelanggan_id = isset($_GET['new_id']) ? $_GET['new_id'] : '';

$auto_nota = "HRM-" . date('ymd') . "-" . rand(100,999);

if(isset($_POST['simpan'])) {
    $no_nota     = mysqli_real_escape_string($conn, $_POST['no_nota']);
    $jenis_trans = mysqli_real_escape_string($conn, $_POST['jenis_transaksi']);
    $id_karyawan = $_POST['id_karyawan'];
    $tgl_masuk   = $_POST['tanggal_masuk'];
    $status_cuc  = mysqli_real_escape_string($conn, $_POST['status_cucian']);
    $status_bayar= mysqli_real_escape_string($conn, $_POST['status_pembayaran']);
    
    if(empty($_POST['id_pelanggan'])) {
        $id_pelanggan = "NULL"; 
    } else {
        $id_pelanggan = "'" . mysqli_real_escape_string($conn, $_POST['id_pelanggan']) . "'";
    }

    $id_layanan   = $_POST['id_layanan'];
    
    // Nangkep dua inputan, Qty sama Berat
    $qty_input   = isset($_POST['qty']) ? (float) $_POST['qty'] : 0;
    $berat_input = isset($_POST['berat']) ? (float) $_POST['berat'] : 0;

    // Logic cerdas: ambil yang nominalnya lebih dari 0 buat dimasukin ke DB
    $qty_final = ($berat_input > 0) ? $berat_input : $qty_input;

    $q_harga = mysqli_query($conn, "SELECT harga_jual FROM tb_layanan WHERE id_layanan = '$id_layanan'");
    $data_harga = mysqli_fetch_array($q_harga);
    $harga_satuan = $data_harga['harga_jual'];

    // Subtotal pake nilai yang udah difilter tadi
    $subtotal = $qty_final * $harga_satuan;

    mysqli_query($conn, "SET FOREIGN_KEY_CHECKS=0;");

    $q_pesanan = "INSERT INTO pesanan (no_nota, jenis_transaksi, id_pelanggan, id_karyawan, tanggal_masuk, status_cucian, status_pembayaran, total_tagihan) 
                  VALUES ('$no_nota', '$jenis_trans', $id_pelanggan, '$id_karyawan', '$tgl_masuk', '$status_cuc', '$status_bayar', '$subtotal')";
    
    $insert_pesanan = mysqli_query($conn, $q_pesanan);

    if($insert_pesanan) {
        $id_pesanan = mysqli_insert_id($conn);

        // Disimpennya tetep ke kolom qty biar database lu ga usah diutak-atik lagi
        $q_detail = "INSERT INTO detail_pesanan (id_pesanan, id_layanan, id_barang, qty, harga_satuan, subtotal) 
                     VALUES ('$id_pesanan', '$id_layanan', NULL, '$qty_final', '$harga_satuan', '$subtotal')";
        
        $insert_detail = mysqli_query($conn, $q_detail);
        mysqli_query($conn, "SET FOREIGN_KEY_CHECKS=1;");

        if($insert_detail) {
            echo "<script>alert('Pesanan berhasil dibuat!'); window.location='pesanan.php';</script>";
        } else {
            echo "Gagal simpan detail: " . mysqli_error($conn);
        }
    } else {
        mysqli_query($conn, "SET FOREIGN_KEY_CHECKS=1;");
        echo "Gagal bikin nota: " . mysqli_error($conn);
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Buat Pesanan - Harum Laundry</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    
    <style>
        body { font-family: 'Poppins', sans-serif; background-color: #fdf2f8; color: #334155; }
        .sidebar { width: 260px; height: 100vh; position: fixed; background: #fff; border-right: 1px solid #fce7f3; z-index: 100; overflow-y: auto; top: 0; left: 0;}
        .sidebar-brand { padding: 1.5rem; text-align: center; border-bottom: 1px solid #fce7f3; }
        .sidebar-brand img { max-width: 150px; }
        .nav-link { color: #64748b; font-weight: 500; padding: 10px 20px; border-radius: 10px; margin: 4px 15px; transition: 0.3s; font-size: 0.9rem; text-decoration: none; display: block;}
        .nav-link:hover, .nav-link.active { background: #fdf2f8; color: #ec4899 !important; border-left: 4px solid #ec4899; }
        .nav-link i { margin-right: 10px; font-size: 1.1rem; }
        .menu-title { font-size: 0.7rem; font-weight: 700; color: #475569; padding: 0 30px; margin-top: 20px; margin-bottom: 5px; text-transform: uppercase;}
        .main-content { margin-left: 260px; padding: 40px; }
        .card-form { border-radius: 20px; border: none; background: white; padding: 40px; box-shadow: 0 10px 30px rgba(236, 72, 153, 0.05); }
        .form-section { background-color: #f8fafc; border: 1px solid #f1f5f9; border-radius: 15px; padding: 25px; margin-bottom: 25px; }
        .section-title { font-size: 0.9rem; font-weight: 700; color: #3b82f6; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 20px; }
        .form-control, .form-select { border-radius: 10px; padding: 12px 15px; border: 1px solid #e2e8f0; font-size: 0.95rem; }
        .form-label { font-weight: 500; color: #475569; font-size: 0.9rem; margin-bottom: 8px; }
        .select2-container .select2-selection--single { height: 50px; border-radius: 10px; border: 1px solid #e2e8f0; padding: 10px 15px; }
        .select2-container--default .select2-selection--single .select2-selection__rendered { line-height: 28px; color: #475569; font-size: 0.95rem; padding-left: 0;}
        .select2-container--default .select2-selection--single .select2-selection__arrow { height: 48px; right: 15px; }
        .select2-container--default.select2-container--open .select2-selection--single { border-color: #ec4899; }
    </style>
</head>
<body>

    <?php include 'sidebar.php'; ?>

    <div class="main-content">
        <div class="container" style="max-width: 950px; margin: 0;">
            <div class="card-form">
                <div class="d-flex align-items-center mb-4 pb-2 border-bottom">
                    <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px;">
                        <i class="bi bi-cart-plus fs-4"></i>
                    </div>
                    <h3 class="fw-bold mb-0" style="color: #3b82f6;">Buat Pesanan Baru</h3>
                </div>

                <form method="POST">
                    <div class="form-section">
                        <div class="section-title"><i class="bi bi-info-circle me-2"></i>1. Informasi Umum</div>
                        <div class="row gy-3">
                            <div class="col-md-4">
                                <label class="form-label">No. Nota</label>
                                <input type="text" name="no_nota" class="form-control bg-light fw-bold text-secondary" value="<?= $auto_nota; ?>" readonly>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Tanggal Masuk</label>
                                <input type="datetime-local" name="tanggal_masuk" class="form-control" value="<?= date('Y-m-d\TH:i'); ?>" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Kasir Bertugas</label>
                                <select name="id_karyawan" class="form-select" required>
                                    <option value="" disabled selected>-- Pilih Kasir --</option>
                                    <?php 
                                    $q_kar = mysqli_query($conn, "SELECT * FROM karyawan WHERE jabatan LIKE '%Kasir%'");
                                    while($kar = mysqli_fetch_array($q_kar)){ 
                                        echo "<option value='".$kar['id_karyawan']."'>".$kar['nama_karyawan']." (".$kar['jabatan'].")</option>"; 
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-section">
                        <div class="section-title"><i class="bi bi-person-check me-2"></i>2. Data Pelanggan</div>
                        <div class="row gy-3">
                            <div class="col-md-12">
                                <label class="form-label d-flex justify-content-between align-items-center">
                                    <span>Pilih Nama Pelanggan</span>
                                    <a href="tambah_pelanggan.php?source=pesanan" onclick="simpanDraft()" class="text-decoration-none" style="font-size: 0.8rem; font-weight: 600;"><i class="bi bi-person-plus-fill me-1"></i>Pelanggan Baru</a>
                                </label>
                                
                                <select name="id_pelanggan" id="pelanggan_search" class="form-select" style="width: 100%;">
                                    <option value="">-- Umum / Walk-in Customer --</option>
                                    <?php 
                                    $q_pel = mysqli_query($conn, "SELECT * FROM pelanggan ORDER BY id_pelanggan DESC");
                                    while($pel = mysqli_fetch_array($q_pel)){ 
                                        echo "<option value='".$pel['id_pelanggan']."'>".$pel['nama_pelanggan']." (".$pel['no_telepon'].")</option>"; 
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-section">
                        <div class="section-title"><i class="bi bi-tags me-2"></i>3. Rincian Layanan</div>
                        <div class="row gy-3">
                            <div class="col-md-4">
                                <label class="form-label">Jenis Transaksi</label>
                                <select name="jenis_transaksi" id="jenis_transaksi" class="form-select" required onchange="filterLayanan()">
                                    <option value="" disabled selected>-- Pilih Jenis --</option>
                                    <option value="Drop-off">Drop-off (Titip Cuci)</option>
                                    <option value="Self-Service">Self-Service (Sendiri)</option>
                                </select>
                            </div>
                            <div class="col-md-8">
                                <label class="form-label">Pilih Paket Layanan</label>
                                <select name="id_layanan" id="id_layanan" class="form-select" required onchange="hitungTotal()">
                                    <option value="" data-harga="0" data-kategori="all" disabled selected>-- Pilih Jenis Transaksi Dulu --</option>
                                    <?php 
                                    $q_lay = mysqli_query($conn, "SELECT * FROM tb_layanan");
                                    while($lay = mysqli_fetch_array($q_lay)){ 
                                        echo "<option value='".$lay['id_layanan']."' data-harga='".$lay['harga_jual']."' data-kategori='".$lay['kategori_layanan']."'>".$lay['nama_layanan']." - Rp ".number_format($lay['harga_jual'], 0, ',', '.')." / ".$lay['satuan']."</option>"; 
                                    }
                                    ?>
                                </select>
                            </div>
                            
                            <div class="col-md-4">
                                <label class="form-label">Qty (Pcs/Satuan)</label>
                                <input type="number" step="1" name="qty" id="qty" class="form-control" placeholder="Isi jika satuan" oninput="hitungTotal(); resetLawan('qty')">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Berat (Kg)</label>
                                <input type="number" step="0.01" name="berat" id="berat" class="form-control" placeholder="Isi jika kiloan" oninput="hitungTotal(); resetLawan('berat')">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Total Harga (Rp)</label>
                                <input type="text" id="total_tampil" class="form-control bg-light fw-bold text-primary" value="0" readonly>
                            </div>
                        </div>
                    </div>

                    <div class="form-section mb-0">
                        <div class="section-title"><i class="bi bi-activity me-2"></i>4. Status Akhir</div>
                        <div class="row gy-3">
                            <div class="col-md-6">
                                <label class="form-label">Status Cucian</label>
                                <select name="status_cucian" class="form-select" required>
                                    <option value="Proses">Proses (Ditinggal Dicuci)</option>
                                    <option value="Langsung Selesai">Langsung Selesai (Self-Service)</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Status Pembayaran</label>
                                <select name="status_pembayaran" class="form-select" required>
                                    <option value="Belum Lunas">Belum Lunas (Bayar Nanti)</option>
                                    <option value="Lunas">Lunas (Bayar Sekarang)</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex gap-3 mt-5 justify-content-end border-top pt-4">
                        <a href="pesanan.php" class="btn btn-light px-4" style="border-radius: 10px; font-weight: 500;">Batal</a>
                        <button type="submit" name="simpan" class="btn text-white px-5 shadow-sm" style="background: linear-gradient(90deg, #3b82f6, #ec4899); border:none; border-radius: 10px; font-weight: 500;">
                            <i class="bi bi-check-circle me-2"></i>Simpan Pesanan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function simpanDraft() {
            sessionStorage.setItem('draft_tgl', $('input[name="tanggal_masuk"]').val());
            sessionStorage.setItem('draft_kasir', $('select[name="id_karyawan"]').val());
            sessionStorage.setItem('draft_jenis', $('select[name="jenis_transaksi"]').val());
            sessionStorage.setItem('draft_layanan', $('select[name="id_layanan"]').val());
            sessionStorage.setItem('draft_qty', $('input[name="qty"]').val());
            sessionStorage.setItem('draft_berat', $('input[name="berat"]').val());
            sessionStorage.setItem('draft_cucian', $('select[name="status_cucian"]').val());
            sessionStorage.setItem('draft_bayar', $('select[name="status_pembayaran"]').val());
        }

        function loadDraft() {
            if(sessionStorage.getItem('draft_tgl')) {
                $('input[name="tanggal_masuk"]').val(sessionStorage.getItem('draft_tgl'));
                $('select[name="id_karyawan"]').val(sessionStorage.getItem('draft_kasir'));
                
                var jenis = sessionStorage.getItem('draft_jenis');
                if(jenis) {
                    $('select[name="jenis_transaksi"]').val(jenis);
                    filterLayanan(); 
                }
                var layanan = sessionStorage.getItem('draft_layanan');
                if(layanan) $('select[name="id_layanan"]').val(layanan);
                
                var qty = sessionStorage.getItem('draft_qty');
                var berat = sessionStorage.getItem('draft_berat');
                if(qty) $('input[name="qty"]').val(qty);
                if(berat) $('input[name="berat"]').val(berat);
                
                if(qty || berat) hitungTotal(); 

                $('select[name="status_cucian"]').val(sessionStorage.getItem('draft_cucian'));
                $('select[name="status_pembayaran"]').val(sessionStorage.getItem('draft_bayar'));

                sessionStorage.clear();
            } else {
                filterLayanan(); 
            }
        }

        $(document).ready(function() {
            // Inisialisasi Select2
            $('#pelanggan_search').select2({
                placeholder: "-- Umum / Walk-in Customer --",
                allowClear: true,
                language: {
                    noResults: function() {
                        var typedName = $('.select2-search__field').val();
                        var url = 'tambah_pelanggan.php?nama=' + encodeURIComponent(typedName) + '&source=pesanan';
                        return "<div class='text-center p-2'><span class='d-block text-muted mb-2'>Pelanggan tidak ditemukan.</span><a href='" + url + "' onclick='simpanDraft()' class='btn btn-sm btn-primary w-100 rounded-pill'><i class='bi bi-plus-lg me-1'></i>Tambah Baru</a></div>";
                    }
                },
                escapeMarkup: function(markup) { return markup; }
            });

            var preselectedId = "<?= $new_pelanggan_id; ?>";
            if(preselectedId !== '') {
                $('#pelanggan_search').val(preselectedId).trigger('change');
            }

            loadDraft();
        });

        function filterLayanan() {
            var jenis_trx = document.getElementById('jenis_transaksi').value; 
            var select_layanan = document.getElementById('id_layanan');
            var options = select_layanan.options;

            select_layanan.selectedIndex = 0;
            document.getElementById('total_tampil').value = '0';
            document.getElementById('qty').value = '';
            document.getElementById('berat').value = '';

            for (var i = 0; i < options.length; i++) {
                var kategori = options[i].getAttribute('data-kategori');
                if (kategori === 'all' || kategori === jenis_trx) {
                    options[i].style.display = '';
                } else {
                    options[i].style.display = 'none';
                }
            }
        }

        // Fungsi biar milih salah satu aja, gausah serakah diisi dua-duanya
        function resetLawan(yangDiisi) {
            if(yangDiisi === 'qty') {
                document.getElementById('berat').value = '';
            } else {
                document.getElementById('qty').value = '';
            }
        }

        function hitungTotal() {
            var select = document.getElementById('id_layanan');
            var qty = parseFloat(document.getElementById('qty').value) || 0;
            var berat = parseFloat(document.getElementById('berat').value) || 0;
            
            // Ambil yang ada isinya
            var pengali = qty > 0 ? qty : berat; 
            
            var harga = select.options[select.selectedIndex].getAttribute('data-harga');
            var total = (pengali * harga) || 0;
            
            document.getElementById('total_tampil').value = new Intl.NumberFormat('id-ID').format(total);
        }
    </script>
</body>
</html>