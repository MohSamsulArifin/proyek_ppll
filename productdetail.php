<?php
session_start();
include __DIR__ . '/db/koneksi.php';

// Cek login
$isLoggedIn = isset($_SESSION['id_user']);
$userId = $isLoggedIn ? intval($_SESSION['id_user']) : 0;

// Ambil jumlah item di keranjang untuk navbar
$cartCount = 0;
if($isLoggedIn){
    $stmt = $conn->prepare("SELECT SUM(qty) as total FROM keranjang WHERE id_user = ?");
    $stmt->bind_param('i', $userId);
    $stmt->execute();
    $res = $stmt->get_result()->fetch_assoc();
    $cartCount = $res['total'] ?? 0;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Detail Produk - Beauty Store</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<?php
// fetch product by id from DB
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$product = null;
if ($id > 0) {
    $stmt = $conn->prepare("SELECT id_barang, nama_barang, harga, kategori, img, keterangan, stok FROM tb_barang WHERE id_barang = ? LIMIT 1");
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $product = $stmt->get_result()->fetch_assoc();
}
?>
<style>
/* === GLOBAL === */
body { font-family: 'Times New Roman', serif; margin:0; background:#fff; color:#333; }
.top-bar { background:#ffebf2; color:#e91e63; text-align:center; padding:8px 0; font-weight:bold; }
.navbar { display:flex; justify-content:space-between; align-items:center; padding:15px 30px; background:#fff; box-shadow:0 2px 5px rgba(0,0,0,0.1);}
.navbar .logo { font-size:24px; font-weight:bold; color:#e91e63; }
.navbar ul { margin:0; padding:0; display:flex; list-style:none; gap:25px;}
.navbar ul li a { text-decoration:none; color:#333; font-weight:500; }
.navbar ul li a:hover { color:#e91e63; }
.shop-btn { background:#e91e63; color:#fff; padding:8px 18px; border-radius:20px; text-decoration:none; position:relative;}
.shop-btn:hover { background:#c2185b; }
#navCartCount { background:#fff; color:#e91e63; padding:2px 6px; border-radius:50%; font-size:12px; position:absolute; top:-5px; right:-5px; }

/* === CONTAINER DETAIL === */
.container { padding:40px 20px; max-width:1100px; margin:auto; }
.back { text-decoration:none; color:#e91e63; font-size:16px; display:inline-block; margin-bottom:20px; font-weight:bold;}
.back:hover { text-decoration:underline;}
.product-box { display:flex; gap:35px; flex-wrap:wrap; background:#fff6f9; padding:25px; border-radius:15px; border:1px solid #ffd6e7;}
.product-box img { width:360px; height:380px; object-fit:contain; background:#fff; padding:12px; border-radius:15px; border:1px solid #ffe1ec; }
h2 { color:#e91e63; margin-bottom:8px; font-size:26px;}
.price { color:#e91e63; font-size:22px; font-weight:bold; margin-bottom:10px; }
.category { display:inline-block; background:#ffe0ec; padding:8px 15px; border-radius:20px; font-size:14px; margin-bottom:15px;}
h3 { margin-top:25px; color:#d81b60; font-size:20px;}
p { color:#444; line-height:1.6;}
.action-buttons { margin-top:25px; display:flex; gap:15px; }
.btn-cart, .btn-buy { padding:12px 22px; font-size:16px; border:none; border-radius:25px; cursor:pointer; font-weight:bold; }
.btn-cart { background:#ff8fb1; color:#fff;}
.btn-cart:hover { background:#e5789c;}
.btn-buy { background:#e91e63; color:white; }
.btn-buy:hover { background:#c2185b;}
</style>
</head>
<body>

<div class="top-bar">🌸 Beauty Store 🌸</div>

<div class="navbar">
    <div class="logo">Beauty Store</div>
    <ul>
        <li><a href="index.php">Beranda</a></li>
        <li><a href="products.php">Produk</a></li>
        <li><a href="about.php">Tentang</a></li>
        <li><a href="contact.php">Kontak</a></li>
    </ul>
    <a class="shop-btn" href="cart.php">Keranjang 🛒 <span id="navCartCount"><?= $cartCount ?></span></a>
</div>

<div class="container">
    <a href="products.php" class="back"> ← Kembali ke Produk</a>
    <?php if (!$product): ?>
        <p>Produk tidak ditemukan.</p>
    <?php else:
        // resolve image path
        $img = $product['img'] ?? '';
        if (empty($img)) {
            $imgPath = 'image/placeholder.png';
        } else if (strpos($img, 'assets/') === 0 || strpos($img, 'image/') === 0) {
            $imgPath = $img;
        } else if (file_exists(__DIR__ . '/assets/img/' . $img)) {
            $imgPath = 'assets/img/' . $img;
        } else if (file_exists(__DIR__ . '/image/' . $img)) {
            $imgPath = 'image/' . $img;
        } else {
            $imgPath = $img;
        }
    ?>
    <div class="product-box">
        <img src="<?= $imgPath ?>" alt="<?= htmlspecialchars($product['nama_barang']) ?>">
        <div>
            <h2><?= htmlspecialchars($product['nama_barang']) ?></h2>
            <div class="price"><?= rupiah($product['harga']) ?></div>
            <div class="category"><?= htmlspecialchars($product['kategori']) ?></div>
            <h3>Deskripsi Produk</h3>
            <p><?= nl2br(htmlspecialchars($product['keterangan'])) ?></p>
            <div class="action-buttons">
                <button class="btn-cart" onclick="addToCart(<?= $product['id_barang'] ?>)">Masukkan Keranjang 🛒</button>
                <button class="btn-buy" onclick="buyNow(<?= $product['id_barang'] ?>)">Beli Sekarang ⚡</button>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>

<script>
// Login info
const isLoggedIn = <?php echo $isLoggedIn ? 'true':'false'; ?>;
function addToCart(id){
    if(!isLoggedIn){
        alert("Silakan login terlebih dahulu.");
        window.location.href = "login.php?redirect=productdetail.php?id="+id;
        return;
    }

    fetch("add_to_cart.php",{
        method:"POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: "id_barang="+id
    }).then(r=>r.json()).then(res=>{
        if(res.success){
            alert(res.message);
            document.getElementById("navCartCount").textContent = res.cartCount;
        } else {
            alert(res.message);
        }
    }).catch(e=>alert("Terjadi kesalahan"));
}

function buyNow(id){
    if(!isLoggedIn){
        alert("Silakan login terlebih dahulu.");
        window.location.href = "login.php?redirect=productdetail.php?id="+id;
        return;
    }
    window.location.href = "checkout.php?id="+id;
}
</script>

</body>
</html>
