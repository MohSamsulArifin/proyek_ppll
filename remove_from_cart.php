<?php
session_start();
include __DIR__ . '/db/koneksi.php';

// FUNCTION HAPUS BARANG DARI KERANJANG
function hapusBarang($id_user, $id_barang) {
    global $conn;
    
    $stmt = $conn->prepare("DELETE FROM keranjang WHERE id_user = ? AND id_barang = ?");
    $stmt->bind_param("ii", $id_user, $id_barang);
    return $stmt->execute();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: cart.php');
    exit;
}

if (!isset($_SESSION['id_user'])) {
    header('Location: login.php?redirect=cart.php');
    exit;
}

$id_barang = isset($_POST['id_barang']) ? intval($_POST['id_barang']) : 0;
if ($id_barang <= 0) {
    header('Location: cart.php');
    exit;
}

$ok = hapusBarang(intval($_SESSION['id_user']), $id_barang);

header('Location: cart.php');
exit;
