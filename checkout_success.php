<?php
session_start();
include __DIR__ . "/db/koneksi.php";

// Support two flows:
// - if proses_checkout.php redirected with ?order_id=ID (OUR preferred flow)
// - legacy: if POST 'products' present we keep existing fallback (but that path is not used by our updated UI)

$order_id = null;
$name = '';

if (isset($_GET['order_id']) || isset($_GET['id'])) {
    $order_id = intval($_GET['order_id'] ?? $_GET['id']);
    // fetch order and user info for display
    $stmt = $conn->prepare("SELECT o.*, u.nama as user_nama FROM orders o LEFT JOIN tb_user u USING(id_user) WHERE id_orders = ? LIMIT 1");
    $stmt->bind_param('i', $order_id);
    $stmt->execute();
    $orderRow = $stmt->get_result()->fetch_assoc();
    if ($orderRow) {
        $name = $orderRow['user_nama'] ?: $orderRow['pesan'];
        $bukti = $orderRow['bukti_pembayaran'] ?? '';
    }
} elseif (isset($_POST['products'])) {
    // Legacy POST path: keep minimal handling for backwards compatibility
    $user = $_SESSION['id_user'];
    $name = $_POST['name'] ?? '';
    $address = $_POST['address'] ?? '';
    $payment = $_POST['paymentMethod'] ?? '';
    $items = json_decode($_POST['products'], true);

    // try to insert an order using safer column names for this app
    $konfirmasi = 'Belum Terkonfirmasi';
    $status = 'Menunggu Konfirmasi';
    $pesan = "Nama: $name | Alamat: $address | Payment: $payment";

    $stmt = $conn->prepare("INSERT INTO orders (id_user, id_jasa, id_metode, tanggal, pesan, konfirmasi, status, bukti_pembayaran) VALUES (?, 1, 1, NOW(), ?, ?, ?, '')");
    $stmt->bind_param('isss', $user, $pesan, $konfirmasi, $status);
    $stmt->execute();
    $order_id = $stmt->insert_id;

    if ($order_id && is_array($items)) {
        foreach ($items as $item) {
            $id = intval($item['id']);
            $qty = intval($item['qty']);
            $conn->query("INSERT INTO details(id_orders,id_barang,harga_satuan,qty) SELECT $order_id,id_barang,harga,$qty FROM tb_barang WHERE id_barang=$id");
            $conn->query("DELETE FROM keranjang WHERE id_keranjang=$id");
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Checkout Sukses</title>
<style>
body{text-align:center;font-family:'Times New Roman';padding:50px;}
.box{background:#fff6f9;padding:30px;border-radius:15px;display:inline-block;}
a{color:#fff;background:#e91e63;padding:10px 25px;border-radius:20px;text-decoration:none;}
</style>
</head>
<body>

<div class="box">
    <h1>Pesanan Berhasil 🎉</h1>
    <p>Terima kasih <b><?= $name ?></b></p>
    <p>Pesananmu sedang menunggu konfirmasi admin</p>
    <?php if (!empty($bukti)): ?>
        <p><strong>Bukti pembayaran terunggah:</strong></p>
        <p><img src="<?= htmlspecialchars($bukti) ?>" alt="Bukti Pembayaran" style="max-width:300px;max-height:300px;border:1px solid #eee;padding:6px;border-radius:8px;" /></p>
    <?php endif; ?>
    <p><b>ID Pesanan: #<?= $order_id ?></b></p>

    <br>
    <a href="order.php">Lihat Pesanan Saya</a>
    <a href="index.php">Kembali Beranda</a>
</div>

</body>
</html>
