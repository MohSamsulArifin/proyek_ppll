<?php
include __DIR__ . '/db/koneksi.php';
if (session_status() === PHP_SESSION_NONE) session_start();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tentang Kami - Beauty Store</title>

    <style>
        body {
            font-family: 'Times New Roman', serif;
            margin: 0;
            background-color: #fff;
            color: #333;
        }

        /* Top Bar */
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
            padding: 15px 60px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            flex-wrap: wrap;
        }

        .navbar .logo {
            font-size: 26px;
            font-weight: bold;
            color: #e91e63;
        }

        .navbar ul {
            list-style: none;
            display: flex;
            margin: 0;
            padding: 0;
            flex-wrap: wrap;
        }

        .navbar ul li {
            margin: 0 15px;
        }

        .navbar ul li a {
            text-decoration: none;
            color: #333;
            font-weight: 500;
            transition: color 0.3s;
        }

        .navbar ul li a:hover {
            color: #e91e63;
        }

        .navbar .shop-btn {
            background-color: #e91e63;
            color: white;
            padding: 8px 18px;
            border-radius: 20px;
            text-decoration: none;
            font-weight: 600;
            transition: background 0.3s;
            font-size: 14px;
            white-space: nowrap;
        }

        .navbar .shop-btn:hover {
            background-color: #d81b60;
        }

        /* Hero Section */
        .hero {
            background: linear-gradient(to right, #ffd1e8, #fff);
            text-align: center;
            padding: 60px 20px;
        }

        .hero h1 {
            font-size: 36px;
            color: #e91e63;
            margin-bottom: 10px;
        }

        .hero p {
            color: #555;
            font-size: 18px;
        }

        /* About Section */
        .about-section {
            padding: 60px 20px;
            display: flex;
            justify-content: center;
        }

        .about-box {
            background-color: #fff6f9;
            border-radius: 20px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.05);
            max-width: 900px;
            padding: 40px 50px;
            text-align: center;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .about-box:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
        }

        .about-box h2 {
            color: #e91e63;
            margin-bottom: 20px;
            font-size: 26px;
        }

        .about-box p {
            line-height: 1.8;
            color: #555;
            margin-bottom: 15px;
            font-size: 16px;
        }

        /* Footer */
        footer {
            background-color: #ffe4ec;
            text-align: center;
            padding: 20px;
            color: #555;
            font-size: 14px;
            margin-top: 60px;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .navbar {
                flex-direction: column;
                text-align: center;
                padding: 10px 20px;
            }

            .navbar ul {
                flex-direction: column;
                padding: 10px 0;
            }

            .navbar ul li {
                margin: 8px 0;
            }

            .navbar .shop-btn {
                margin-top: 8px;
                font-size: 14px;
                padding: 6px 14px;
            }

            .hero h1 {
                font-size: 28px;
            }

            .about-box {
                padding: 25px 20px;
            }
        }
    </style>
</head>

<body>

    <div class="top-bar">
        💖 Beauty Store - Cantik Alami Setiap Hari 💄
    </div>

    <div class="navbar">
        <div class="logo">Beauty Store</div>

        <ul>
            <li><a href="index.php">Beranda</a></li>
            <li><a href="products.php">Produk</a></li>
            <li><a href="about.php" style="color:#e91e63;">Tentang</a></li>
            <li><a href="contact.php">Kontak</a></li>
        </ul>

        <a href="products.php" class="shop-btn">Shop Now</a>
    </div>

    <div class="hero">
        <h1>Tentang Kami</h1>
        <p>Selamat datang di Beauty Store — destinasi terbaru untuk menemukan kecantikan sejati dan kepercayaan diri yang memancar.</p>
    </div>

    <div class="about-section">
        <div class="about-box">
            <h2>💋 Cerita Kami</h2>

            <p>Didirikan pada tahun <strong>2025</strong>, <b>Beauty Store</b> hadir sebagai toko kecantikan modern yang menginspirasi setiap wanita Indonesia untuk tampil cantik dengan cara yang mudah, aman, dan menyenangkan.</p>

            <p>Kami menghadirkan koleksi produk kecantikan pilihan mulai dari <b>skincare</b>, <b>makeup</b>, hingga <b>perawatan tubuh</b> — semuanya dari brand terpercaya dan berkualitas tinggi.</p>

            <p>Dengan pelayanan yang hangat, pengiriman cepat, dan pengalaman berbelanja yang menyenangkan, <b>Beauty Store</b> siap menjadi sahabat barumu dalam setiap langkah menuju versi terbaik dirimu.</p>

            <p>💖 Karena bagi kami, <b>setiap wanita berhak merasa cantik — luar dan dalam.</b></p>
        </div>
    </div>

    <footer>
        Beauty Store 💖
    </footer>

</body>
</html>
