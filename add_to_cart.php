<?php
session_start();
header('Content-Type: application/json');
include __DIR__.'/db/koneksi.php';

// FUNCTION TAMBAH KERANJANG DENGAN SESSION CHECK
function add_keranjang($data) {
    global $conn;
    
    // Cek session
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    
    if (!isset($_SESSION["id_user"])) {
        return false;
    }
    
    $id = intval($data["id_barang"]);
    $user = intval($_SESSION["id_user"]);
    $qty = intval($data["qty"]);

    $stmt = $conn->prepare("INSERT INTO keranjang (id_barang, id_user, qty) VALUES (?, ?, ?)");
    $stmt->bind_param("iii", $id, $user, $qty);
    
    if ($stmt->execute()) {
        return true;
    }

    // If insert failed because a duplicate key exists, try to increase qty instead
    $errno = mysqli_errno($conn);
    if ($errno == 1062) { // duplicate entry
        $stmt2 = $conn->prepare("UPDATE keranjang SET qty = qty + ? WHERE id_barang = ? AND id_user = ?");
        $stmt2->bind_param('iii', $qty, $id, $user);
        return $stmt2->execute();
    }

    error_log("add_keranjang failed: (".mysqli_errno($conn).") ".mysqli_error($conn));
    return false;
}

// FUNCTION CEK KERANJANG
function cek_keranjang($id_barang, $id_user) {
    global $conn;

    $stmt = $conn->prepare("SELECT * FROM keranjang WHERE id_barang = ? AND id_user = ?");
    $stmt->bind_param("ii", $id_barang, $id_user);
    $stmt->execute();
    $result = $stmt->get_result();

    return $result->num_rows;
}

// FUNCTION HAPUS BARANG DARI KERANJANG
function hapusBarang($id_user, $id_barang) {
    global $conn;
    
    $stmt = $conn->prepare("DELETE FROM keranjang WHERE id_user = ? AND id_barang = ?");
    $stmt->bind_param("ii", $id_user, $id_barang);
    return $stmt->execute();
}

// FUNCTION HAPUS MULTIPLE BARANG
function hapus_barang_dari_keranjang($id_barang) {
    global $conn;
    
    if (empty($id_barang)) return false;
    
    $placeholders = str_repeat('?,', count($id_barang) - 1) . '?';
    $stmt = $conn->prepare("DELETE FROM keranjang WHERE id_barang IN ($placeholders)");
    
    $types = str_repeat('i', count($id_barang));
    $stmt->bind_param($types, ...$id_barang);
    
    return $stmt->execute();
}

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
