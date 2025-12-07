<?php
include '../db/koneksi.php';

$tanggal_awal = $_GET['tanggal_awal'] ?? '';
$tanggal_akhir = $_GET['tanggal_akhir'] ?? '';


// Data pengeluaran
$data = query("SELECT orders.tanggal, SUM(details.harga_satuan * details.qty) AS pemasukan 
                    FROM orders
                    INNER JOIN details using(id_orders)
                    WHERE orders.tanggal BETWEEN '$tanggal_awal' AND '$tanggal_akhir'
                    GROUP BY orders.tanggal");

// Nama file CSV
$filename = 'Pendapatan' . $tanggal_awal . '-' . $tanggal_akhir . '.csv';


// Header untuk memaksa browser untuk mengunduh file
header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="' . $filename . '"');

// File output
$output = fopen('php://output', 'w');

// Header kolom
fputcsv($output, array('Tanggal', 'Pemasukan',));

// Data pengeluaran
foreach ($data as $row) {
    fputcsv($output, $row);
}

fclose($output);
exit;
?>
