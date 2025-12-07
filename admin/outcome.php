<?php
include '../db/koneksi.php';

$tanggal_awal = $_GET['tanggal_awal'] ?? '';
$tanggal_akhir = $_GET['tanggal_akhir'] ?? '';


// Data pengeluaran
$data = query("SELECT 
            tb_pengeluaran.tanggal, 
            tb_pengeluaran.nama_pengeluaran AS nama_barang,
            tb_pengeluaran.harga, 
            tb_pengeluaran.qty,
            SUM(tb_pengeluaran.harga * tb_pengeluaran.qty) AS pengeluaran 
        FROM tb_pengeluaran
        WHERE tb_pengeluaran.tanggal BETWEEN '$tanggal_awal' AND '$tanggal_akhir'
        GROUP BY tb_pengeluaran.tanggal");

$data = query("SELECT orders.tanggal, ");




// Nama file CSV
$filename = 'Pengeluaran' . $tanggal_awal . '-' . $tanggal_akhir . '.csv';


// Header untuk memaksa browser untuk mengunduh file
header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="' . $filename . '"');

// File output
$output = fopen('php://output', 'w');

// Header kolom
fputcsv($output, array('Tanggal', 'Product', 'Harga Beli', 'Qty','Pengeluaran'));

// Data pengeluaran
foreach ($data as $row) {
    fputcsv($output, $row);
}

fclose($output);
exit;
?>
