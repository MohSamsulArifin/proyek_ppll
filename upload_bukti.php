<?php
session_start();
include __DIR__ . '/db/koneksi.php';

if (!isset($_SESSION['id_user'])) {
    header('HTTP/1.1 401 Unauthorized');
    echo 'Silakan login untuk mengunggah bukti pembayaran.';
    exit;
}

$userId = intval($_SESSION['id_user']);
$orderId = isset($_POST['id_orders']) ? intval($_POST['id_orders']) : 0;
if ($orderId <= 0) {
    header('HTTP/1.1 400 Bad Request');
    echo 'Order tidak valid.';
    exit;
}

// verify ownership
$stmt = $conn->prepare("SELECT id_user FROM orders WHERE id_orders = ? LIMIT 1");
$stmt->bind_param('i', $orderId);
$stmt->execute();
$row = $stmt->get_result()->fetch_assoc();
if (!$row) {
    header('HTTP/1.1 404 Not Found');
    echo 'Order tidak ditemukan.';
    exit;
}

if (intval($row['id_user']) !== $userId) {
    header('HTTP/1.1 403 Forbidden');
    echo 'Anda tidak diizinkan mengunggah bukti untuk pesanan ini.';
    exit;
}

// use upbukti helper
$ok = upbukti(['id_orders' => $orderId], $_FILES);

// redirect back to success page with a simple query flag
if ($ok) {
    header('Location: checkout_success.php?id=' . intval($orderId) . '&uploaded=1');
} else {
    header('Location: checkout_success.php?id=' . intval($orderId) . '&uploaded=0');
}
exit;

?>
