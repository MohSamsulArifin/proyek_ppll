<?php 
session_start();
include "../db/koneksi.php";

if (isset($_POST["submit"])) {
    if (!empty($_POST["username"]) && !empty($_POST["password"])) {
        $username = $_POST["username"];
        $password = $_POST["password"];

    $query = "SELECT * FROM tb_admin WHERE username = '$username'";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);
        $_SESSION["id_admin"] = $user["id_admin"];
        $_SESSION["username"] = $user["username"];
        if ($user['password']) {
            echo "<script>
            alert('Login sukses'); 
            window.location.href = 'index.php';
            </script>";
        } else {
            echo "<script>
            alert('Password Salah, silahkan coba lagi.');
            history.back();
            </script>";
        }
    } else {
        echo "<script>
        alert('Username tidak Ditemukan, silahkan daftar terlebih dahulu jika belum memiliki akun');
        history.back();
        </script>";
    }
} else {
      // Fields are empty
    echo "<script>
    alert('Please enter both username and password.');
    history.back();
    </script>";
}
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin Beauty Store</title>
    <link rel="stylesheet" href="../css/login.css">
</head>

<body>
    <div class="container">
        <div class="login">
            <form method="post">
                <h1>Admin Login</h1>
                <hr>
                <p>Masuk Ke Dashboard Admin</p>
                <label for="">Username</label>
                <input type="text" name="username" placeholder="Username">
                <label for="">Password</label>
                <input type="password" name="password" placeholder="Password">
                <button name="submit">Login</button>
            </form>
        </div>
        <div class="right">
            <img src="../assets/icon/makeup.png" alt="">
        </div>
    </div>
</body>

</html>