<?php
session_start();
include __DIR__ . '/db/koneksi.php';

// Pastikan user login
if (!isset($_SESSION['id_user'])) {
    header('Location: login.php');
    exit;
}

// Ambil id_keranjang terpilih dari query string
if (!isset($_GET['ids'])) {
    header('Location: cart.php');
    exit;
}

$selectedIds = explode(',', $_GET['ids']);
$selectedIds = array_map('intval', $selectedIds);

// Fetch selected cart rows and corresponding product info
$placeholders = implode(',', array_fill(0, count($selectedIds), '?'));
$types = str_repeat('i', count($selectedIds));
$stmt = $conn->prepare("SELECT k.id_keranjang, k.id_barang, k.qty, b.nama_barang, b.harga, b.img FROM keranjang k JOIN tb_barang b ON k.id_barang=b.id_barang WHERE k.id_keranjang IN ($placeholders)");
// bind params dynamically
$refs = [];
foreach ($selectedIds as $i => $val) {
    $refs[$i] = &$selectedIds[$i];
}
array_unshift($refs, $types);
call_user_func_array([$stmt, 'bind_param'], $refs);
$stmt->execute();
$checkoutRows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

// Use rupiah() helper from db/koneksi.php

?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Checkout - Beauty Store</title>
<!-- server-side rendered checkout rows (no static productdata.js) -->
<style>
body { font-family:'Times New Roman', serif; margin:0; background:#fff; color:#333; }
.navbar { display:flex; justify-content:space-between; align-items:center; padding:15px 30px; background:#fff; box-shadow:0 2px 5px rgba(0,0,0,0.1);}
.navbar .logo { font-size:24px; font-weight:bold; color:#e91e63; }
.navbar ul { margin:0; padding:0; display:flex; list-style:none; gap:25px;}
.navbar ul li a { text-decoration:none; color:#333; font-weight:500; }
.navbar ul li a:hover { color:#e91e63; }
.container { max-width:1000px; margin:30px auto; padding:0 20px; }
h2 { color:#e91e63; margin-bottom:20px; }
.cart-table { width:100%; border-collapse:collapse; margin-bottom:20px; }
.cart-table th, .cart-table td { padding:12px; border-bottom:1px solid #eee; text-align:left; }
.cart-table img { width:80px; height:80px; object-fit:cover; border-radius:8px; }
.total { text-align:right; font-weight:bold; font-size:18px; margin-top:10px; }
.checkout-form { margin-top:30px; display:flex; flex-direction:column; gap:15px; max-width:500px; }
.checkout-form input, .checkout-form select { padding:8px 10px; border-radius:6px; border:1px solid #ccc; width:100%; }
.checkout-btn { display:inline-block; background:#e91e63; color:#fff; padding:10px 20px; border-radius:25px; text-decoration:none; margin-top:10px; cursor:pointer; border:none;}
.checkout-btn:hover { background:#c2185b; }
</style>
</head>
<body>
<div class="navbar">
    <div class="logo">Beauty Store</div>
    <ul>
        <li><a href="index.php">Beranda</a></li>
        <li><a href="products.php">Produk</a></li>
        <li><a href="about.php">Tentang</a></li>
        <li><a href="contact.php">Kontak</a></li>
    </ul>
</div>

<div class="container">
<h2>Checkout Produk Terpilih</h2>

<form id="checkoutForm" class="checkout-form" method="POST" action="proses_checkout.php" enctype="multipart/form-data">
    <table class="cart-table">
    <thead>
        <tr>
            <th>Produk</th>
            <th>Harga</th>
            <th>Jumlah</th>
            <th>Subtotal</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $totalPayment = 0;
        foreach ($checkoutRows as $row) {
            $subtotal = floatval($row['harga']) * intval($row['qty']);
            $totalPayment += $subtotal;
            ?>
            <tr>
                <td style="display:flex; gap:10px; align-items:center;">
                    <img src="<?= htmlspecialchars($row['img'] ?: 'image/placeholder.png') ?>" alt="<?= htmlspecialchars($row['nama_barang']) ?>">
                    <span><?= htmlspecialchars($row['nama_barang']) ?></span>
                </td>
                <td><?= rupiah($row['harga']) ?></td>
                <td><?= intval($row['qty']) ?></td>
                <td><?= rupiah($subtotal) ?></td>
            </tr>
        <?php } ?>
    </tbody>
    </table>

    <div class="total">Total Pembayaran: <span id="totalPayment"><?= rupiah($totalPayment) ?></span></div>

    <h2>Isi Data Pemesan</h2>
    <!-- include hidden id_keranjang[] for all rows so the server processes these -->
    <?php foreach ($checkoutRows as $row): ?>
        <input type="hidden" name="id_keranjang[]" value="<?= intval($row['id_keranjang']) ?>">
    <?php endforeach; ?>

    <input type="hidden" name="total" value="<?= $totalPayment ?>" />
    <input type="text" id="name" name="name" placeholder="Nama Lengkap" required>
    <textarea id="address" name="address" placeholder="Alamat Lengkap" rows="3" required style="padding:8px; border-radius:6px; border:1px solid #ccc;"></textarea>
    <select id="paymentMethod" name="paymentMethod" required>
        <option value="">Pilih Metode Pembayaran</option>
        <option value="transfer">Transfer Bank</option>
        <option value="cod">Cash on Delivery (COD)</option>
        <option value="e-wallet">E-Wallet (Gopay, OVO, Dana)</option>
    </select>
    
    <!-- Upload bukti pembayaran (opsional) -->
    <label for="bukti" style="font-weight:600; margin-top:6px;">Upload Bukti Pembayaran (opsional)</label>
    <input type="file" id="bukti" name="bukti" accept="image/*,.pdf" />
    <button type="submit" class="checkout-btn">Konfirmasi Pesanan</button>
</form>

</div>

<!-- minimal client-side validation could be added if desired -->
</body>
</html>
