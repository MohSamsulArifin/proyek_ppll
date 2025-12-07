

<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

include __DIR__ . '/db/koneksi.php';
if (session_status() === PHP_SESSION_NONE) session_start();

// Cek apakah user sudah login
$user_logged_in = isset($_SESSION['nama']);
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Beauty Store - Beauty Wonderland</title>
<style>
    body {
        font-family: 'Times New Roman', serif;
        margin: 0;
        background-color: #fff;
        color: #333;
    }

    /* Header promo bar */
    .top-bar {
        background-color: #ffebf2;
        color: #e91e63;
        text-align: center;
        padding: 8px;
        font-size: 14px;
        font-weight: 500;
    }

    /* Navbar */
    .navbar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        background-color: white;
        padding: 15px 30px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        position: relative;
        flex-wrap: wrap;
    }

    .logo {
        font-size: 22px;
        font-weight: bold;
        color: #e91e63;
    }

    .navbar-center {
        position: absolute;
        left: 50%;
        transform: translateX(-50%);
    }

    .navbar-center ul {
        list-style: none;
        display: flex;
        margin: 0;
        padding: 0;
        justify-content: center;
    }

    .navbar-center ul li {
        margin: 0 15px;
    }

    .navbar-center ul li a {
        text-decoration: none;
        color: #333;
        font-weight: 500;
        transition: color 0.3s;
    }

    .navbar-center ul li a:hover {
        color: #e91e63;
    }

    /* Navbar right buttons */
    .navbar-right {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .shop-btn {
        background-color: #e91e63;
        color: white;
        padding: 8px 18px;
        border-radius: 20px;
        text-decoration: none;
        font-weight: 600;
        transition: background 0.3s;
    }

    .shop-btn:hover {
        background-color: #d81b60;
    }

    /* Dropdown for user */
    .dropdown {
        position: relative;
    }

    .dropdown-btn {
        background-color: #e91e63;
        color: white;
        padding: 8px 18px;
        border-radius: 20px;
        font-weight: 600;
        border: none;
        cursor: pointer;
        transition: background 0.3s;
    }

    .dropdown-btn:hover {
        background-color: #d81b60;
    }

    .dropdown-content {
        display: none;
        position: absolute;
        top: 100%;
        right: 0;
        background: white;
        min-width: 120px;
        box-shadow: 0 2px 6px rgba(0,0,0,0.2);
        border-radius: 8px;
        z-index: 1000;
    }

    .dropdown-content a {
        display: block;
        padding: 10px 12px;
        text-decoration: none;
        color: #333;
    }

    .dropdown-content a:hover {
        background: #f2f2f2;
    }

    .dropdown:hover .dropdown-content {
        display: block;
    }

    /* Hero Section */
    .hero {
        background-image: url('image/gambar 1.webp');
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        text-align: center;
        padding: 100px 20px;
        position: relative;
        color: white;
    }

    .hero::before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.4);
        z-index: 1;
    }

    .hero h1, .hero p, .hero .cta {
        position: relative;
        z-index: 2;
    }

    .hero h1 {
        font-size: 36px;
        margin-bottom: 10px;
        text-shadow: 2px 2px 6px rgba(0,0,0,0.3);
    }

    .hero p {
        font-size: 16px;
        color: #f9f9f9;
        text-shadow: 1px 1px 3px rgba(0,0,0,0.2);
        margin: 0 10px;
    }

    .hero .cta a {
        background-color: #e91e63;
        color: white;
        padding: 10px 25px;
        border-radius: 25px;
        text-decoration: none;
        font-weight: bold;
        transition: background 0.3s;
    }

    .hero .cta a:hover {
        background-color: #c2185b;
    }

    /* Product Section */
    .products {
        padding: 40px 25px;
        background-color: #fff;
        text-align: center;
    }

    .products h2 {
        color: #e91e63;
        margin-bottom: 25px;
    }

    .product-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
    }

    .product {
        border: 1px solid #f2f2f2;
        border-radius: 15px;
        padding: 15px;
        box-shadow: 0 3px 6px rgba(0,0,0,0.05);
        transition: transform 0.3s;
    }

    .product:hover {
        transform: scale(1.03);
    }

    .product img {
        width: 100%;
        border-radius: 15px;
    }

    .product h3 {
        color: #333;
        margin-top: 10px;
        font-size: 16px;
    }

    .product p {
        color: #e91e63;
        font-weight: 600;
        font-size: 15px;
    }

    /* Footer */
    footer {
        background-color: #ffe4ec;
        text-align: center;
        padding: 15px;
        color: #555;
        font-size: 14px;
    }

    /* RESPONSIVE */
    @media (max-width: 768px) {
        .navbar {
            flex-direction: column;
            align-items: center;
            text-align: center;
            padding: 10px 15px;
        }

        .navbar-center {
            position: static;
            transform: none;
            margin: 10px 0;
        }

        .navbar-center ul {
            flex-direction: column;
        }

        .navbar-center ul li {
            margin: 8px 0;
        }

        .product-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 480px) {
        .hero {
            padding: 80px 15px;
        }

        .hero h1 {
            font-size: 24px;
        }

        .hero p {
            font-size: 13px;
        }
    }

</style>
</head>
<body>

<div class="top-bar">
    ✨ Welcome To Beauty Store ✨ 
</div>

<div class="navbar">
    <div class="logo">Beauty Store</div>

    <div class="navbar-center">
        <ul>
            <li><a href="index.php">Beranda</a></li>
            <li><a href="products.php">Produk</a></li>
            <li><a href="about.php">Tentang</a></li>
            <li><a href="contact.php">Kontak</a></li>
            <li><a href="order.php">Pesanan saya</a></li>
        </ul>
    </div>

    <div class="navbar-right">
        <?php if($user_logged_in): ?>
            <div class="dropdown">
                <button class="dropdown-btn"><?= htmlspecialchars($_SESSION['nama']); ?> ▼</button>
                <div class="dropdown-content">
                    <a href="logout.php">Logout</a>
                </div>
            </div>
        <?php else: ?>
            <a href="login.php" class="shop-btn">Login</a>
            <a href="register.php" class="shop-btn">Daftar</a>
        <?php endif; ?>
    </div>
</div>

<div class="hero">
    <h1>Beauty Wonderland</h1>
    <p>Temukan kecantikanmu bersama Beauty Store dengan promo hingga 50%!</p>
    <div class="cta">
        <a href="products.php">Belanja Sekarang</a>
    </div>
</div>

<div class="products">
    <h2>Produk Terlaris</h2>
    <div class="product-grid">
        <div class="product">
            <img src="image/Serum Glow Radiance.jpeg" alt="Serum">
            <h3>Serum Glow Radiance</h3>
            <p>Rp 120.000</p>
        </div>

        <div class="product">
            <img src="image/Soft Lip Tint.jpeg" alt="Lip Tint">
            <h3>Soft Lip Tint</h3>
            <p>Rp 85.000</p>
        </div>

        <div class="product">
            <img src="image/Hydrating Face Cream.webp" alt="Cream">
            <h3>Hydrating Face Cream</h3>
            <p>Rp 150.000</p>
        </div>
    </div>
</div>

<footer>
    Beauty Store 💖
</footer>

</body>
</html>
