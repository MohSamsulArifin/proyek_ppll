<?php
session_start();
include __DIR__ . '/db/koneksi.php';

// Pastikan user login
if (!isset($_SESSION['id_user'])) {
    header('Location: login.php');
    exit;
}

$userId = intval($_SESSION['id_user']);

// Pastikan ada data dari checkout (id_keranjang[])
if (!isset($_POST['id_keranjang']) || empty($_POST['id_keranjang'])) {
    header('Location: cart.php');
    exit;
}

$cartIds = $_POST['id_keranjang'];
$cartIds = array_map('intval', $cartIds);

// Ambil data keranjang + tb_barang
$placeholders = implode(',', array_fill(0, count($cartIds), '?'));
$types = str_repeat('i', count($cartIds)) . 'i';
$params = array_merge($cartIds, [$userId]);

$stmt = $conn->prepare("SELECT k.id_keranjang, k.id_barang, k.qty, b.harga FROM keranjang k JOIN tb_barang b ON k.id_barang = b.id_barang WHERE k.id_keranjang IN ($placeholders) AND k.id_user = ?");
$stmt->bind_param($types, ...$params);
$stmt->execute();
$checkoutItems = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

if (count($checkoutItems) === 0) {
    header('Location: cart.php');
    exit;
}

// compute totals
$total = 0;
foreach ($checkoutItems as $item) {
    $total += floatval($item['harga']) * intval($item['qty']);
}

// read form fields
$name = isset($_POST['name']) ? mysqli_real_escape_string($conn, $_POST['name']) : '';
$address = isset($_POST['address']) ? mysqli_real_escape_string($conn, $_POST['address']) : '';
$paymentMethod = isset($_POST['paymentMethod']) ? mysqli_real_escape_string($conn, $_POST['paymentMethod']) : '';

// set default shipping/method (you can adjust to accept values from client)
$id_jasa = isset($_POST['id_jasa']) ? intval($_POST['id_jasa']) : 1;
$id_metode = isset($_POST['id_metode']) ? intval($_POST['id_metode']) : 1;

// pesan column store address + name
$pesan = "Nama: $name | Alamat: $address | Payment: $paymentMethod";

// default konfirmasi & status
$konfirmasi = 'Belum Terkonfirmasi';
$status = 'Menunggu Konfirmasi';

// Insert order
$stmtOrder = $conn->prepare("INSERT INTO orders (id_user, id_jasa, id_metode, tanggal, pesan, konfirmasi, status, bukti_pembayaran) VALUES (?, ?, ?, NOW(), ?, ?, ?, '')");
$stmtOrder->bind_param('iiisss', $userId, $id_jasa, $id_metode, $pesan, $konfirmasi, $status);

if (!$stmtOrder->execute()) {
    error_log('Gagal membuat order: ' . $stmtOrder->error);
    header('Location: cart.php');
    exit;
}

$orderId = $stmtOrder->insert_id;

// Insert details rows
$stmtDetail = $conn->prepare("INSERT INTO details (id_orders, id_barang, harga_satuan, qty) VALUES (?, ?, ?, ?)");
foreach ($checkoutItems as $it) {
    $bid = intval($it['id_barang']);
    $qty = intval($it['qty']);
    $harga = floatval($it['harga']);
    $stmtDetail->bind_param('iidi', $orderId, $bid, $harga, $qty);
    $stmtDetail->execute();
}

// If user uploaded payment proof during checkout, process it and update order
if (isset($_FILES['bukti']) && $_FILES['bukti']['error'] !== UPLOAD_ERR_NO_FILE) {
    // use upbukti helper which expects id_orders in data and full $_FILES array
    $up = upbukti(['id_orders' => $orderId], $_FILES);
    // pass upload status along in redirect if needed
    $uploadedFlag = $up ? 1 : 0;
} else {
    $uploadedFlag = null;
}

// Delete items from keranjang
$deletePlaceholders = implode(',', array_fill(0, count($cartIds), '?'));
$typesDelete = str_repeat('i', count($cartIds)) . 'i';
$paramsDelete = array_merge($cartIds, [$userId]);
$stmtDelete = $conn->prepare("DELETE FROM keranjang WHERE id_keranjang IN ($deletePlaceholders) AND id_user = ?");
$stmtDelete->bind_param($typesDelete, ...$paramsDelete);
$stmtDelete->execute();

// redirect to success (with uploaded flag when applicable)
if ($uploadedFlag !== null) {
    header("Location: checkout_success.php?order_id=$orderId&uploaded={$uploadedFlag}");
} else {
    header("Location: checkout_success.php?order_id=$orderId");
}
exit;
