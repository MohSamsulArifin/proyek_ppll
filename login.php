<?php
session_start();
include __DIR__ . '/db/koneksi.php';

$error = '';
$redirect = isset($_GET['redirect']) ? $_GET['redirect'] : 'products.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username_or_email = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username_or_email === '' || $password === '') {
        $error = 'Silakan isi username/email dan password.';
    } else {
        // find user by username or email
        $stmt = $conn->prepare("SELECT * FROM tb_user WHERE username = ? OR email = ? LIMIT 1");
        $stmt->bind_param('ss', $username_or_email, $username_or_email);
        $stmt->execute();
        $res = $stmt->get_result();
        $user = $res->fetch_assoc();

        if (!$user) {
            $error = 'Username/email tidak ditemukan.';
        } else {
            $stored = $user['password'];
            $ok = false;
            // support both hashed and plain passwords (legacy)
            if (password_verify($password, $stored)) $ok = true;
            if (!$ok && $password === $stored) $ok = true;

            if ($ok) {
                // login success
                $_SESSION['id_user'] = intval($user['id_user']);
                $_SESSION['username'] = $user['username'];
                $_SESSION['nama'] = $user['nama'];

                header('Location: ' . $redirect);
                exit;
            } else {
                $error = 'Password salah.';
            }
        }
    }
}

?>
<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Login - Beauty Store</title>
  <link rel="stylesheet" href="styles.css">
  <style>
    .center { max-width:420px;margin:48px auto;padding:24px;background:#fff6f9;border-radius:10px;border:1px solid #ffd6e7 }
    label{display:block;margin:8px 0 4px}
    input[type=text],input[type=password]{width:100%;padding:10px;border:1px solid #ddd;border-radius:6px}
    button{background:#e91e63;color:#fff;padding:10px 14px;border:none;border-radius:8px;cursor:pointer}
    .muted{color:#666;font-size:14px}
    .error{color:#b00020;margin-bottom:12px}
  </style>
</head>
<body>
  <div style="padding:12px;text-align:center;background:#ffebf2;color:#e91e63;font-weight:bold">🌸 Beauty Store - Login</div>

  <div class="center">
    <?php if ($error): ?>
      <div class="error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="post" action="login.php?redirect=<?= urlencode($redirect) ?>">
      <label>Username atau Email</label>
      <input type="text" name="username" required value="<?= isset($_POST['username'])?htmlspecialchars($_POST['username']):'' ?>" />

      <label>Password</label>
      <input type="password" name="password" required />

      <div style="margin-top:12px;display:flex;justify-content:space-between;align-items:center">
        <div class="muted">Belum punya akun? <a href="register.php">Daftar</a></div>
        <button type="submit">Masuk</button>
      </div>
    </form>
  </div>

</body>
</html>
