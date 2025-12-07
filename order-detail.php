<?php
session_start();
include __DIR__ . '/db/koneksi.php';

// Pastikan user sudah login
if (!isset($_SESSION['id_user'])) {
    header('Location: login.php?redirect=order-detail.php?id=' . ($_GET['id'] ?? ''));
    exit;
}

$userId = intval($_SESSION['id_user']);
$orderId = intval($_GET['id'] ?? 0);

if ($orderId <= 0) {
    die("ID pesanan tidak valid.");
}

// Ambil detail pesanan dari database
$stmt = $conn->prepare("SELECT * FROM orders WHERE id_orders = ? AND id_user = ? LIMIT 1");
$stmt->bind_param('ii', $orderId, $userId);
$stmt->execute();
$order = $stmt->get_result()->fetch_assoc();

if (!$order) {
    die("Pesanan tidak ditemukan atau bukan milik Anda.");
}

// Use rupiah() helper from db/koneksi.php

?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Detail Pesanan - Beauty Store</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<style>
body { font-family: 'Times New Roman', serif; margin:0; background:#fff; color:#333; }
.top-bar { background:#ffebf2; color:#e91e63; text-align:center; padding:8px 0; font-weight:bold; }
.navbar { display:flex; justify-content:space-between; align-items:center; padding:15px 30px; background:#fff; box-shadow:0 2px 5px rgba(0,0,0,0.1); }
.navbar .logo { font-size:24px; font-weight:bold; color:#e91e63; }
.navbar ul { display:flex; list-style:none; gap:25px; margin:0; padding:0; }
.navbar ul li a { text-decoration:none; color:#333; font-weight:500; }
.navbar ul li a:hover { color:#e91e63; }
.container { max-width:1100px; margin:auto; padding:30px 15px; }
.order-box { background:#fff6f9; border:1px solid #ffd6e7; border-radius:15px; padding:20px; display:flex; gap:20px; flex-wrap:wrap; }
.order-box img { width:220px; height:220px; object-fit:contain; border:1px solid #ffe5ef; background:#fff; border-radius:10px; padding:10px; }
h2 { color:#e91e63; }
h3 { margin:0; color:#e91e63; }
.status { display:inline-block; background:#ffe0ec; padding:6px 14px; border-radius:15px; margin:10px 0; }
</style>
</head>
<body>

<div class="top-bar">🌸 Beauty Store 🌸</div>

<div class="navbar">
    <div class="logo">Beauty Store</div>
    <ul>
        <li><a href="pesanan.php">Pesanan</a></li>
        <li><a href="products.php">Produk</a></li>
    </ul>
</div>

<div class="container">
    <h2>Detail Pesanan</h2>
    <div class="order-box">
        <div style="width:100%;">
            <h3>Pesanan #<?= $order['id_orders'] ?></h3>
            <span class="status"><?= htmlspecialchars($order['status']) ?></span>
            <p><strong>Tanggal Pesan:</strong> <?= htmlspecialchars(date('d M Y', strtotime($order['tanggal']))) ?></p>
            <p><strong>Alamat:</strong> <?= htmlspecialchars($order['alamat'] ?: '-') ?></p>
            <p><strong>Catatan:</strong> <?= htmlspecialchars($order['catatan'] ?: '-') ?></p>

            <?php if (!empty($order['bukti_pembayaran'])): ?>
                <div style="margin-top:10px;">
                    <strong>Bukti Pembayaran:</strong><br>
                    <img src="<?= htmlspecialchars($order['bukti_pembayaran']) ?>" alt="Bukti Pembayaran" style="max-width:300px;max-height:300px;border:1px solid #eee;padding:6px;border-radius:6px;" />
                </div>
            <?php endif; ?>

            <hr />
            <h4>Item Pesanan</h4>
            <?php
                $stmt2 = $conn->prepare("SELECT d.*, b.nama_barang, b.harga AS harga_produk, b.img FROM details d JOIN tb_barang b ON d.id_barang = b.id_barang WHERE d.id_orders = ?");
                $stmt2->bind_param('i', $orderId);
                $stmt2->execute();
                $rows = $stmt2->get_result()->fetch_all(MYSQLI_ASSOC);
                $total = 0;
                foreach ($rows as $r) {
                    $line = $r['harga_satuan'] * $r['qty'];
                    $total += $line;
            ?>
                <div style="display:flex;gap:15px;padding:10px 0;align-items:center;">
                    <img src="<?= htmlspecialchars($r['img'] ?: 'assets/icon/makeup.png') ?>" width="100" height="100" style="object-fit:contain;" />
                    <div>
                        <div style="font-weight:600"><?= htmlspecialchars($r['nama_barang']) ?></div>
                        <div>Jumlah: <?= intval($r['qty']) ?></div>
                        <div>Harga satuan: <?= rupiah($r['harga_satuan']) ?></div>
                        <div>Subtotal: <?= rupiah($line) ?></div>
                    </div>
                </div>
            <?php } ?>

            <div style="text-align:right;margin-top:10px;font-weight:700">Total Pesanan: <?= rupiah($total) ?></div>

            <?php if ($order['status'] === 'Dikirim'): ?>
                <form method="post" action="confirm_received.php" onsubmit="return confirm('Konfirmasi pesanan sudah diterima?');">
                    <input type="hidden" name="id_orders" value="<?= $order['id_orders'] ?>">
                    <input type="hidden" name="return_to" value="order-detail.php?id=<?= $order['id_orders'] ?>">
                    <button type="submit" class="view-btn" style="background:#28a745;border:none;margin-top:12px;">Konfirmasi Sudah Diterima</button>
                </form>
            <?php endif; ?>
        </div>
    </div>
</div>

</body>
</html>
