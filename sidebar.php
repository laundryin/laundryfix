<?php
// Deteksi lo lagi buka file apa biar menu 'active'-nya otomatis
$current_page = basename($_SERVER['PHP_SELF']);
?>

<!-- Tambahin class d-flex flex-column dan style height 100% biar bisa mainin posisi bawah -->
<div class="sidebar d-flex flex-column" style="height: 100vh;">
    
    <!-- Bagian Atas: Brand dan Menu -->
    <div class="flex-grow-1 overflow-y-auto">
        <div class="sidebar-brand">
            <!-- Logo londriin kesayangan lo -->
            <img src="londriin.jpeg" alt="Logo Londriin" style="max-width: 150px; height: auto; border-radius: 10px; margin: 0 auto; display: block;">
        </div>
        
        <div class="mt-3">
            <small class="text-uppercase fw-bold px-4 mb-1 d-block text-muted" style="font-size: 0.7rem;">Dashboard</small>
            <a href="index.php" class="nav-link <?= ($current_page == 'index.php') ? 'active' : ''; ?>">
                <i class="bi bi-house-door"></i> Overview
            </a>
            
            <small class="text-uppercase fw-bold px-4 mb-1 mt-3 d-block text-muted" style="font-size: 0.7rem;">Operasional</small>
            <a href="pesanan.php" class="nav-link <?= ($current_page == 'pesanan.php' || $current_page == 'tambah_pesanan.php') ? 'active' : ''; ?>">
                <i class="bi bi-cart-check"></i> Kasir / Pesanan
            </a>
            <a href="pembelian.php" class="nav-link <?= ($current_page == 'pembelian.php') ? 'active' : ''; ?>"><i class="bi bi-bag"></i> Pembelian</a>
            <a href="pemakaian.php" class="nav-link <?= ($current_page == 'pemakaian.php') ? 'active' : ''; ?>"><i class="bi bi-droplet"></i> Pemakaian Barang</a>

            <small class="text-uppercase fw-bold px-4 mb-1 mt-3 d-block text-muted" style="font-size: 0.7rem;">Data Master</small>
            <a href="pelanggan.php" class="nav-link <?= ($current_page == 'pelanggan.php' || $current_page == 'tambah_pelanggan.php' || $current_page == 'edit_pelanggan.php') ? 'active' : ''; ?>">
                <i class="bi bi-people"></i> Pelanggan
            </a>
            <a href="layanan.php" class="nav-link <?= ($current_page == 'layanan.php' || $current_page == 'tambah_layanan.php' || $current_page == 'edit_layanan.php') ? 'active' : ''; ?>"><i class="bi bi-tags"></i> Layanan</a>
            <a href="barang.php" class="nav-link <?= ($current_page == 'barang.php') ? 'active' : ''; ?>"><i class="bi bi-box-seam"></i> Barang & Stok</a>
            <a href="supplier.php" class="nav-link <?= ($current_page == 'supplier.php') ? 'active' : ''; ?>"><i class="bi bi-truck"></i> Supplier</a>
            <a href="karyawan.php" class="nav-link <?= ($current_page == 'karyawan.php' || $current_page == 'tambah_karyawan.php' || $current_page == 'edit_karyawan.php') ? 'active' : ''; ?>"><i class="bi bi-person-badge"></i> Karyawan</a>
            
            <small class="text-uppercase fw-bold px-4 mb-1 mt-3 d-block text-muted" style="font-size: 0.7rem;">Akuntansi</small>
            <a href="akun.php" class="nav-link <?= ($current_page == 'akun.php') ? 'active' : ''; ?>"><i class="bi bi-journal-text"></i> Master Akun</a>
            <a href="aset.php" class="nav-link <?= ($current_page == 'aset.php') ? 'active' : ''; ?>"><i class="bi bi-pc-display"></i> Aset Tetap</a>
            <a href="jurnal.php" class="nav-link <?= ($current_page == 'jurnal.php') ? 'active' : ''; ?>"><i class="bi bi-journal-check"></i> Jurnal Umum</a>
            <a href="riwayat_stok.php" class="nav-link <?= ($current_page == 'riwayat_stok.php') ? 'active' : ''; ?>"><i class="bi bi-clock-history"></i> Riwayat Stok</a>
        </div>
    </div>

    <!-- Bagian Bawah: Logout -->
    <div class="mt-auto pb-4">
        <hr class="mx-4" style="opacity: 0.1;">
        <a href="logout.php" class="nav-link text-danger fw-bold" onclick="return confirm('Udah mau keluar, om?')">
            <i class="bi bi-box-arrow-left"></i> Logout
        </a>
    </div>
</div>

<style>
    /* Tambahan dikit biar scrollbar-nya nggak nutupin menu kalau kepanjangan */
    .overflow-y-auto::-webkit-scrollbar {
        width: 5px;
    }
    .overflow-y-auto::-webkit-scrollbar-thumb {
        background: #f1f1f1;
        border-radius: 10px;
    }
</style>