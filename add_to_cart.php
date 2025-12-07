<?php
session_start();
header('Content-Type: application/json');
include __DIR__.'/db/koneksi.php';

// pastikan login
if(!isset($_SESSION['id_user'])){
    echo json_encode(["success"=>false,"message"=>"Silakan login terlebih dahulu."]);
    exit;
}

$userId = intval($_SESSION['id_user']);
$id_barang = isset($_POST['id_barang']) ? intval($_POST['id_barang']) : 0;

if($id_barang <= 0){
    echo json_encode(["success"=>false,"message"=>"ID produk tidak valid."]);
    exit;
}

// cek apakah barang sudah ada di keranjang
$stmt = $conn->prepare("SELECT qty FROM keranjang WHERE id_user=? AND id_barang=?");
$stmt->bind_param("ii",$userId,$id_barang);
$stmt->execute();
$res = $stmt->get_result()->fetch_assoc();

if($res){
    $qty = $res['qty'] + 1;
    $stmt2 = $conn->prepare("UPDATE keranjang SET qty=? WHERE id_user=? AND id_barang=?");
    $stmt2->bind_param("iii",$qty,$userId,$id_barang);
    $stmt2->execute();
} else {
    $stmt2 = $conn->prepare("INSERT INTO keranjang(id_user,id_barang,qty) VALUES(?,?,1)");
    $stmt2->bind_param("ii",$userId,$id_barang);
    $stmt2->execute();
}

// hitung total item di keranjang
$stmt3 = $conn->prepare("SELECT SUM(qty) as total FROM keranjang WHERE id_user=?");
$stmt3->bind_param("i",$userId);
$stmt3->execute();
$total = $stmt3->get_result()->fetch_assoc()['total'] ?? 0;

echo json_encode(["success"=>true,"message"=>"Produk berhasil ditambahkan ke keranjang.","cartCount"=>$total]);
