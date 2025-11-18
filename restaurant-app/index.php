<?php
session_start();
include 'includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $pass = $_POST['password'];
    
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($pass, $user['password'])) {
        $_SESSION['user'] = [
            'id' => $user['id'],
            'fullname' => $user['fullname'],
            'role' => $user['role'],
            'email' => $user['email']
        ];
        if ($user['role'] === 'admin') {
            header('Location: admin/dashboard.php');
        } else {
            header('Location: customer/menu.php');
        }
        exit;
    } else {
        $error = "Email atau password salah.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Login</title>
<link href="assets/css/style.css" rel="stylesheet" />
<script src="assets/js/login.js" defer></script>
</head>
<body>
<div class="login-container">
    <header class="login-header">
    <h2>Selamat Datang</h2>
    <small>Masuk ke akun Anda</small>
    </header>

    <form method="post" novalidate>
        <label>Email</label>
        <input type="email" name="email" required placeholder="email@example.com" />
        <label>Password</label>
        <div class="password-input">
            <input type="password" name="password" required />
            <button type="button" class="toggle-password">👁️</button>
        </div>
        <button type="submit" class="btn-login">Masuk</button>
    </form>

    <p>Belum punya akun? <a href="register.php">Daftar sekarang</a></p>

    <?php if (isset($error)): ?>
        <div class="error-msg"><?=htmlspecialchars($error)?></div>
    <?php endif; ?>

    <div class="demo-credentials">
        <div><strong>Admin:</strong><br>admin@restaurant.com / admin123</div>
        <div><strong>Customer:</strong><br>customer@email.com / customer123</div>
    </div>
</div>
</body>
</html>