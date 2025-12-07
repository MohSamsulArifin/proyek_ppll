<?php
// Shared header + navbar
if (session_status() === PHP_SESSION_NONE) session_start();

$cartCount = 0;
if (isset($_SESSION['id_user']) && isset($conn)) {
    $stmt = $conn->prepare("SELECT COALESCE(SUM(qty),0) as total FROM keranjang WHERE id_user = ?");
    $stmt->bind_param('i', $_SESSION['id_user']);
    $stmt->execute();
    $r = $stmt->get_result()->fetch_assoc();
    $cartCount = intval($r['total'] ?? 0);
}
?>

<!-- ====== CSS NAVBAR ====== -->
<style>
    body { margin:0; padding:0; font-family:Arial, sans-serif; }

    .top-bar {
        background:#ffb6c1;
        text-align:center;
        padding:8px 0;
        font-weight:bold;
        color:#b3004b;
        letter-spacing:1px;
    }

    .navbar {
        display:flex;
        justify-content:space-between;
        align-items:center;
        padding:12px 40px;
        background:white;
        border-bottom:1px solid #ffc0cb;
    }

    .logo {
        font-size:22px;
        font-weight:bold;
        color:#e91e63;
    }

    .navbar ul {
        list-style:none;
        display:flex;
        gap:30px;
    }

    .navbar ul li a {
        text-decoration:none;
        color:#333;
        font-weight:500;
        transition:0.3s;
    }

    .navbar ul li a:hover {
        color:#e91e63;
    }

    .shop-btn {
        background:#e91e63;
        padding:8px 16px;
        border-radius:25px;
        color:white !important;
        text-decoration:none;
        font-weight:bold;
        transition:0.3s;
        display:flex;
        align-items:center;
        gap:6px;
    }

    .shop-btn:hover {
        background:#d31857;
    }

    /* agar tombol masuk / keranjang tidak terlalu jauh */
    .navbar-right {
        display:flex;
        align-items:center;
        gap:12px;  /* sebelumnya terlalu besar, dikurangi */
    }

    /* warna teks halo */
    .halo-text {
        font-weight:600;
        color:#e91e63;
    }
</style>

<!-- ====== NAVBAR ====== -->
<div class="top-bar">🌸 Beauty Store 🌸</div>

<div class="navbar">
    <div class="logo">Beauty Store</div>

    <ul>
        <li><a href="index.">Beranda</a></li>
        <li><a href="products.php">Produk</a></li>
        <li><a href="about.html">Tentang</a></li>
        <li><a href="contact.html">Kontak</a></li>
    </ul>

    <div class="navbar-right">
        <?php if (isset($_SESSION['id_user'])): ?>
            <div class="halo-text">
                Halo, <?= htmlspecialchars($_SESSION['nama'] ?? $_SESSION['username']) ?>
            </div>

            <!-- Tombol Keranjang — tulisan putih -->
            <a class="shop-btn" href="cart.php">
                Keranjang (<span id="navCartCount"><?= $cartCount ?></span>) 🛒
            </a>

            <a href="logout.php" style="color:#e91e63; font-weight:600;">Keluar</a>

        <?php else: ?>
            <a class="shop-btn" href="login.php?redirect=<?= urlencode($_SERVER['REQUEST_URI']) ?>">
                Masuk
            </a>
        <?php endif; ?>
    </div>
</div>

<script>
async function refreshNavCartCount(){
    try{
        const r = await fetch('get_cart_count.php');
        const j = await r.json();
        if (j?.cartCount !== undefined){
            const el = document.getElementById('navCartCount');
            if (el) el.textContent = j.cartCount;
        }
    }catch(e){}
}
refreshNavCartCount();
</script>
