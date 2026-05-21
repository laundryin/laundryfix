<?php
include 'koneksi.php';

// Menentukan nama file
$filename = "Laporan_Jurnal_" . date('Y-m-d') . ".xls";

// Header untuk Force Download Excel
header("Content-type: application/vnd-ms-excel");
header("Content-Disposition: attachment; filename=$filename");

$tgl_mulai = isset($_GET['tgl_mulai']) ? $_GET['tgl_mulai'] : '';
$tgl_selesai = isset($_GET['tgl_selesai']) ? $_GET['tgl_selesai'] : '';

echo "<h3>LAPORAN JURNAL UMUM DILAUNDRYIN</h3>";
if($tgl_mulai) echo "Periode: $tgl_mulai s/d $tgl_selesai <br><br>";

echo '<table border="1">
    <tr>
        <th bgcolor="#f2f2f2">Tanggal</th>
        <th bgcolor="#f2f2f2">Keterangan</th>
        <th bgcolor="#f2f2f2">Kode Akun</th>
        <th bgcolor="#f2f2f2">Nama Akun</th>
        <th bgcolor="#f2f2f2">Debit</th>
        <th bgcolor="#f2f2f2">Kredit</th>
    </tr>';

$sql = "SELECT j.tanggal_jurnal, j.keterangan, dj.kode_akun, a.nama_akun, dj.nominal, dj.posisi 
        FROM jurnal_umum j 
        JOIN detail_jurnal dj ON j.id_jurnal = dj.id_jurnal 
        JOIN akun a ON dj.kode_akun = a.kode_akun";

if(!empty($tgl_mulai)) {
    $sql .= " WHERE j.tanggal_jurnal BETWEEN '$tgl_mulai' AND '$tgl_selesai'";
}
$sql .= " ORDER BY j.tanggal_jurnal ASC";

$res = mysqli_query($conn, $sql);
while($row = mysqli_fetch_array($res)){
    $debit = ($row['posisi'] == 'Debit') ? $row['nominal'] : 0;
    $kredit = ($row['posisi'] == 'Kredit') ? $row['nominal'] : 0;
    
    echo "<tr>
        <td>{$row['tanggal_jurnal']}</td>
        <td>{$row['keterangan']}</td>
        <td>{$row['kode_akun']}</td>
        <td>{$row['nama_akun']}</td>
        <td>$debit</td>
        <td>$kredit</td>
    </tr>";
}
echo '</table>';
?>