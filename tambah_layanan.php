<?php 
// Nyalakan laporan error biar kalau ada salah langsung ketahuan
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'koneksi.php'; 

if(isset($_POST['simpan'])) {
    // Tangkap data dari form dengan pengamanan
    $id_layanan   = mysqli_real_escape_string($conn, strtoupper($_POST['id_layanan']));
    $nama_layanan = mysqli_real_escape_string($conn, $_POST['nama_layanan']);
    $kategori     = mysqli_real_escape_string($conn, $_POST['kategori_layanan']);
    $satuan       = mysqli_real_escape_string($conn, $_POST['satuan']);
    $harga_jual   = (int) $_POST['harga_jual'];

    // 1. Cek dulu apakah Kode Layanan sudah ada di database
    $cek_kode = mysqli_query($conn, "SELECT * FROM tb_layanan WHERE id_layanan = '$id_layanan'");
    
    if(mysqli_num_rows($cek_kode) > 0) {
        echo "<script>alert('Gagal! Kode Layanan $id_layanan sudah terdaftar. Gunakan kode lain!'); window.history.back();</script>";
    } else {
        // 2. Proses Simpan ke Database
        $query = "INSERT INTO tb_layanan (id_layanan, nama_layanan, kategori_layanan, satuan, harga_jual) 
                  VALUES ('$id_layanan', '$nama_layanan', '$kategori', '$satuan', '$harga_jual')";
        
        $insert = mysqli_query($conn, $query);

        if($insert) {
            echo "<script>alert('Layanan baru berhasil disimpan!'); window.location='layanan.php';</script>";
        } else {
            // Jika gagal, tampilkan pesan error database yang spesifik
            echo "<div style='color:red; padding:20px; border:1px solid red;'>";
            echo "<strong>Gagal Simpan Database!</strong><br>";
            echo "Pesan Error: " . mysqli_error($conn);
            echo "</div>";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Layanan - Harum Laundry ERP</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    
    <style>
        body { font-family: 'Poppins', sans-serif; background-color: #fdf2f8; color: #334155; margin: 0; }
        
        /* SIDEBAR STYLE */
        .sidebar { width: 260px; height: 100vh; position: fixed; background: #fff; border-right: 1px solid #fce7f3; z-index: 100; overflow-y: auto; top: 0; left: 0;}
        .sidebar-brand { padding: 1.5rem; text-align: center; border-bottom: 1px solid #fce7f3; }
        .nav-link { color: #64748b; font-weight: 500; padding: 10px 20px; border-radius: 10px; margin: 4px 15px; transition: 0.3s; font-size: 0.9rem; text-decoration: none; display: block;}
        .nav-link:hover, .nav-link.active { background: #fdf2f8; color: #ec4899 !important; border-left: 4px solid #ec4899; }
        .nav-link i { margin-right: 10px; font-size: 1.1rem; }
        
        /* FORM STYLE - PERBAIKAN POSISI TENGAH */
        .main-content { 
            margin-left: 260px; 
            padding: 40px; 
            min-height: 100vh;
            display: flex;             /* Menggunakan Flexbox */
            align-items: center;       /* Tengah secara Vertikal */
            justify-content: center;    /* Tengah secara Horizontal */
        }

        .card-form { 
            width: 100%;
            max-width: 800px;          /* Batas lebar card */
            border-radius: 20px; 
            border: none; 
            background: white; 
            padding: 40px; 
            box-shadow: 0 10px 30px rgba(236, 72, 153, 0.05); 
        }

        .form-section { background-color: #f8fafc; border: 1px solid #f1f5f9; border-radius: 15px; padding: 25px; margin-bottom: 25px; }
        .section-title { font-size: 0.9rem; font-weight: 700; color: #3b82f6; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 20px; }
        
        .form-control, .form-select { border-radius: 10px; padding: 12px 15px; border: 1px solid #e2e8f0; font-size: 0.95rem; }
        .form-control:focus, .form-select:focus { border-color: #ec4899; box-shadow: 0 0 0 0.25rem rgba(236, 72, 153, 0.1); }
        .form-label { font-weight: 600; color: #475569; font-size: 0.9rem; margin-bottom: 8px; }
        
        /* Responsif untuk HP */
        @media (max-width: 768px) {
            .sidebar { display: none; }
            .main-content { margin-left: 0; padding: 20px; }
        }
    </style>
</head>
<body>

    <?php include 'sidebar.php'; ?>

    <div class="main-content">
        <div class="card-form">
            
            <div class="d-flex align-items-center mb-4 pb-3 border-bottom">
                <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px;">
                    <i class="bi bi-tags-fill fs-4"></i>
                </div>
                <div>
                    <h3 class="fw-bold mb-0" style="color: #3b82f6;">Tambah Master Layanan</h3>
                    <p class="text-muted mb-0" style="font-size: 0.85rem;">Input jenis layanan cuci atau pembelian barang</p>
                </div>
            </div>

            <form method="POST">
                
                <div class="form-section">
                    <div class="section-title"><i class="bi bi-info-circle me-2"></i>1. Identitas Layanan</div>
                    <div class="row gy-3">
                        <div class="col-md-4">
                            <label class="form-label">Kode Layanan</label>
                            <input type="text" name="id_layanan" class="form-control" placeholder="Misal: CK01" required>
                            <small class="text-muted" style="font-size:0.75rem;">Gunakan huruf & angka (Maks 10)</small>
                        </div>
                        <div class="col-md-8">
                            <label class="form-label">Nama Paket Layanan</label>
                            <input type="text" name="nama_layanan" class="form-control" placeholder="Contoh: Cuci Komplit Reguler 3 Hari" required>
                        </div>
                    </div>
                </div>

                <div class="form-section">
                    <div class="section-title"><i class="bi bi-layers me-2"></i>2. Pengaturan Harga</div>
                    <div class="row gy-3">
                        <div class="col-md-6">
                            <label class="form-label">Kategori Layanan</label>
                            <select name="kategori_layanan" class="form-select" required>
                                <option value="" disabled selected>-- Pilih Kategori --</option>
                                <option value="Drop-off">Drop-off (Titip Cuci)</option>
                                <option value="Self-Service">Self-Service (Nyuci Sendiri)</option>
                                <option value="Lainnya">Lainnya</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Satuan Hitung</label>
                            <select name="satuan" class="form-select" required>
                                <option value="" disabled selected>-- Pilih Satuan --</option>
                                <option value="Kg">Kilogram (Kg)</option>
                                <option value="Pcs">Satuan (Pcs)</option>
                                <option value="Meter">Meter (Karpet dll)</option>
                                <option value="Load 8Kg">1 Load Mesin (Max 8 Kg)</option>
                                <option value="Load 10Kg">1 Load Mesin (Max 10 Kg)</option>
                            </select>
                        </div>
                        <div class="col-12 mt-3">
                            <label class="form-label">Harga Jual (Rp)</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0">Rp</span>
                                <input type="number" name="harga_jual" class="form-control border-start-0 ps-0" placeholder="Contoh: 5000" required>
                            </div>
                            <small class="text-muted" style="font-size:0.75rem;">*Isi hanya angka tanpa titik atau koma</small>
                        </div>
                    </div>
                </div>

                <div class="d-flex gap-3 mt-4 pt-4 border-top justify-content-end">
                    <a href="layanan.php" class="btn btn-light px-4" style="border-radius: 10px; font-weight: 500;">Batal</a>
                    <button type="submit" name="simpan" class="btn text-white px-5 shadow-sm" style="background: linear-gradient(90deg, #3b82f6, #ec4899); border:none; border-radius: 10px; font-weight: 600;">
                        Simpan Layanan
                    </button>
                </div>

            </form>
        </div>
    </div>

</body>
</html>