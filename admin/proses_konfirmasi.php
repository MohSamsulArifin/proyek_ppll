<?php
include "../db/koneksi.php";

// FUNCTION KONFIRMASI ORDER
function confirm($id_orders) {
    global $conn;
    
    $konfirmasi = "Terkonfirmasi";
    $status = "Diproses";
    
    $stmt = $conn->prepare("UPDATE orders SET konfirmasi = ?, status = ? WHERE id_orders = ?");
    $stmt->bind_param("ssi", $konfirmasi, $status, $id_orders);
    
    return $stmt->execute();
}

// FUNCTION KIRIM ORDER
function kirim($id_orders, $resi = null) {
    global $conn;

    // Allow kirim() to accept either an integer id or an array (POST data)
    if (is_array($id_orders)) {
        $data = $id_orders;
        $id_orders = isset($data['id_orders']) ? intval($data['id_orders']) : 0;
        $resi = isset($data['resi']) ? trim($data['resi']) : $resi;
    }

    $id_orders = intval($id_orders);
    if ($id_orders <= 0) return false;

    // ensure `resi` column exists in orders table; add if missing
    $colCheck = mysqli_query($conn, "SHOW COLUMNS FROM orders LIKE 'resi'");
    if (!$colCheck || mysqli_num_rows($colCheck) === 0) {
        // Add the resi column if not present (best-effort)
        @mysqli_query($conn, "ALTER TABLE orders ADD COLUMN resi VARCHAR(255) DEFAULT NULL");
    }

    $status = "Dikirim";

    if ($resi === null || $resi === '') {
        $stmt = $conn->prepare("UPDATE orders SET status = ? WHERE id_orders = ?");
        $stmt->bind_param("si", $status, $id_orders);
    } else {
        $stmt = $conn->prepare("UPDATE orders SET status = ?, resi = ? WHERE id_orders = ?");
        $stmt->bind_param("ssi", $status, $resi, $id_orders);
    }

    return $stmt->execute();
}

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
