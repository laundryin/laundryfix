<?php 
include 'koneksi.php'; 

// Load PHPMailer Manual jalur kuli
require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Fungsi Terbilang titipan dosen lu
function terbilang($x){
    $abil = array("", "Satu", "Dua", "Tiga", "Empat", "Lima", "Enam", "Tujuh", "Delapan", "Sembilan", "Sepuluh", "Sebelas");
    if ($x < 12) return " " . $abil[$x];
    elseif ($x < 20) return terbilang($x - 10) . " Belas"; 
    elseif ($x < 100) return terbilang($x / 10) . " Puluh" . terbilang($x % 10);
    elseif ($x < 200) return " Seratus" . terbilang($x - 100);
    elseif ($x < 1000) return terbilang($x / 100) . " Ratus" . terbilang($x % 100);
    elseif ($x < 2000) return " Seribu" . terbilang($x - 1000);
    elseif ($x < 1000000) return terbilang($x / 1000) . " Ribu" . terbilang($x % 1000);
    elseif ($x < 1000000000) return terbilang($x / 1000000) . " Juta" . terbilang($x % 1000000);
}

// Fungsi Helper Kirim Email
function kirimEmailNotif($email_tujuan, $nama_pelanggan, $subject, $body_html) {
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'enduw.laundryin@gmail.com'; 
        $mail->Password   = 'skthyomekyryixhr'; // MASUKIN APP PASSWORD LU DISINI
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port       = 465;

        $mail->setFrom('enduw.laundryin@gmail.com', 'Harum Laundry ERP');
        $mail->addAddress($email_tujuan, $nama_pelanggan); 
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $body_html;
        $mail->send();
        return true;
    } catch (Exception $e) {
        return false;
    }
}

$id_pesanan = $_GET['id'];

// Ambil data lama + JOIN ke pelanggan buat dapet emailnya
$q = mysqli_query($conn, "SELECT p.*, pel.nama_pelanggan, pel.email FROM pesanan p LEFT JOIN pelanggan pel ON p.id_pelanggan = pel.id_pelanggan WHERE p.id_pesanan = '$id_pesanan'");
$d = mysqli_fetch_array($q);

// Simpen status yang lama buat perbandingan trigger email
$status_cucian_lama = $d['status_cucian'];
$status_bayar_lama = $d['status_pembayaran'];

if(isset($_POST['update'])) {
    $status_cucian_baru = mysqli_real_escape_string($conn, $_POST['status_cucian']);
    $status_bayar_baru  = mysqli_real_escape_string($conn, $_POST['status_pembayaran']);
    $pesan_alert = "Status pesanan berhasil di-update!";

    // Update status di database
    $update = mysqli_query($conn, "UPDATE pesanan SET status_cucian = '$status_cucian_baru', status_pembayaran = '$status_bayar_baru' WHERE id_pesanan = '$id_pesanan'");
    
    if($update) {
        $email_customer = $d['email'];
        $nama_customer = $d['nama_pelanggan'] ? $d['nama_pelanggan'] : 'Pelanggan';
        $no_nota = $d['no_nota'];
        $total = number_format($d['total_tagihan'], 0, ',', '.');
        $terbilang_txt = trim(terbilang($d['total_tagihan'])) . " Rupiah";

        // ==========================================
        // TRIGGER 1: EMAIL CUCIAN SELESAI
        // ==========================================
        if($status_cucian_lama != 'Selesai' && $status_cucian_baru == 'Selesai' && !empty($email_customer)) {
            $subj_selesai = "Yeay! Cucian Kamu Udah Selesai - $no_nota";
            $body_selesai = "
                <div style='font-family: sans-serif; padding: 20px; border: 1px solid #ddd; border-radius: 10px; max-width: 500px;'>
                    <h2 style='color: #10b981;'>Cucian Udah Wangi! 🌸</h2>
                    <p>Halo Kak <b>$nama_customer</b>,</p>
                    <p>Cucian kamu dengan nomor nota <b>$no_nota</b> udah kelar dicuci dan disetrika nih. Udah siap diambil ya di toko kami.</p>
                    <p>Status Tagihan: <b>$status_bayar_baru</b></p>
                    <hr>
                    <p style='font-size: 12px; color: #888;'>Harum Laundry ERP - Sistem Londriin</p>
                </div>
            ";
            if(kirimEmailNotif($email_customer, $nama_customer, $subj_selesai, $body_selesai)){
                $pesan_alert .= "\\n- Email notif cucian selesai sukses terkirim.";
            }
        }

        // ==========================================
        // TRIGGER 2: EMAIL KWITANSI & JURNAL PELUNASAN
        // ==========================================
        if($status_bayar_lama == 'Belum Lunas' && $status_bayar_baru == 'Lunas') {
            
            // JURNAL OTOMATIS: Balik Piutang (1120) ke Kas (1110)
            $ket_jurnal = "Pelunasan Piutang Pelanggan (Nota: $no_nota)";
            mysqli_query($conn, "INSERT INTO jurnal_umum (tanggal_jurnal, no_referensi, tipe_transaksi, keterangan, total_transaksi) VALUES (DATE(NOW()), '$no_nota', 'Penjualan', '$ket_jurnal', '{$d['total_tagihan']}')");
            $id_jurnal = mysqli_insert_id($conn);
            
            mysqli_query($conn, "INSERT INTO detail_jurnal (id_jurnal, kode_akun, posisi, nominal) VALUES ('$id_jurnal', '1110', 'Debit', '{$d['total_tagihan']}')");
            mysqli_query($conn, "INSERT INTO detail_jurnal (id_jurnal, kode_akun, posisi, nominal) VALUES ('$id_jurnal', '1120', 'Kredit', '{$d['total_tagihan']}')");

            $pesan_alert .= "\\n- Jurnal Pelunasan Piutang otomatis tercatat.";

            // Kirim Email Kwitansi Resmi
            if(!empty($email_customer)) {
                $subj_lunas = "Kwitansi Pembayaran Resmi - $no_nota";
                $body_lunas = "
                    <div style='font-family: monospace; max-width: 350px; padding: 20px; border: 2px dashed #10b981; background-color: #f0fdf4; color: #064e3b;'>
                        <div style='text-align: center; margin-bottom: 20px;'>
                            <h3 style='margin: 0; color: #047857;'>-=KWITANSI LUNAS=-</h3>
                            <p style='margin: 0;'>HARUM LAUNDRY</p>
                        </div>
                        <p style='margin: 5px 0;'>No. Nota : $no_nota</p>
                        <p style='margin: 5px 0;'>Pelanggan: Kak $nama_customer</p>
                        <p style='margin: 5px 0;'>Tgl Bayar: " . date('d F Y') . "</p>
                        <hr style='border: 1px dashed #10b981;' />
                        <h2 style='margin: 10px 0 0 0; color: #059669;'>TELAH DIBAYAR: Rp $total</h2>
                        <p style='margin: 5px 0 15px 0; font-size: 11px; font-style: italic;'>Terbilang: $terbilang_txt</p>
                        <hr style='border: 1px dashed #10b981;' />
                        <p style='text-align: center; font-size: 11px;'>Terima kasih atas pembayarannya!</p>
                    </div>
                ";
                if(kirimEmailNotif($email_customer, $nama_customer, $subj_lunas, $body_lunas)){
                    $pesan_alert .= "\\n- Email kwitansi pelunasan sukses terkirim.";
                }
            }
        }

        echo "<script>alert('$pesan_alert'); window.location='pesanan.php';</script>";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Update Status Pesanan - Harum Laundry</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    
    <style>
        body { font-family: 'Poppins', sans-serif; background-color: #fdf2f8; color: #334155; }
        
        /* CSS SIDEBAR */
        .sidebar { width: 260px; height: 100vh; position: fixed; background: #fff; border-right: 1px solid #fce7f3; z-index: 100; overflow-y: auto; top: 0; left: 0;}
        .sidebar-brand { padding: 1.5rem; text-align: center; border-bottom: 1px solid #fce7f3; }
        .sidebar-brand img { max-width: 150px; }
        .nav-link { color: #64748b; font-weight: 500; padding: 10px 20px; border-radius: 10px; margin: 4px 15px; transition: 0.3s; font-size: 0.9rem; text-decoration: none; display: block;}
        .nav-link:hover, .nav-link.active { background: #fdf2f8; color: #ec4899 !important; border-left: 4px solid #ec4899; }
        .nav-link i { margin-right: 10px; font-size: 1.1rem; }
        .menu-title { font-size: 0.7rem; font-weight: 700; color: #475569; padding: 0 30px; margin-top: 20px; margin-bottom: 5px; text-transform: uppercase;}
        .sidebar::-webkit-scrollbar { width: 5px; }
        .sidebar::-webkit-scrollbar-thumb { background: #fbcfe8; border-radius: 10px; }

        /* CSS MAIN CONTENT & FORM */
        .main-content { margin-left: 260px; padding: 40px; }
        .card-form { border-radius: 20px; border: none; background: white; padding: 40px; box-shadow: 0 10px 30px rgba(236, 72, 153, 0.05); }
        .form-control, .form-select { border-radius: 10px; padding: 12px 15px; border: 1px solid #e2e8f0; font-size: 0.95rem; }
        .form-control:focus, .form-select:focus { border-color: #10b981; box-shadow: 0 0 0 0.25rem rgba(16, 185, 129, 0.1); }
        .form-label { font-weight: 500; color: #475569; font-size: 0.9rem; margin-bottom: 8px; }
        
        /* Box buat info nota biar elegan */
        .info-box { background-color: #f8fafc; border-radius: 15px; padding: 20px; border: 1px solid #f1f5f9; margin-bottom: 25px;}
    </style>
</head>
<body>

    <?php include 'sidebar.php'; ?>

    <div class="main-content">
        <div class="container" style="max-width: 650px; margin: 0;">
            <div class="card-form">
                
                <div class="d-flex align-items-center mb-4 pb-3 border-bottom">
                    <div class="bg-success bg-opacity-10 text-success rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px;">
                        <i class="bi bi-pencil-square fs-4"></i>
                    </div>
                    <h3 class="fw-bold mb-0" style="color: #10b981;">Update Status Pesanan</h3>
                </div>

                <form method="POST">
                    
                    <div class="info-box">
                        <div class="row align-items-center">
                            <div class="col-6 border-end">
                                <span class="text-muted d-block mb-1" style="font-size: 0.8rem;"><i class="bi bi-receipt me-1"></i> NO. NOTA</span>
                                <span class="fw-bold fs-5 text-dark"><?= htmlspecialchars($d['no_nota']); ?></span>
                            </div>
                            <div class="col-6 ps-4">
                                <span class="text-muted d-block mb-1" style="font-size: 0.8rem;"><i class="bi bi-cash me-1"></i> TOTAL TAGIHAN</span>
                                <span class="fw-bold fs-5 text-primary">Rp <?= number_format($d['total_tagihan'], 0, ',', '.'); ?></span>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Status Cucian</label>
                        <select name="status_cucian" class="form-select" required>
                            <option value="Proses" <?= ($d['status_cucian'] == 'Proses') ? 'selected' : ''; ?>>Proses</option>
                            <option value="Selesai" <?= ($d['status_cucian'] == 'Selesai') ? 'selected' : ''; ?>>Selesai</option>
                            </select>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Status Pembayaran</label>
                        <select name="status_pembayaran" class="form-select" required>
                            <option value="Belum Lunas" <?= ($d['status_pembayaran'] == 'Belum Lunas') ? 'selected' : ''; ?>>Belum Lunas</option>
                            <option value="Lunas" <?= ($d['status_pembayaran'] == 'Lunas') ? 'selected' : ''; ?>>Lunas</option>
                        </select>
                    </div>

                    <div class="d-flex gap-3 mt-4 pt-3 justify-content-end border-top">
                        <a href="pesanan.php" class="btn btn-light px-4" style="border-radius: 10px; font-weight: 500;">Batal</a>
                        <button type="submit" name="update" class="btn btn-success px-5 shadow-sm" style="border-radius: 10px; font-weight: 500;">
                            <i class="bi bi-check2-circle me-2"></i>Simpan Perubahan
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>

</body>
</html>