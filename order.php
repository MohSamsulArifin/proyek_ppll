<?php
session_start();
include __DIR__ . '/db/koneksi.php';

// Pastikan user login
if (!isset($_SESSION['id_user'])) {
    header('Location: login.php');
    exit;
}

$userId = intval($_SESSION['id_user']);

/*
 AMBIL DATA PESANAN + TOTAL + STATUS
*/
$query = "
        SELECT o.id_orders, o.tanggal, o.status,
            SUM(d.harga_satuan * d.qty) AS total_harga,
            (SELECT tb.nama_barang FROM details dd JOIN tb_barang tb ON dd.id_barang = tb.id_barang WHERE dd.id_orders=o.id_orders LIMIT 1) AS produk_pertama,
            (SELECT tb.img FROM details dd JOIN tb_barang tb ON dd.id_barang = tb.id_barang WHERE dd.id_orders=o.id_orders LIMIT 1) AS produk_img
        FROM orders o
        JOIN details d ON o.id_orders = d.id_orders
    WHERE o.id_user = ?
    GROUP BY o.id_orders
    ORDER BY o.id_orders DESC
";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Pesanan Saya - Beauty Store</title>
<style>
body{font-family:'Times New Roman',serif;margin:0;background:#fff;}
.container{max-width:1100px;margin:auto;padding:25px;}
.order-box{border:1px solid #ffd6e7;background:#fff6f9;margin-bottom:18px;padding:18px;border-radius:15px;}
.order-box h3{color:#e91e63;margin:0;}
.status{background:#ffb0cf;padding:5px 12px;border-radius:20px;font-size:13px;color:#fff;}
.view-btn{background:#e91e63;color:#fff;padding:6px 14px;text-decoration:none;border-radius:15px;}
</style>
</head>
<body>

<div class="container">
<h2 style="color:#e91e63;">Pesanan Saya</h2>

<?php if(isset($_GET['confirmed'])): ?>
    <?php if($_GET['confirmed'] == '1'): ?>
        <div style="padding:10px;border-radius:8px;background:#e6ffed;border:1px solid #b7f0c6;color:#0f5132;margin-bottom:12px;">Terima kasih — pesanan telah dikonfirmasi sebagai diterima.</div>
    <?php else: ?>
        <div style="padding:10px;border-radius:8px;background:#fff3cd;border:1px solid #ffeeba;color:#664d03;margin-bottom:12px;">Gagal mengkonfirmasi pesanan. Silakan coba lagi.</div>
    <?php endif; ?>
<?php endif; ?>

<?php if($result->num_rows === 0): ?>
    <p>Belum ada pesanan. <a href="products.php">Belanja sekarang</a></p>
<?php else: ?>
    <?php while($o = $result->fetch_assoc()): ?>

        <div class="order-box">
            <h3>Pesanan #<?= $o['id_orders'] ?></h3>
            <p>Status: <span class="status"><?= $o['status'] ?></span></p>
            <p>Produk: <?= $o['produk_pertama'] ?> + (item lainnya)</p>
            <p>Total: <b>Rp <?= number_format($o['total_harga'],0,",",".") ?></b></p>

            <div style="display:flex;gap:10px;align-items:center;margin-top:10px;">
                <a class="view-btn" href="order-detail.php?id=<?= $o['id_orders'] ?>">Lihat Detail</a>
                <?php if($o['status'] === 'Dikirim'): ?>
                    <form method="post" action="confirm_received.php" onsubmit="return confirm('Konfirmasi bahwa pesanan telah diterima?');">
                        <input type="hidden" name="id_orders" value="<?= $o['id_orders'] ?>">
                        <input type="hidden" name="return_to" value="order.php">
                        <button type="submit" class="view-btn" style="background:#28a745; border:none;">Konfirmasi Diterima</button>
                    </form>
                <?php endif; ?>
            </div>
        </div>

    <?php endwhile; ?>
<?php endif; ?>

</div>
</body>
</html>
