-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 19 Bulan Mei 2026 pada 06.18
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `harum_laundry`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `akun`
--

CREATE TABLE `akun` (
  `kode_akun` varchar(20) NOT NULL,
  `nama_akun` varchar(100) NOT NULL,
  `kategori_akun` enum('Aset','Kewajiban','Ekuitas','Pendapatan','Beban') NOT NULL,
  `saldo_normal` enum('Debit','Kredit') NOT NULL,
  `saldo_berjalan` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `akun`
--

INSERT INTO `akun` (`kode_akun`, `nama_akun`, `kategori_akun`, `saldo_normal`, `saldo_berjalan`) VALUES
('1110', 'Kas di Tangan (Cash on Hand)', 'Aset', 'Debit', 0),
('1111', 'Kas Kecil (Petty Cash)', 'Aset', 'Debit', 0),
('1120', 'Bank BCA Operasional', 'Aset', 'Debit', 0),
('1121', 'Bank Mandiri Bisnis', 'Aset', 'Debit', 0),
('1130', 'Kas Digital - QRIS', 'Aset', 'Debit', 0),
('1140', 'Piutang Usaha', 'Aset', 'Debit', 0),
('1141', 'Piutang Kemitraan / Korporat', 'Aset', 'Debit', 0),
('1150', 'Persediaan Barang Dagangan Ritel', 'Aset', 'Debit', 0),
('1160', 'Perlengkapan Utama - Chemical', 'Aset', 'Debit', 0),
('1161', 'Perlengkapan Pengemasan (Packaging)', 'Aset', 'Debit', 0),
('1162', 'Perlengkapan Administrasi (ATK)', 'Aset', 'Debit', 0),
('1170', 'Sewa Kios Dibayar di Muka', 'Aset', 'Debit', 0),
('1210', 'Mesin Cuci (Washing Machine)', 'Aset', 'Debit', 0),
('1211', 'Akumulasi Penyusutan Mesin Cuci', 'Aset', 'Kredit', 0),
('1220', 'Mesin Pengering (Tumble Dryer)', 'Aset', 'Debit', 0),
('1221', 'Akumulasi Penyusutan Mesin Pengering', 'Aset', 'Kredit', 0),
('1230', 'Peralatan Setrika & Boiler', 'Aset', 'Debit', 0),
('1231', 'Akumulasi Penyusutan Peralatan Setrika', 'Aset', 'Kredit', 0),
('1240', 'Peralatan Pendukung Kios', 'Aset', 'Debit', 0),
('1241', 'Akumulasi Penyusutan Peralatan Pendukung', 'Aset', 'Kredit', 0),
('1250', 'Renovasi & Instalasi Tempat', 'Aset', 'Debit', 0),
('1251', 'Akumulasi Amortisasi Renovasi', 'Aset', 'Kredit', 0),
('1260', 'Kendaraan Kurir Shuttle', 'Aset', 'Debit', 0),
('1261', 'Akumulasi Penyusutan Kendaraan', 'Aset', 'Kredit', 0),
('2110', 'Utang Usaha', 'Kewajiban', 'Kredit', 0),
('2120', 'Pendapatan Diterima di Muka - Deposit Member', 'Kewajiban', 'Kredit', 0),
('2130', 'Utang Gaji Staf', 'Kewajiban', 'Kredit', 0),
('2140', 'Utang Listrik & Air', 'Kewajiban', 'Kredit', 0),
('2150', 'Utang Pajak UMKM (PPh Final)', 'Kewajiban', 'Kredit', 0),
('2210', 'Utang Pembiayaan Mesin (Leasing)', 'Kewajiban', 'Kredit', 0),
('3110', 'Modal Ditempatkan - Pemilik Utama', 'Ekuitas', 'Kredit', 0),
('3120', 'Prive / Penarikan Pemilik', 'Ekuitas', 'Debit', 0),
('3130', 'Laba Ditahan (Retained Earnings)', 'Ekuitas', 'Kredit', 0),
('3140', 'Ikhtisar Laba Rugi', 'Ekuitas', 'Kredit', 0),
('4110', 'Pendapatan Jasa Laundry Kiloan', 'Pendapatan', 'Kredit', 0),
('4111', 'Pendapatan Jasa Laundry Kiloan (Setrika Saja)', 'Pendapatan', 'Kredit', 0),
('4120', 'Pendapatan Jasa Laundry Express', 'Pendapatan', 'Kredit', 0),
('4130', 'Pendapatan Jasa Satuan - Bedcover & Selimut', 'Pendapatan', 'Kredit', 0),
('4131', 'Pendapatan Jasa Satuan - Pakaian Premium', 'Pendapatan', 'Kredit', 0),
('4132', 'Pendapatan Jasa Satuan - Sepatu & Tas', 'Pendapatan', 'Kredit', 0),
('4140', 'Pendapatan Layanan Antar-Jemput', 'Pendapatan', 'Kredit', 0),
('4210', 'Pendapatan Ritel Konsumen', 'Pendapatan', 'Kredit', 0),
('4290', 'Pendapatan Operasional Lainnya (Jerigen Bekas)', 'Pendapatan', 'Kredit', 0),
('5110', 'Beban Pemakaian Chemical - Detergen', 'Beban', 'Debit', 0),
('5111', 'Beban Pemakaian Chemical - Softener', 'Beban', 'Debit', 0),
('5112', 'Beban Pemakaian Chemical - Parfum Laundry', 'Beban', 'Debit', 0),
('5120', 'Beban Pemakaian Plastik & Packing', 'Beban', 'Debit', 0),
('5130', 'Beban Pemeliharaan Aset', 'Beban', 'Debit', 0),
('5210', 'Beban Gaji Staf Kasir', 'Beban', 'Debit', 0),
('5211', 'Beban Gaji Operator Cuci & Pengering', 'Beban', 'Debit', 0),
('5212', 'Beban Gaji Operator Setrika', 'Beban', 'Debit', 0),
('5220', 'Beban Insentif / Komisi Per-Kg', 'Beban', 'Debit', 0),
('5240', 'Beban Kesejahteraan Karyawan', 'Beban', 'Debit', 0),
('5310', 'Beban Listrik (PLN)', 'Beban', 'Debit', 0),
('5320', 'Beban Air (PDAM / Sumur)', 'Beban', 'Debit', 0),
('5330', 'Beban Gas LPG', 'Beban', 'Debit', 0),
('5340', 'Beban Sewa Ruko / Kios Bulanan', 'Beban', 'Debit', 0),
('5350', 'Beban Internet, WiFi & Telepon', 'Beban', 'Debit', 0),
('5410', 'Beban Servis & Suku Cadang Mesin', 'Beban', 'Debit', 0),
('5412', 'Beban Servis Setrika & Boiler', 'Beban', 'Debit', 0),
('5430', 'Beban Servis & Bensin Kendaraan Kurir', 'Beban', 'Debit', 0),
('5510', 'Beban Penyusutan Aset Tetap', 'Beban', 'Debit', 0),
('5610', 'Beban Pemasaran, Cetak Brosur & Spanduk', 'Beban', 'Debit', 0),
('5620', 'Beban Ganti Rugi Pakaian Pelanggan', 'Beban', 'Debit', 0),
('5630', 'Beban Potongan Jasa / Diskon', 'Beban', 'Debit', 0),
('5640', 'Beban Administrasi Bank & QRIS', 'Beban', 'Debit', 0),
('5699', 'Beban Lain-lain (General & Administrative)', 'Beban', 'Debit', 0);

-- --------------------------------------------------------

--
-- Struktur dari tabel `aset_tetap`
--

CREATE TABLE `aset_tetap` (
  `id_aset` int(11) NOT NULL,
  `kode_aset` varchar(50) NOT NULL,
  `nama_aset` varchar(100) NOT NULL,
  `kategori_aset` enum('Mesin Cuci','Mesin Pengering','Elektronik','Kendaraan','Lainnya') NOT NULL,
  `tanggal_beli` date NOT NULL,
  `harga_beli` int(11) NOT NULL,
  `umur_ekonomis_bulan` int(11) NOT NULL,
  `status_aset` enum('Aktif','Rusak','Dijual/Dibuang') DEFAULT 'Aktif'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `aset_tetap`
--

INSERT INTO `aset_tetap` (`id_aset`, `kode_aset`, `nama_aset`, `kategori_aset`, `tanggal_beli`, `harga_beli`, `umur_ekonomis_bulan`, `status_aset`) VALUES
(1, 'MC-01', 'Mesin Cuci Front Loading 10Kg', 'Mesin Cuci', '2023-10-01', 7000000, 60, 'Aktif'),
(2, 'MP-01', 'Mesin Pengering Gas 10Kg', 'Mesin Pengering', '2023-10-01', 6000000, 60, 'Aktif');

-- --------------------------------------------------------

--
-- Struktur dari tabel `barang`
--

CREATE TABLE `barang` (
  `id_barang` int(11) NOT NULL,
  `nama_barang` varchar(100) NOT NULL,
  `tipe_barang` enum('Bahan Baku','Barang Dijual','Keduanya') NOT NULL,
  `satuan` varchar(20) NOT NULL,
  `harga_beli` int(11) DEFAULT 0,
  `harga_jual` int(11) DEFAULT 0,
  `stok` decimal(10,2) DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `barang`
--

INSERT INTO `barang` (`id_barang`, `nama_barang`, `tipe_barang`, `satuan`, `harga_beli`, `harga_jual`, `stok`) VALUES
(1, 'Deterjen Cair Super (Jerigen)', 'Bahan Baku', 'Liter', 50000, 0, 15.00),
(2, 'Pewangi Bunga (Jerigen)', 'Bahan Baku', 'Liter', 10000, 0, 2.00),
(3, 'Deterjen Sachet Sekali Pakai', 'Barang Dijual', 'Sachet', 15000, 5000, 19.00),
(4, 'Plastik Packing Besar', 'Bahan Baku', 'Lembar', 200, 0, 160.00),
(5, 'Pemutih Pakaian (Dirigen)', 'Bahan Baku', 'Liter', 90000, 0, 9.00);

-- --------------------------------------------------------

--
-- Struktur dari tabel `detail_jurnal`
--

CREATE TABLE `detail_jurnal` (
  `id_detail_jurnal` int(11) NOT NULL,
  `id_jurnal` int(11) NOT NULL,
  `kode_akun` varchar(20) NOT NULL,
  `posisi` enum('Debit','Kredit') NOT NULL,
  `nominal` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `detail_jurnal`
--

INSERT INTO `detail_jurnal` (`id_detail_jurnal`, `id_jurnal`, `kode_akun`, `posisi`, `nominal`) VALUES
(1, 1, '1210', 'Debit', 13000000),
(2, 1, '3110', 'Kredit', 13000000),
(3, 2, '1130', 'Debit', 550000),
(4, 2, '1110', 'Kredit', 550000),
(5, 3, '1110', 'Debit', 25000),
(6, 3, '4110', 'Kredit', 20000),
(7, 3, '4120', 'Kredit', 5000),
(8, 4, '1110', 'Debit', 32000),
(9, 4, '4110', 'Kredit', 32000),
(10, 5, '5110', 'Debit', 5000),
(11, 5, '1130', 'Kredit', 5000),
(12, 6, '1130', 'Debit', 40000),
(13, 6, '1110', 'Kredit', 40000),
(14, 7, '1130', 'Debit', 20000),
(15, 7, '1110', 'Kredit', 20000),
(16, 8, '1130', 'Debit', 250000),
(17, 8, '1110', 'Kredit', 250000),
(18, 9, '1130', 'Debit', 150000),
(19, 9, '1110', 'Kredit', 150000),
(20, 10, '5120', 'Debit', 250000),
(21, 10, '1130', 'Kredit', 250000),
(22, 11, '1110', 'Debit', 10000000),
(23, 11, '3110', 'Kredit', 10000000),
(24, 12, '1110', 'Debit', 17500),
(25, 12, '1120', 'Kredit', 17500),
(26, 13, '5120', 'Debit', 140000),
(27, 13, '1130', 'Kredit', 140000),
(28, 14, '1110', 'Debit', 34965),
(29, 14, '1120', 'Kredit', 34965),
(30, 15, '1110', 'Debit', 42000),
(31, 15, '1120', 'Kredit', 42000),
(32, 16, '5120', 'Debit', 197000),
(33, 16, '1130', 'Kredit', 197000),
(34, 17, '1110', 'Debit', 49000),
(35, 17, '1120', 'Kredit', 49000),
(36, 18, '1110', 'Debit', 32500),
(37, 18, '1120', 'Kredit', 32500);

-- --------------------------------------------------------

--
-- Struktur dari tabel `detail_pembelian`
--

CREATE TABLE `detail_pembelian` (
  `id_detail_beli` int(11) NOT NULL,
  `id_pembelian` int(11) NOT NULL,
  `id_barang` int(11) NOT NULL,
  `qty` decimal(10,2) NOT NULL,
  `harga_beli_satuan` int(11) NOT NULL,
  `subtotal` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `detail_pembelian`
--

INSERT INTO `detail_pembelian` (`id_detail_beli`, `id_pembelian`, `id_barang`, `qty`, `harga_beli_satuan`, `subtotal`) VALUES
(1, 1, 1, 20.00, 25000, 500000),
(2, 1, 3, 10.00, 5000, 50000),
(3, 2, 4, 200.00, 200, 40000),
(4, 3, 2, 2.00, 10000, 20000),
(5, 4, 1, 5.00, 50000, 250000),
(6, 5, 3, 10.00, 15000, 150000);

-- --------------------------------------------------------

--
-- Struktur dari tabel `detail_pesanan`
--

CREATE TABLE `detail_pesanan` (
  `id_detail_pesanan` int(11) NOT NULL,
  `id_pesanan` int(11) NOT NULL,
  `id_layanan` varchar(11) DEFAULT NULL,
  `id_barang` int(11) DEFAULT NULL,
  `qty` decimal(10,2) NOT NULL,
  `harga_satuan` int(11) NOT NULL,
  `subtotal` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `detail_pesanan`
--

INSERT INTO `detail_pesanan` (`id_detail_pesanan`, `id_pesanan`, `id_layanan`, `id_barang`, `qty`, `harga_satuan`, `subtotal`) VALUES
(11, 10, 'CC01', NULL, 2.50, 7000, 17500),
(12, 11, 'CK01', NULL, 6.50, 5000, 32500),
(13, 12, 'CC01', NULL, 7.00, 7000, 49000),
(14, 13, 'SK01', NULL, 9.99, 3500, 34965),
(15, 14, 'CC01', NULL, 6.00, 7000, 42000);

-- --------------------------------------------------------

--
-- Struktur dari tabel `jurnal_umum`
--

CREATE TABLE `jurnal_umum` (
  `id_jurnal` int(11) NOT NULL,
  `tanggal_jurnal` date NOT NULL,
  `no_referensi` varchar(50) DEFAULT NULL,
  `tipe_transaksi` enum('Penjualan','Pembelian','Biaya','Penyusutan','Lainnya') NOT NULL,
  `keterangan` varchar(255) NOT NULL,
  `total_transaksi` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `jurnal_umum`
--

INSERT INTO `jurnal_umum` (`id_jurnal`, `tanggal_jurnal`, `no_referensi`, `tipe_transaksi`, `keterangan`, `total_transaksi`) VALUES
(1, '2023-10-01', NULL, 'Lainnya', 'Investasi awal pembelian mesin laundry', 13000000),
(2, '2023-10-25', NULL, 'Pembelian', 'Pembelian persediaan dari Toko Kimia Bersih', 550000),
(3, '2023-10-26', 'HRM-2310-001', 'Penjualan', 'Penjualan self-service tunai', 25000),
(4, '2023-10-26', 'HRM-2310-002', 'Penjualan', 'Pendapatan jasa dari Budi Santoso', 32000),
(5, '2023-10-26', 'HRM-2310-002', 'Biaya', 'Beban pemakaian deterjen', 5000),
(6, '2026-05-05', 'INV-20260505055659', 'Pembelian', 'Pembelian Persediaan (Faktur: INV-20260505055659)', 40000),
(7, '2026-05-05', 'INV-20260505061850', 'Pembelian', 'Pembelian Persediaan (Faktur: INV-20260505061850)', 20000),
(8, '2026-05-05', 'INV-20260505063146', 'Pembelian', 'Pembelian Persediaan (Faktur: INV-20260505063146)', 250000),
(9, '2026-05-05', 'INV-20260505063641', 'Pembelian', 'Pembelian Persediaan (Faktur: INV-20260505063641)', 150000),
(10, '2026-05-05', 'HRM-2310-001', 'Biaya', 'Beban pemakaian Deterjen Cair Super (Jerigen) (Nota: HRM-2310-001)', 250000),
(11, '2026-05-05', NULL, 'Lainnya', 'Suntikan Modal Pemilik', 10000000),
(12, '2026-05-07', 'HRM-260507-320', 'Penjualan', 'Pelunasan Piutang Pelanggan (Nota: HRM-260507-320)', 17500),
(13, '2026-05-12', 'HRM-260512-142', 'Biaya', 'Beban pemakaian Deterjen Cair Super (Jerigen) (Nota: HRM-260512-142)', 140000),
(14, '2026-05-12', 'HRM-260512-976', 'Penjualan', 'Pelunasan Piutang Pelanggan (Nota: HRM-260512-976)', 34965),
(15, '2026-05-12', 'HRM-260512-661', 'Penjualan', 'Pelunasan Piutang Pelanggan (Nota: HRM-260512-661)', 42000),
(16, '2026-05-12', 'REKAP-20260512-0633', 'Biaya', 'Beban pemakaian bahan baku harian (REKAP-20260512-0633)', 197000),
(17, '2026-05-19', 'HRM-260512-801', 'Penjualan', 'Pelunasan Piutang Pelanggan (Nota: HRM-260512-801)', 49000),
(18, '2026-05-19', 'HRM-260512-142', 'Penjualan', 'Pelunasan Piutang Pelanggan (Nota: HRM-260512-142)', 32500);

-- --------------------------------------------------------

--
-- Struktur dari tabel `karyawan`
--

CREATE TABLE `karyawan` (
  `id_karyawan` int(11) NOT NULL,
  `nama_karyawan` varchar(100) NOT NULL,
  `jabatan` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `karyawan`
--

INSERT INTO `karyawan` (`id_karyawan`, `nama_karyawan`, `jabatan`) VALUES
(1, 'Nia', 'Kasir & Administrasi'),
(2, 'Anwar', 'Tukang Cuci Jemur'),
(5, 'Hanina', 'Tukang Setrika dan Packing'),
(6, 'Ilham', 'Tukang Antar Jemput'),
(7, 'Nabila', 'Admin Sosmed'),
(8, 'Ichwan', 'Owner'),
(11, 'Mrs. Badeg', 'Audit Eksternal');

-- --------------------------------------------------------

--
-- Struktur dari tabel `pelanggan`
--

CREATE TABLE `pelanggan` (
  `id_pelanggan` varchar(20) NOT NULL,
  `nama_pelanggan` varchar(100) NOT NULL,
  `no_telepon` varchar(15) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `alamat` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `pelanggan`
--

INSERT INTO `pelanggan` (`id_pelanggan`, `nama_pelanggan`, `no_telepon`, `email`, `alamat`) VALUES
('', 'Aghni', '048949465165', NULL, 'Jl. Tlogosari'),
('1', 'Budi Santoso', '0812345678900', 'ilhamrusyda12@gmail.com', 'Jl. Merdeka No. 10'),
('3', 'Hawa Chiquita', '086756467868', NULL, 'Jl. Tembalang Selatan N. 51'),
('4', 'Ichwan Amrul', '895361229659', 'lommon499@gmail.com', 'Jl. Baskoro'),
('5', 'Mrs. Penguk', '081234572849', NULL, 'Jl. Pemuda No. 3'),
('6', 'Puspita Lestari', '081212345612', NULL, 'Jl. Tembalang Selatan No.15'),
('7', 'Hadziq', '084646546552', 'loommoon9@gmail.com', 'Jl. Baskoro Raya'),
('8', 'Hamzah', '084654646421', NULL, 'Jl. Banyumanik No.11'),
('9', 'Gus wildan', '0846513465550', NULL, 'Jl. Jatimulyo No.02'),
('PLG-055714', 'Ahmad Dani', '08956718299', NULL, 'Jl. Menteng Raya'),
('PLG-055729', 'Yasir', '0897532145753', NULL, 'Jl. Baskara Putra No. 6');

-- --------------------------------------------------------

--
-- Struktur dari tabel `pemakaian_bahan`
--

CREATE TABLE `pemakaian_bahan` (
  `id_pemakaian` int(11) NOT NULL,
  `id_barang` int(11) NOT NULL,
  `qty_dipakai` decimal(10,2) NOT NULL,
  `tanggal_pakai` datetime DEFAULT current_timestamp(),
  `keterangan` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `pemakaian_bahan`
--

INSERT INTO `pemakaian_bahan` (`id_pemakaian`, `id_barang`, `qty_dipakai`, `tanggal_pakai`, `keterangan`) VALUES
(5, 1, 2.80, '2026-05-12 05:54:00', NULL),
(7, 1, 2.00, '2026-05-12 06:33:00', 'REKAP-20260512-0633'),
(8, 4, 35.00, '2026-05-12 06:33:00', 'REKAP-20260512-0633'),
(9, 5, 1.00, '2026-05-12 06:33:00', 'REKAP-20260512-0633');

-- --------------------------------------------------------

--
-- Struktur dari tabel `pembelian`
--

CREATE TABLE `pembelian` (
  `id_pembelian` int(11) NOT NULL,
  `no_faktur_beli` varchar(50) NOT NULL,
  `id_supplier` int(11) NOT NULL,
  `id_karyawan` int(11) NOT NULL,
  `tanggal_beli` datetime DEFAULT current_timestamp(),
  `total_biaya` int(11) NOT NULL,
  `status_pembayaran` enum('Lunas','Hutang') DEFAULT 'Lunas'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `pembelian`
--

INSERT INTO `pembelian` (`id_pembelian`, `no_faktur_beli`, `id_supplier`, `id_karyawan`, `tanggal_beli`, `total_biaya`, `status_pembayaran`) VALUES
(1, 'TKB-INV-00123', 1, 1, '2026-04-09 09:41:41', 550000, 'Lunas'),
(2, 'INV-20260505055659', 5, 6, '2026-05-05 05:56:00', 40000, 'Lunas'),
(3, 'INV-20260505061850', 2, 1, '2026-05-05 06:18:00', 20000, 'Lunas'),
(4, 'INV-20260505063146', 1, 1, '2026-05-05 06:31:00', 250000, 'Lunas'),
(5, 'INV-20260505063641', 1, 2, '2026-05-05 06:36:00', 150000, 'Lunas');

-- --------------------------------------------------------

--
-- Struktur dari tabel `perawatan_aset`
--

CREATE TABLE `perawatan_aset` (
  `id_perawatan` int(11) NOT NULL,
  `id_aset` int(11) NOT NULL,
  `tanggal_perawatan` date NOT NULL,
  `deskripsi_masalah` text NOT NULL,
  `biaya_perbaikan` int(11) DEFAULT 0,
  `pihak_teknisi` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `pesanan`
--

CREATE TABLE `pesanan` (
  `id_pesanan` int(11) NOT NULL,
  `no_nota` varchar(50) NOT NULL,
  `jenis_transaksi` enum('Drop-off','Self-Service') NOT NULL,
  `id_pelanggan` int(11) DEFAULT NULL,
  `id_karyawan` int(11) NOT NULL,
  `tanggal_masuk` datetime DEFAULT current_timestamp(),
  `tanggal_estimasi_selesai` datetime DEFAULT NULL,
  `tanggal_diambil` datetime DEFAULT NULL,
  `status_cucian` enum('Baru','Proses','Selesai','Diambil','Langsung Selesai') NOT NULL,
  `status_pembayaran` enum('Lunas','Belum Lunas') DEFAULT 'Belum Lunas',
  `total_tagihan` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `pesanan`
--

INSERT INTO `pesanan` (`id_pesanan`, `no_nota`, `jenis_transaksi`, `id_pelanggan`, `id_karyawan`, `tanggal_masuk`, `tanggal_estimasi_selesai`, `tanggal_diambil`, `status_cucian`, `status_pembayaran`, `total_tagihan`) VALUES
(10, 'HRM-260507-320', 'Drop-off', 4, 1, '2026-05-07 06:26:00', NULL, NULL, 'Selesai', 'Lunas', 17500),
(11, 'HRM-260512-142', 'Drop-off', 9, 1, '2026-05-12 05:47:00', NULL, NULL, 'Selesai', 'Lunas', 32500),
(12, 'HRM-260512-801', 'Drop-off', 0, 1, '2026-05-12 10:56:00', NULL, NULL, 'Selesai', 'Lunas', 49000),
(13, 'HRM-260512-976', 'Drop-off', 7, 1, '2026-05-12 05:59:00', NULL, NULL, 'Selesai', 'Lunas', 34965),
(14, 'HRM-260512-661', 'Drop-off', 4, 1, '2026-05-12 06:14:00', NULL, NULL, 'Selesai', 'Lunas', 42000);

-- --------------------------------------------------------

--
-- Struktur dari tabel `riwayat_stok`
--

CREATE TABLE `riwayat_stok` (
  `id_riwayat` int(11) NOT NULL,
  `id_barang` int(11) NOT NULL,
  `tanggal` datetime DEFAULT current_timestamp(),
  `jenis_mutasi` enum('Masuk','Keluar') NOT NULL,
  `qty_mutasi` decimal(10,2) NOT NULL,
  `referensi_dokumen` varchar(50) DEFAULT NULL,
  `keterangan` varchar(255) DEFAULT NULL,
  `saldo_akhir` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `riwayat_stok`
--

INSERT INTO `riwayat_stok` (`id_riwayat`, `id_barang`, `tanggal`, `jenis_mutasi`, `qty_mutasi`, `referensi_dokumen`, `keterangan`, `saldo_akhir`) VALUES
(1, 1, '2026-04-09 09:41:42', 'Masuk', 20.00, 'TKB-INV-00123', 'Pembelian dari Toko Kimia Bersih', 20.00),
(2, 3, '2026-04-09 09:41:42', 'Masuk', 10.00, 'TKB-INV-00123', 'Pembelian dari Toko Kimia Bersih', 10.00),
(3, 3, '2026-04-09 09:42:18', 'Keluar', 1.00, 'HRM-2310-001', 'Terjual Eceran', 9.00),
(4, 1, '2026-04-09 09:42:43', 'Keluar', 0.20, 'HRM-2310-002', 'Pemakaian Internal', 19.80),
(5, 4, '2026-05-05 05:56:00', 'Masuk', 200.00, 'INV-20260505055659', 'Pembelian (Faktur: INV-20260505055659)', 200.00),
(6, 4, '2026-05-05 06:04:00', 'Keluar', 3.00, 'HRM-2310-002', 'Pemakaian bahan untuk cucian (Nota: HRM-2310-002)', 197.00),
(7, 2, '2026-05-05 06:18:00', 'Masuk', 2.00, 'INV-20260505061850', 'Pembelian (Faktur: INV-20260505061850)', 2.00),
(8, 1, '2026-05-05 06:31:00', 'Masuk', 5.00, 'INV-20260505063146', 'Pembelian (Faktur: INV-20260505063146)', 24.80),
(9, 4, '2026-05-05 06:34:00', 'Keluar', 2.00, 'HRM-2310-002', 'Pemakaian bahan untuk cucian (Nota: HRM-2310-002)', 195.00),
(10, 3, '2026-05-05 06:36:00', 'Masuk', 10.00, 'INV-20260505063641', 'Pembelian (Faktur: INV-20260505063641)', 19.00),
(11, 1, '2026-05-05 06:41:00', 'Keluar', 5.00, 'HRM-2310-001', 'Pemakaian bahan untuk cucian (Nota: HRM-2310-001)', 19.80),
(12, 1, '2026-05-12 05:54:00', 'Keluar', 2.80, 'HRM-260512-142', 'Pemakaian bahan untuk cucian (Nota: HRM-260512-142)', 17.00),
(13, 1, '2026-05-12 06:33:00', 'Keluar', 2.00, 'REKAP-20260512-0633', 'Pemakaian bahan harian (REKAP-20260512-0633)', 15.00),
(14, 4, '2026-05-12 06:33:00', 'Keluar', 35.00, 'REKAP-20260512-0633', 'Pemakaian bahan harian (REKAP-20260512-0633)', 160.00),
(15, 5, '2026-05-12 06:33:00', 'Keluar', 1.00, 'REKAP-20260512-0633', 'Pemakaian bahan harian (REKAP-20260512-0633)', 9.00);

-- --------------------------------------------------------

--
-- Struktur dari tabel `supplier`
--

CREATE TABLE `supplier` (
  `id_supplier` int(11) NOT NULL,
  `nama_supplier` varchar(100) NOT NULL,
  `no_telepon` varchar(15) DEFAULT NULL,
  `alamat` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `supplier`
--

INSERT INTO `supplier` (`id_supplier`, `nama_supplier`, `no_telepon`, `alamat`) VALUES
(1, 'Toko Kimia Bersih', '081377699739', 'Jl. Jatimulyo No.15, Semarang'),
(2, 'Indo Clean Chemical', '081234567890', 'Jl. Industri No. 12, Jakarta'),
(3, 'Berkah Wangi Parfum', '081987654321', 'Jl. Melati No. 5, Semarang'),
(4, 'Sinar Sabun Grosir', '085511223344', 'Kawasan Rungkut, Semarang'),
(5, 'Lestari Hanger & Plastik', '081255667788', 'Jl. Pandanaran No. 88, Semarang'),
(6, 'Maju Jaya Mesin Laundry', '087799001122', 'Jl. Gatot Subroto No. 45, Demak');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_layanan`
--

CREATE TABLE `tb_layanan` (
  `id_layanan` varchar(10) NOT NULL,
  `nama_layanan` varchar(100) DEFAULT NULL,
  `kategori_layanan` varchar(50) DEFAULT NULL,
  `satuan` varchar(10) DEFAULT NULL,
  `harga_jual` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `tb_layanan`
--

INSERT INTO `tb_layanan` (`id_layanan`, `nama_layanan`, `kategori_layanan`, `satuan`, `harga_jual`) VALUES
('CC01', 'Cuci Cepat express 1 hari', 'Drop-off', 'Kg', 7000),
('CC02', 'Cuci Cepat express 2 hari', 'Drop-off', 'Kg', 6000),
('CK01', 'Cuci Komplit Reguler 3 hari', 'Drop-off', 'Kg', 5000),
('CL01', 'Cuci Lipat Reguler 3 hari', 'Drop-off', 'Kg', 3500),
('SF001', 'Cuci Kering 8Kg', 'Self-Service', 'Load 8Kg', 15000),
('SF002', 'Cuci Kering 10Kg', 'Self-Service', 'Load 10Kg', 20000),
('SK01', 'Setrika Reguler 3 hari', 'Drop-off', 'Kg', 3500);

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_pengguna`
--

CREATE TABLE `tb_pengguna` (
  `id_user` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nama_lengkap` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `tb_pengguna`
--

INSERT INTO `tb_pengguna` (`id_user`, `username`, `password`, `nama_lengkap`) VALUES
(1, 'admin', 'admin123', 'Admin Harum Laundry');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `akun`
--
ALTER TABLE `akun`
  ADD PRIMARY KEY (`kode_akun`);

--
-- Indeks untuk tabel `aset_tetap`
--
ALTER TABLE `aset_tetap`
  ADD PRIMARY KEY (`id_aset`),
  ADD UNIQUE KEY `kode_aset` (`kode_aset`);

--
-- Indeks untuk tabel `barang`
--
ALTER TABLE `barang`
  ADD PRIMARY KEY (`id_barang`);

--
-- Indeks untuk tabel `detail_jurnal`
--
ALTER TABLE `detail_jurnal`
  ADD PRIMARY KEY (`id_detail_jurnal`),
  ADD KEY `id_jurnal` (`id_jurnal`),
  ADD KEY `kode_akun` (`kode_akun`);

--
-- Indeks untuk tabel `detail_pembelian`
--
ALTER TABLE `detail_pembelian`
  ADD PRIMARY KEY (`id_detail_beli`),
  ADD KEY `id_pembelian` (`id_pembelian`),
  ADD KEY `id_barang` (`id_barang`);

--
-- Indeks untuk tabel `detail_pesanan`
--
ALTER TABLE `detail_pesanan`
  ADD PRIMARY KEY (`id_detail_pesanan`),
  ADD KEY `id_pesanan` (`id_pesanan`),
  ADD KEY `id_layanan` (`id_layanan`),
  ADD KEY `id_barang` (`id_barang`),
  ADD KEY `id_pesanan_2` (`id_pesanan`);

--
-- Indeks untuk tabel `jurnal_umum`
--
ALTER TABLE `jurnal_umum`
  ADD PRIMARY KEY (`id_jurnal`);

--
-- Indeks untuk tabel `karyawan`
--
ALTER TABLE `karyawan`
  ADD PRIMARY KEY (`id_karyawan`);

--
-- Indeks untuk tabel `pelanggan`
--
ALTER TABLE `pelanggan`
  ADD PRIMARY KEY (`id_pelanggan`);

--
-- Indeks untuk tabel `pemakaian_bahan`
--
ALTER TABLE `pemakaian_bahan`
  ADD PRIMARY KEY (`id_pemakaian`),
  ADD KEY `id_barang` (`id_barang`);

--
-- Indeks untuk tabel `pembelian`
--
ALTER TABLE `pembelian`
  ADD PRIMARY KEY (`id_pembelian`),
  ADD UNIQUE KEY `no_faktur_beli` (`no_faktur_beli`),
  ADD KEY `id_supplier` (`id_supplier`),
  ADD KEY `id_karyawan` (`id_karyawan`);

--
-- Indeks untuk tabel `perawatan_aset`
--
ALTER TABLE `perawatan_aset`
  ADD PRIMARY KEY (`id_perawatan`),
  ADD KEY `id_aset` (`id_aset`);

--
-- Indeks untuk tabel `pesanan`
--
ALTER TABLE `pesanan`
  ADD PRIMARY KEY (`id_pesanan`),
  ADD UNIQUE KEY `no_nota` (`no_nota`),
  ADD KEY `id_pelanggan` (`id_pelanggan`),
  ADD KEY `id_karyawan` (`id_karyawan`);

--
-- Indeks untuk tabel `riwayat_stok`
--
ALTER TABLE `riwayat_stok`
  ADD PRIMARY KEY (`id_riwayat`),
  ADD KEY `id_barang` (`id_barang`);

--
-- Indeks untuk tabel `supplier`
--
ALTER TABLE `supplier`
  ADD PRIMARY KEY (`id_supplier`);

--
-- Indeks untuk tabel `tb_layanan`
--
ALTER TABLE `tb_layanan`
  ADD PRIMARY KEY (`id_layanan`);

--
-- Indeks untuk tabel `tb_pengguna`
--
ALTER TABLE `tb_pengguna`
  ADD PRIMARY KEY (`id_user`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `aset_tetap`
--
ALTER TABLE `aset_tetap`
  MODIFY `id_aset` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `barang`
--
ALTER TABLE `barang`
  MODIFY `id_barang` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `detail_jurnal`
--
ALTER TABLE `detail_jurnal`
  MODIFY `id_detail_jurnal` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT untuk tabel `detail_pembelian`
--
ALTER TABLE `detail_pembelian`
  MODIFY `id_detail_beli` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT untuk tabel `detail_pesanan`
--
ALTER TABLE `detail_pesanan`
  MODIFY `id_detail_pesanan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT untuk tabel `jurnal_umum`
--
ALTER TABLE `jurnal_umum`
  MODIFY `id_jurnal` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT untuk tabel `karyawan`
--
ALTER TABLE `karyawan`
  MODIFY `id_karyawan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT untuk tabel `pemakaian_bahan`
--
ALTER TABLE `pemakaian_bahan`
  MODIFY `id_pemakaian` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT untuk tabel `pembelian`
--
ALTER TABLE `pembelian`
  MODIFY `id_pembelian` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `perawatan_aset`
--
ALTER TABLE `perawatan_aset`
  MODIFY `id_perawatan` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `pesanan`
--
ALTER TABLE `pesanan`
  MODIFY `id_pesanan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT untuk tabel `riwayat_stok`
--
ALTER TABLE `riwayat_stok`
  MODIFY `id_riwayat` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT untuk tabel `supplier`
--
ALTER TABLE `supplier`
  MODIFY `id_supplier` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT untuk tabel `tb_pengguna`
--
ALTER TABLE `tb_pengguna`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `detail_jurnal`
--
ALTER TABLE `detail_jurnal`
  ADD CONSTRAINT `detail_jurnal_ibfk_1` FOREIGN KEY (`id_jurnal`) REFERENCES `jurnal_umum` (`id_jurnal`) ON DELETE CASCADE,
  ADD CONSTRAINT `detail_jurnal_ibfk_2` FOREIGN KEY (`kode_akun`) REFERENCES `akun` (`kode_akun`);

--
-- Ketidakleluasaan untuk tabel `detail_pembelian`
--
ALTER TABLE `detail_pembelian`
  ADD CONSTRAINT `detail_pembelian_ibfk_1` FOREIGN KEY (`id_pembelian`) REFERENCES `pembelian` (`id_pembelian`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `detail_pesanan`
--
ALTER TABLE `detail_pesanan`
  ADD CONSTRAINT `fk_layanan_baru` FOREIGN KEY (`id_layanan`) REFERENCES `tb_layanan` (`id_layanan`) ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `pemakaian_bahan`
--
ALTER TABLE `pemakaian_bahan`
  ADD CONSTRAINT `pemakaian_bahan_ibfk_2` FOREIGN KEY (`id_barang`) REFERENCES `barang` (`id_barang`);

--
-- Ketidakleluasaan untuk tabel `pembelian`
--
ALTER TABLE `pembelian`
  ADD CONSTRAINT `pembelian_ibfk_1` FOREIGN KEY (`id_supplier`) REFERENCES `supplier` (`id_supplier`),
  ADD CONSTRAINT `pembelian_ibfk_2` FOREIGN KEY (`id_karyawan`) REFERENCES `karyawan` (`id_karyawan`);

--
-- Ketidakleluasaan untuk tabel `perawatan_aset`
--
ALTER TABLE `perawatan_aset`
  ADD CONSTRAINT `perawatan_aset_ibfk_1` FOREIGN KEY (`id_aset`) REFERENCES `aset_tetap` (`id_aset`);

--
-- Ketidakleluasaan untuk tabel `pesanan`
--
ALTER TABLE `pesanan`
  ADD CONSTRAINT `pesanan_ibfk_2` FOREIGN KEY (`id_karyawan`) REFERENCES `karyawan` (`id_karyawan`);

--
-- Ketidakleluasaan untuk tabel `riwayat_stok`
--
ALTER TABLE `riwayat_stok`
  ADD CONSTRAINT `riwayat_stok_ibfk_1` FOREIGN KEY (`id_barang`) REFERENCES `barang` (`id_barang`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
