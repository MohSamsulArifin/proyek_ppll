<?php
header('Content-Type: application/json; charset=utf-8');
include __DIR__ . '/db/koneksi.php';
if (session_status() === PHP_SESSION_NONE) session_start();

if (!isset($_SESSION['id_user'])) {
    echo json_encode(['success' => true, 'cartCount' => 0]);
    exit;
}

$uid = intval($_SESSION['id_user']);
$stmt = $conn->prepare("SELECT COALESCE(SUM(qty),0) as total FROM keranjang WHERE id_user = ?");
$stmt->bind_param('i', $uid);
$stmt->execute();
$res = $stmt->get_result()->fetch_assoc();
$count = intval($res['total'] ?? 0);

echo json_encode(['success' => true, 'cartCount' => $count]);
