<?php
session_start();
include __DIR__ . '/db/koneksi.php';

// Cek apakah user sudah login
$user_logged_in = isset($_SESSION['nama']);

// Jika user submit form dan sudah login
$pesan_terkirim = false;
if ($_SERVER["REQUEST_METHOD"] === "POST" && $user_logged_in) {
    $nama = htmlspecialchars($_POST['nama']);
    $email = htmlspecialchars($_POST['email']);
    $pesan = htmlspecialchars($_POST['pesan']);

    // Simpan pesan ke database jika mau (opsional)
    // $stmt = $conn->prepare("INSERT INTO messages (id_user, nama, email, pesan) VALUES (?,?,?,?)");
    // $stmt->bind_param("isss", $_SESSION['id_user'], $nama, $email, $pesan);
    // $stmt->execute();

    $pesan_terkirim = true;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Kontak - Beauty Store</title>
<style>
body {
    font-family: 'Times New Roman', serif;
    margin: 0;
    background-color: #fff;
    color: #333;
}
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
    flex-wrap: wrap;
}
.navbar .logo {
    font-size: 26px;
    font-weight: bold;
    color: #e91e63;
}
.navbar-center ul {
    list-style: none;
    display: flex;
    margin: 0;
    padding: 0;
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
.shop-btn:hover { background-color: #d81b60; }

/* Dropdown for user */
.dropdown { position: relative; }
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
.dropdown-btn:hover { background-color: #d81b60; }
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
.dropdown-content a:hover { background: #f2f2f2; }
.dropdown:hover .dropdown-content { display: block; }

/* Contact Section */
.contact {
    padding: 60px 20px;
    background-color: #fff6f9;
    text-align: center;
}
.contact h1 {
    color: #e91e63;
    margin-bottom: 10px;
    font-size: 32px;
}
.contact p {
    margin-bottom: 40px;
    color: #555;
    font-size: 17px;
}
form {
    background-color: white;
    max-width: 500px;
    margin: 0 auto;
    padding: 30px;
    border-radius: 15px;
    box-shadow: 0 3px 15px rgba(0,0,0,0.08);
    text-align: left;
}
form input, form textarea {
    width: 100%;
    padding: 12px;
    margin: 10px 0 20px 0;
    border: 1px solid #ddd;
    border-radius: 10px;
    font-family: 'Times New Roman', serif;
    font-size: 15px;
    resize: none;
}
form button {
    background-color: #e91e63;
    color: white;
    border: none;
    padding: 12px 20px;
    border-radius: 25px;
    cursor: pointer;
    font-weight: bold;
    transition: background 0.3s;
    width: 100%;
}
form button:hover { background-color: #c2185b; }
.btn-login-warn { background-color: #888; cursor: not-allowed; }
.login-box {
    background: #ffe4ec;
    padding: 15px;
    border-radius: 12px;
    margin-top: 20px;
    font-size: 16px;
}
.login-btn {
    display: inline-block;
    background: #e91e63;
    color: #fff;
    padding: 10px 18px;
    border-radius: 20px;
    text-decoration: none;
    font-weight: bold;
    margin-top: 10px;
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
@media (max-width:768px){.navbar-center ul{flex-direction:column;}.navbar-center ul li{margin:8px 0;}}
</style>
</head>
<body>

<div class="top-bar">💌 Butuh Bantuan? Hubungi Kami Sekarang!</div>

<div class="navbar">
    <div class="logo">Beauty Store</div>

    <div class="navbar-center">
        <ul>
            <li><a href="index.php">Beranda</a></li>
            <li><a href="products.php">Produk</a></li>
            <li><a href="about.php">Tentang</a></li>
            <li><a href="contact.php" style="color:#e91e63;">Kontak</a></li>
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

<div class="contact">
    <h1>Hubungi Kami</h1>
    <p>Isi formulir di bawah ini ya cantik dan kami akan segera menghubungimu 💕</p>

    <form action="contact.php" method="post">
        <input type="text" name="nama" placeholder="Nama Lengkap" value="<?= $user_logged_in ? htmlspecialchars($_SESSION['nama']) : '' ?>" required>
        <input type="email" name="email" placeholder="Alamat Email" value="<?= $user_logged_in ? htmlspecialchars($_SESSION['email'] ?? '') : '' ?>" required>
        <textarea name="pesan" rows="5" placeholder="Tulis pesanmu di sini..." required></textarea>

        <?php if($user_logged_in): ?>
            <button type="submit">Kirim Pesan</button>
        <?php else: ?>
            <div class="login-box">
                Kamu harus login sebelum mengirim pesan 💕<br>
                <a href="login.php" class="login-btn">Login Sekarang</a>
            </div>
            <button type="button" class="btn-login-warn">Kirim Pesan (Login dulu)</button>
        <?php endif; ?>
    </form>

    <?php if($pesan_terkirim): ?>
        <p style="color:green; margin-top:20px; font-weight:bold;">Pesan berhasil terkirim! 💖</p>
    <?php endif; ?>
</div>

<footer>Beauty Store 💖</footer>
</body>
</html>
