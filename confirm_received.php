<?php
session_start();
include __DIR__ . '/db/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: order.php');
    exit;
}

if (!isset($_SESSION['id_user'])) {
    header('Location: login.php');
    exit;
}

$userId = intval($_SESSION['id_user']);
$orderId = isset($_POST['id_orders']) ? intval($_POST['id_orders']) : 0;
// optional redirect target after confirming
$returnTo = isset($_POST['return_to']) ? $_POST['return_to'] : 'order.php';
// sanitize returnTo: prevent open redirects or directory traversal
if (strpos($returnTo, '://') !== false || strpos($returnTo, '..') !== false) {
    $returnTo = 'order.php';
}

if ($orderId <= 0) {
    header('Location: order.php');
    exit;
}

// Verify ownership and current status
$stmt = $conn->prepare("SELECT id_user, status FROM orders WHERE id_orders = ? LIMIT 1");
$stmt->bind_param('i', $orderId);
$stmt->execute();
$row = $stmt->get_result()->fetch_assoc();

if (!$row || intval($row['id_user']) !== $userId) {
    // Not allowed
    header('Location: order.php');
    exit;
}

if ($row['status'] !== 'Dikirim') {
    // cannot confirm if not shipped
    header('Location: order.php');
    exit;
}

// mark as selesai
if (selesai($orderId)) {
    // success
    header('Location: ' . $returnTo . (stripos($returnTo, '?') === false ? '?':'&') . 'confirmed=1');
    exit;
} else {
    header('Location: ' . $returnTo . (stripos($returnTo, '?') === false ? '?':'&') . 'confirmed=0');
    exit;
}

?>
