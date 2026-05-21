<?php include 'koneksi.php'; ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Master Karyawan - dilaundryin ERP</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; background-color: #fdf2f8; color: #334155; }
        .sidebar { width: 260px; height: 100vh; position: fixed; background: rgba(255, 255, 255, 0.8); backdrop-filter: blur(10px); border-right: 1px solid #fce7f3; z-index: 100; overflow-y: auto;}
        .sidebar-brand { font-size: 1.8rem; font-weight: 700; background: linear-gradient(90deg, #3b82f6, #ec4899); -webkit-background-clip: text; -webkit-text-fill-color: transparent; padding: 1.5rem; text-align: center; border-bottom: 1px solid #fce7f3; }
        .nav-link { color: #64748b; font-weight: 500; padding: 10px 20px; border-radius: 10px; margin: 4px 15px; transition: 0.3s; font-size: 0.9rem;}
        .nav-link:hover, .nav-link.active { background: linear-gradient(90deg, rgba(59, 130, 246, 0.1), rgba(236, 72, 153, 0.1)); color: #ec4899 !important; border-left: 4px solid #ec4899; }
        .nav-link i { margin-right: 10px; font-size: 1.1rem; }
        .main-content { margin-left: 260px; padding: 40px; }
        .table-custom { background-color: white; border-radius: 20px; overflow: hidden; box-shadow: 0 10px 30px rgba(236, 72, 153, 0.05); }
        .table-custom thead th { background-color: #f8fafc; color: #64748b; font-weight: 600; padding: 15px; border-bottom: 2px solid #f1f5f9; }
        .table-custom td { padding: 15px; vertical-align: middle; border-bottom: 1px solid #f1f5f9; }
    </style>
</head>
<body>

    <?php include 'sidebar.php'; ?>

    <div class="main-content">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="fw-bold mb-0" style="color: #3b82f6;">Data Karyawan</h3>
            <a href="tambah_karyawan.php" class="btn rounded-pill px-4 text-white shadow-sm" style="background: linear-gradient(90deg, #3b82f6, #ec4899); border: none;">
                <i class="bi bi-plus-lg me-2"></i>Tambah Karyawan
            </a>
        </div>

        <div class="table-custom">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th width="10%">No</th>
                        <th>Nama Karyawan</th>
                        <th>Jabatan</th>
                        <th class="text-center" width="20%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $no = 1; 
                    // GANTI DESC JADI ASC DI SINI BIAR URUT DARI YANG LAMA KE BARU
                    $query = mysqli_query($conn, "SELECT * FROM karyawan ORDER BY id_karyawan ASC");
                    
                    if(mysqli_num_rows($query) > 0) {
                        while($d = mysqli_fetch_array($query)){
                    ?>
                    <tr>
                        <td class="fw-bold text-primary">#<?= $no++; ?></td>
                        <td class="fw-medium"><?= htmlspecialchars($d['nama_karyawan']); ?></td>
                        <td><span class="badge bg-info text-dark"><?= htmlspecialchars($d['jabatan']); ?></span></td>
                        <td class="text-center">
                            <a href="edit_karyawan.php?id=<?= $d['id_karyawan']; ?>" class="btn btn-sm btn-outline-primary rounded-pill px-3">Edit</a>
                            <a href="hapus_karyawan.php?id=<?= $d['id_karyawan']; ?>" class="btn btn-sm btn-outline-danger rounded-pill px-3" onclick="return confirm('Yakin mau mecat karyawan ini?')">Hapus</a>
                        </td>
                    </tr>
                    <?php 
                        } 
                    } else { ?>
                    <tr><td colspan="4" class="text-center py-4 text-muted">Belum ada karyawan.</td></tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>