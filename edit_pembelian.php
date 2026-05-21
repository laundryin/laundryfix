<?php 
include 'koneksi.php';

// Ambil ID dari URL
$id = isset($_GET['id']) ? $_GET['id'] : '';
if (!$id) { header("location:pembelian.php"); exit; }

// Query ambil data lama
$query = mysqli_query($conn, "
    SELECT p.*, dp.id_barang, dp.qty 
    FROM pembelian p 
    JOIN detail_pembelian dp ON p.id_pembelian = dp.id_pembelian 
    WHERE p.id_pembelian = '$id'
");
$data = mysqli_fetch_array($query);

// Proses Update
if(isset($_POST['update'])) {
    $no_faktur = mysqli_real_escape_string($conn, $_POST['no_faktur']);
    $tanggal   = $_POST['tanggal'];
    $id_barang = $_POST['id_barang'];
    $qty       = $_POST['qty'];
    $total     = $_POST['total'];
    $status    = $_POST['status'];

    // 1. Update tabel pembelian
    $upd_beli = mysqli_query($conn, "UPDATE pembelian SET 
                no_faktur_beli = '$no_faktur', 
                tanggal_beli = '$tanggal', 
                total_biaya = '$total', 
                status_pembayaran = '$status' 
                WHERE id_pembelian = '$id'");

    // 2. Update tabel detail_pembelian
    $upd_detail = mysqli_query($conn, "UPDATE detail_pembelian SET 
                  id_barang = '$id_barang', 
                  qty = '$qty' 
                  WHERE id_pembelian = '$id'");

    if($upd_beli && $upd_detail) {
        echo "<script>alert('Data pembelian berhasil diperbarui!'); window.location='pembelian.php';</script>";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Pembelian - Harum Laundry ERP</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; background-color: #fdf2f8; color: #334155; }
        /* SIDEBAR STYLE SESUAI REQUEST */
        .sidebar { width: 260px; height: 100vh; position: fixed; background: #fff; border-right: 1px solid #fce7f3; z-index: 100; overflow-y: auto;}
        .sidebar-brand { padding: 1.5rem; text-align: center; border-bottom: 1px solid #fce7f3; }
        .sidebar-brand img { max-width: 150px; } 
        .nav-link { color: #64748b; font-weight: 500; padding: 10px 20px; border-radius: 10px; margin: 4px 15px; transition: 0.3s; font-size: 0.9rem;}
        .nav-link:hover, .nav-link.active { background: #fdf2f8; color: #ec4899 !important; border-left: 4px solid #ec4899; }
        .nav-link i { margin-right: 10px; font-size: 1.1rem; }
        .menu-title { font-size: 0.7rem; font-weight: 700; color: #475569; padding: 0 30px; margin-top: 20px; margin-bottom: 5px; text-transform: uppercase;}
        
        /* CONTENT STYLE */
        .main-content { margin-left: 260px; padding: 40px; }
        .card-custom { background-color: white; border-radius: 20px; padding: 30px; box-shadow: 0 10px 30px rgba(236, 72, 153, 0.05); border:none; }
        .form-label { font-weight: 600; color: #64748b; font-size: 0.85rem; }
        .form-control, .form-select { border-radius: 10px; padding: 12px; border: 1px solid #f1f5f9; }
        .form-control:focus { border-color: #ec4899; box-shadow: 0 0 0 0.25rem rgba(236, 72, 153, 0.1); }
        
        .btn-update { background: linear-gradient(90deg, #3b82f6, #ec4899); border: none; color: white; border-radius: 50px; padding: 10px 40px; font-weight: 600; transition: 0.3s; }
        .btn-update:hover { opacity: 0.9; transform: translateY(-2px); }
        
        .sidebar::-webkit-scrollbar { width: 5px; }
        .sidebar::-webkit-scrollbar-thumb { background: #fbcfe8; border-radius: 10px; }
    </style>
</head>
<body>

    <?php include 'sidebar.php'; ?>

    <div class="main-content">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="fw-bold mb-0" style="color: #3b82f6;">Edit Transaksi Pembelian</h3>
            <a href="pembelian.php" class="btn btn-light rounded-pill px-4 fw-medium">
                <i class="bi bi-arrow-left me-2"></i>Kembali
            </a>
        </div>

        <div class="card-custom">
            <form method="POST">
                <div class="row g-4">
                    <div class="col-md-6">
                        <label class="form-label">Nomor Faktur</label>
                        <input type="text" name="no_faktur" class="form-control" value="<?= htmlspecialchars($data['no_faktur_beli']); ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Tanggal Transaksi</label>
                        <input type="datetime-local" name="tanggal" class="form-control" value="<?= date('Y-m-d\TH:i', strtotime($data['tanggal_beli'])); ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Pilih Barang</label>
                        <select name="id_barang" class="form-select" required>
                            <?php 
                            $brg = mysqli_query($conn, "SELECT * FROM barang");
                            while($b = mysqli_fetch_array($brg)){
                                $selected = ($b['id_barang'] == $data['id_barang']) ? 'selected' : '';
                                echo "<option value='".$b['id_barang']."' $selected>".$b['nama_barang']."</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Jumlah (Qty)</label>
                        <input type="number" name="qty" class="form-control" value="<?= $data['qty']; ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Total Pengeluaran (Rp)</label>
                        <input type="number" name="total" class="form-control" value="<?= $data['total_biaya']; ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Status Pembayaran</label>
                        <select name="status" class="form-select" required>
                            <option value="Lunas" <?= ($data['status_pembayaran'] == 'Lunas') ? 'selected' : ''; ?>>Lunas</option>
                            <option value="Belum Lunas" <?= ($data['status_pembayaran'] == 'Belum Lunas') ? 'selected' : ''; ?>>Belum Lunas</option>
                        </select>
                    </div>
                </div>

                <div class="mt-5 border-top pt-4">
                    <button type="submit" name="update" class="btn-update">
                        <i class="bi bi-check-circle me-2"></i>Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>