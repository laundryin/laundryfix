<?php 
include 'koneksi.php'; 

// 1. Ambil data untuk prefill jika ada
$nama_prefill = isset($_GET['nama']) ? $_GET['nama'] : '';
$source       = isset($_GET['source']) ? $_GET['source'] : '';

// 2. PROSES SIMPAN (Dijalankan saat tombol simpan diklik)
if(isset($_POST['simpan'])) {
    // GENERATE ID MANUAL 
    // Karena id_pelanggan di database adalah VARCHAR(20) dan bukan Auto Increment,
    // kita buat ID unik menggunakan prefix 'PLG' + JamMenitDetik agar tidak duplikat.
    $id_pelanggan = "PLG-" . date('His'); 

    // Sanitasi input
    $nama_pelanggan = mysqli_real_escape_string($conn, $_POST['nama_pelanggan']);
    $no_telepon     = mysqli_real_escape_string($conn, $_POST['no_telepon']);
    $alamat         = mysqli_real_escape_string($conn, $_POST['alamat']);

    // Query Insert - Sekarang menyertakan kolom id_pelanggan agar tidak kosong
    $query = "INSERT INTO pelanggan (id_pelanggan, nama_pelanggan, no_telepon, alamat) 
              VALUES ('$id_pelanggan', '$nama_pelanggan', '$no_telepon', '$alamat')";
    
    $insert = mysqli_query($conn, $query);

    if($insert) {
        if($source == 'pesanan') {
            // Gunakan $id_pelanggan yang baru dibuat untuk dikirim ke halaman pesanan
            echo "<script>alert('Pelanggan berhasil disimpan! Lanjut bikin pesanan.'); window.location='tambah_pesanan.php?new_id=$id_pelanggan';</script>";
        } else {
            echo "<script>alert('Data Pelanggan berhasil ditambahkan!'); window.location='pelanggan.php';</script>";
        }
        exit;
    } else {
        // Menampilkan error jika query gagal
        echo "Error: " . mysqli_error($conn);
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Pelanggan - Harum Laundry ERP</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; background-color: #fdf2f8; color: #334155; }
        .sidebar { width: 260px; height: 100vh; position: fixed; background: #fff; border-right: 1px solid #fce7f3; z-index: 100; overflow-y: auto; top: 0; left: 0;}
        .sidebar-brand { padding: 1.5rem; text-align: center; border-bottom: 1px solid #fce7f3; }
        .sidebar-brand img { max-width: 150px; }
        .nav-link { color: #64748b; font-weight: 500; padding: 10px 20px; border-radius: 10px; margin: 4px 15px; transition: 0.3s; font-size: 0.9rem; text-decoration: none; display: block;}
        .nav-link:hover, .nav-link.active { background: #fdf2f8; color: #ec4899 !important; border-left: 4px solid #ec4899; }
        .nav-link i { margin-right: 10px; font-size: 1.1rem; }
        .menu-title { font-size: 0.7rem; font-weight: 700; color: #475569; padding: 0 30px; margin-top: 20px; margin-bottom: 5px; text-transform: uppercase;}

        .main-content { 
            margin-left: 260px; 
            padding: 40px; 
            min-height: 100vh;
            display: flex;
            align-items: center; 
            justify-content: center; 
        }

        .card-form { 
            width: 100%;
            max-width: 700px; 
            border-radius: 20px; 
            border: none; 
            background: white; 
            padding: 40px; 
            box-shadow: 0 10px 30px rgba(236, 72, 153, 0.05); 
        }

        .form-section { background-color: #f8fafc; border: 1px solid #f1f5f9; border-radius: 15px; padding: 25px; margin-bottom: 25px; }
        .section-title { font-size: 0.9rem; font-weight: 700; color: #3b82f6; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 20px; }
        .form-control { border-radius: 10px; padding: 12px 15px; border: 1px solid #e2e8f0; font-size: 0.95rem; }
        .form-control:focus { border-color: #3b82f6; box-shadow: 0 0 0 0.25rem rgba(59, 130, 246, 0.1); }
        .form-label { font-weight: 500; color: #475569; font-size: 0.9rem; margin-bottom: 8px; }
    </style>
</head>
<body>
    <?php include 'sidebar.php'; ?>

    <div class="main-content">
        <div class="card-form">
            <div class="d-flex align-items-center mb-4 pb-3 border-bottom">
                <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px;">
                    <i class="bi bi-person-vcard fs-4"></i>
                </div>
                <div>
                    <h3 class="fw-bold mb-0" style="color: #3b82f6;">Tambah Master Pelanggan</h3>
                    <p class="text-muted mb-0" style="font-size: 0.85rem;">Input data identitas lengkap pelanggan baru</p>
                </div>
            </div>

            <form method="POST" action="">
                <div class="form-section">
                    <div class="section-title"><i class="bi bi-info-circle me-2"></i>1. Identitas Pelanggan</div>
                    <div class="row mb-3 gy-3">
                        <div class="col-md-6">
                            <label class="form-label">Nama Lengkap</label>
                            <input type="text" name="nama_pelanggan" class="form-control fw-bold text-primary" value="<?= htmlspecialchars($nama_prefill); ?>" placeholder="Contoh: Budi Santoso" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Nomor Telepon</label>
                            <input type="text" name="no_telepon" class="form-control" placeholder="Contoh: 081234567890" required>
                        </div>
                    </div>
                </div>

                <div class="form-section">
                    <div class="section-title"><i class="bi bi-geo-alt me-2"></i>2. Informasi Alamat</div>
                    <div class="mb-3">
                        <label class="form-label">Alamat Lengkap</label>
                        <textarea name="alamat" class="form-control" rows="3" placeholder="Contoh: Jl. Melati No. 15..." required></textarea>
                    </div>
                </div>

                <div class="d-flex gap-3 mt-4 pt-3 justify-content-end border-top">
                    <a href="<?= ($source == 'pesanan') ? 'tambah_pesanan.php' : 'pelanggan.php'; ?>" class="btn btn-light px-4" style="border-radius: 10px; font-weight: 500;">Batal</a>
                    
                    <button type="submit" name="simpan" class="btn btn-primary px-5 shadow-sm" style="border-radius: 10px; font-weight: 500; background: linear-gradient(90deg, #3b82f6, #ec4899); border: none;">
                        Simpan Pelanggan
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>