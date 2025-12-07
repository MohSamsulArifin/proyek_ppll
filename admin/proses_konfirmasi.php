<?php
include "../db/koneksi.php";

if (!isset($_GET['id'])) {
    header('Location: konfirmasi.php');
    exit;
}

$id = intval($_GET['id']);

if (confirm($id)) {
    echo "<script>alert('Pesanan berhasil dikonfirmasi'); window.location.href='konfirmasi.php';</script>";
} else {
    echo "<script>alert('Gagal mengonfirmasi pesanan'); window.location.href='konfirmasi.php';</script>";
}

?>
