<?php
header('Content-Type: application/json; charset=utf-8');
include __DIR__ . '/db/koneksi.php';
if (session_status() === PHP_SESSION_NONE) session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

if (!isset($_SESSION['id_user'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Silakan login terlebih dahulu']);
    exit;
}

$id_barang = isset($_POST['id_barang']) ? intval($_POST['id_barang']) : 0;
$qty = isset($_POST['qty']) ? intval($_POST['qty']) : 0;
$uid = intval($_SESSION['id_user']);

if ($id_barang <= 0 || $qty < 0) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Data tidak valid']);
    exit;
}

// check stock
$stmt = $conn->prepare("SELECT stok, harga FROM tb_barang WHERE id_barang = ? LIMIT 1");
$stmt->bind_param('i', $id_barang);
$stmt->execute();
$row = $stmt->get_result()->fetch_assoc();
if (!$row) {
    http_response_code(404);
    echo json_encode(['success' => false, 'message' => 'Produk tidak ditemukan']);
    exit;
}

$stok = intval($row['stok']);
$harga = floatval($row['harga']);
if ($qty > $stok) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Stok tidak mencukupi (tersisa '.$stok.')']);
    exit;
}

if ($qty === 0) {
    // remove item
    $stmt = $conn->prepare("DELETE FROM keranjang WHERE id_user = ? AND id_barang = ?");
    $stmt->bind_param('ii', $uid, $id_barang);
    $ok = $stmt->execute();
} else {
    $stmt = $conn->prepare("UPDATE keranjang SET qty = ? WHERE id_user = ? AND id_barang = ?");
    $stmt->bind_param('iii', $qty, $uid, $id_barang);
    $ok = $stmt->execute();
}

if (!$ok) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Gagal memperbarui keranjang']);
    exit;
}

// compute new subtotal for item and cart totals
$itemSubtotal = $harga * $qty;

$stmt = $conn->prepare("SELECT COALESCE(SUM(qty),0) as totalQty, COALESCE(SUM(qty*b.harga),0) as totalPrice FROM keranjang k JOIN tb_barang b USING(id_barang) WHERE k.id_user = ?");
$stmt->bind_param('i', $uid);
$stmt->execute();
$agg = $stmt->get_result()->fetch_assoc();
$cartCount = intval($agg['totalQty'] ?? 0);
$cartTotal = floatval($agg['totalPrice'] ?? 0);

echo json_encode(['success' => true, 'itemSubtotal' => $itemSubtotal, 'cartCount' => $cartCount, 'cartTotal' => $cartTotal]);

exit;
