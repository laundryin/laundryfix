<?php 
include 'koneksi.php'; 

if(isset($_POST['simpan'])) {
    $kode     = mysqli_real_escape_string($conn, $_POST['kode_akun']);
    $nama     = mysqli_real_escape_string($conn, $_POST['nama_akun']);
    $kategori = mysqli_real_escape_string($conn, $_POST['kategori_akun']);
    $saldo_n  = mysqli_real_escape_string($conn, $_POST['saldo_normal']);

    // Cek dulu kodenya udah dipake belum
    $cek = mysqli_query($conn, "SELECT * FROM akun WHERE kode_akun = '$kode'");
    if(mysqli_num_rows($cek) > 0) {
        echo "<script>alert('Kode Akun $kode udah ada, cari angka lain Bos!'); window.history.back();</script>";
    } else {
        $insert = mysqli_query($conn, "INSERT INTO akun (kode_akun, nama_akun, kategori_akun, saldo_normal, saldo_berjalan) 
                                       VALUES ('$kode', '$nama', '$kategori', '$saldo_n', 0)");
        
        if($insert) {
            echo "<script>alert('Akun baru berhasil ditambah!'); window.location='akun.php';</script>";
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Akun - Harum Laundry</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
    <style> body { font-family: 'Poppins', sans-serif; background-color: #fdf2f8; } .card { border-radius: 20px; border: none; } </style>
</head>
<body class="p-4">
    <div class="container" style="max-width: 600px;">
        <div class="card p-4 shadow-sm">
            <h3 class="fw-bold mb-4 text-primary">Tambah Master Akun</h3>
            <form method="POST">
                
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Kode Akun</label>
                        <input type="text" name="kode_akun" class="form-control rounded-pill" placeholder="Misal: 1120" required>
                    </div>
                    <div class="col-md-8 mb-3">
                        <label class="form-label">Nama Akun</label>
                        <input type="text" name="nama_akun" class="form-control rounded-pill" placeholder="Misal: Piutang Usaha" required>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Kategori Akun</label>
                        <select name="kategori_akun" class="form-select rounded-pill" required>
                            <option value="" disabled selected>-- Pilih Kategori --</option>
                            <option value="Aset">Aset</option>
                            <option value="Kewajiban">Kewajiban</option>
                            <option value="Ekuitas">Ekuitas</option>
                            <option value="Pendapatan">Pendapatan</option>
                            <option value="Beban">Beban</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Saldo Normal</label>
                        <select name="saldo_normal" class="form-select rounded-pill" required>
                            <option value="" disabled selected>-- Debit / Kredit --</option>
                            <option value="Debit">Debit</option>
                            <option value="Kredit">Kredit</option>
                        </select>
                    </div>
                </div>

                <div class="d-flex gap-2 mt-4 justify-content-end">
                    <a href="akun.php" class="btn btn-outline-secondary rounded-pill px-4">Batal</a>
                    <button type="submit" name="simpan" class="btn btn-primary rounded-pill px-5" style="background: linear-gradient(90deg, #3b82f6, #ec4899); border:none;">Simpan Akun</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>