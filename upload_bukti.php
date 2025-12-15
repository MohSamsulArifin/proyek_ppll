<?php
session_start();
include __DIR__ . '/db/koneksi.php';

// FUNCTION UPLOAD BUKTI PEMBAYARAN
function upbukti($data, $file) {
    global $conn;
    
    $id_orders = intval($data["id_orders"]);
    $status = "Menunggu Konfirmasi";
    
    // Handle file upload
    $nama_file_database = '';
    if (isset($file["bukti"]) && $file["bukti"]["error"] === UPLOAD_ERR_OK) {
        $nama_file = uniqid() . '_' . basename($file["bukti"]["name"]);
        $tujuan = __DIR__ . '/upload/' . $nama_file;
        
        // Create directory if not exists
        if (!is_dir(dirname($tujuan))) {
            mkdir(dirname($tujuan), 0777, true);
        }
        
        if (move_uploaded_file($file["bukti"]["tmp_name"], $tujuan)) {
            $nama_file_database = 'upload/' . $nama_file;
        }
    }
    
    $stmt = $conn->prepare("UPDATE orders SET bukti_pembayaran = ?, status = ? WHERE id_orders = ?");
    $stmt->bind_param("ssi", $nama_file_database, $status, $id_orders);
    
    return $stmt->execute();
}

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
