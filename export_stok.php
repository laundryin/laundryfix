<?php
include 'koneksi.php';

header("Content-type: application/vnd-ms-excel");
header("Content-Disposition: attachment; filename=Laporan_Stok_".date('Y-m-d').".xls");

$tgl_mulai = @$_GET['tgl_mulai'];
$tgl_selesai = @$_GET['tgl_selesai'];

echo "<h3>LAPORAN RIWAYAT STOK DILAUNDRYIN</h3>";
if($tgl_mulai) echo "Periode: $tgl_mulai s/d $tgl_selesai<br><br>";

echo '<table border="1">
    <tr>
        <th>Waktu</th>
        <th>Barang</th>
        <th>Mutasi</th>
        <th>Qty</th>
        <th>Saldo Akhir</th>
        <th>Referensi</th>
        <th>Keterangan</th>
    </tr>';

$sql = "SELECT r.*, b.nama_barang FROM riwayat_stok r JOIN barang b ON r.id_barang = b.id_barang";
if(!empty($tgl_mulai)) $sql .= " WHERE DATE(r.tanggal) BETWEEN '$tgl_mulai' AND '$tgl_selesai'";
$sql .= " ORDER BY r.tanggal ASC";

$res = mysqli_query($conn, $sql);
while($d = mysqli_fetch_array($res)){
    echo "<tr>
        <td>{$d['tanggal']}</td>
        <td>{$d['nama_barang']}</td>
        <td>{$d['jenis_mutasi']}</td>
        <td>{$d['qty_mutasi']}</td>
        <td>{$d['saldo_akhir']}</td>
        <td>{$d['referensi_dokumen']}</td>
        <td>{$d['keterangan']}</td>
    </tr>";
}
echo '</table>';
?>