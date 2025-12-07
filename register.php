<?php
session_start();
include __DIR__ . '/db/koneksi.php';

$errors = [];
$redirect = isset($_GET['redirect']) ? $_GET['redirect'] : 'products.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $nama = trim($_POST['nama'] ?? '');
    $alamat = trim($_POST['alamat'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $password2 = $_POST['password2'] ?? '';

    if ($username === '' || $nama === '' || $email === '' || $password === '') {
        $errors[] = 'Semua field harus diisi.';
    }
    if ($password !== $password2) {
        $errors[] = 'Password dan konfirmasi tidak sama.';
    }

    // uniqueness check
    $stmt = $conn->prepare("SELECT * FROM tb_user WHERE username = ? OR email = ? LIMIT 1");
    $stmt->bind_param('ss', $username, $email);
    $stmt->execute();
    $exists = $stmt->get_result()->fetch_assoc();
    if ($exists) $errors[] = 'Username atau email sudah terdaftar.';

    if (empty($errors)) {
        $data = [
            'username' => $username,
            'nama' => $nama,
            'alamat' => $alamat,
            'email' => $email,
            'password' => $password
        ];

        $insert_id = add_user($data);
        if ($insert_id) {
            // login automatically
            $_SESSION['id_user'] = intval($insert_id);
            $_SESSION['username'] = $username;
            $_SESSION['nama'] = $nama;

            header('Location: ' . $redirect);
            exit;
        } else {
            $errors[] = 'Gagal membuat akun, coba lagi nanti.';
        }
    }
}

?>
<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Daftar - Beauty Store</title>
  <link rel="stylesheet" href="styles.css">
  <style>
    .center { max-width:420px;margin:48px auto;padding:24px;background:#fff6f9;border-radius:10px;border:1px solid #ffd6e7 }
    label{display:block;margin:8px 0 4px}
    input[type=text],input[type=password],input[type=email]{width:100%;padding:10px;border:1px solid #ddd;border-radius:6px}
    button{background:#e91e63;color:#fff;padding:10px 14px;border:none;border-radius:8px;cursor:pointer}
    .error{color:#b00020;margin-bottom:12px}
  </style>
</head>
<body>
  <div style="padding:12px;text-align:center;background:#ffebf2;color:#e91e63;font-weight:bold">🌸 Beauty Store - Daftar</div>

  <div class="center">
    <?php if (!empty($errors)): ?>
      <div class="error"><?= htmlspecialchars(implode("<br>", $errors)) ?></div>
    <?php endif; ?>

    <form method="post" action="register.php?redirect=<?= urlencode($redirect) ?>">
      <label>Username</label>
      <input type="text" name="username" required value="<?= isset($_POST['username'])?htmlspecialchars($_POST['username']):'' ?>" />

      <label>Nama lengkap</label>
      <input type="text" name="nama" required value="<?= isset($_POST['nama'])?htmlspecialchars($_POST['nama']):'' ?>" />

      <label>Alamat</label>
      <input type="text" name="alamat" value="<?= isset($_POST['alamat'])?htmlspecialchars($_POST['alamat']):'' ?>" />

      <label>Email</label>
      <input type="email" name="email" required value="<?= isset($_POST['email'])?htmlspecialchars($_POST['email']):'' ?>" />

      <label>Password</label>
      <input type="password" name="password" required />

      <label>Konfirmasi Password</label>
      <input type="password" name="password2" required />

      <div style="margin-top:12px;display:flex;justify-content:space-between;align-items:center">
        <div class="muted">Sudah punya akun? <a href="login.php">Masuk</a></div>
        <button type="submit">Daftar</button>
      </div>
    </form>
  </div>

</body>
</html>
