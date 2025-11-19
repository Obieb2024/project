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
<html lang="id">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Login - RestoApp</title>
<link href="assets/css/style.css" rel="stylesheet" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<script src="assets/js/login.js" defer></script>
</head>
<body>

<div class="login-container">
    <header class="login-header">
        <div class="icon-round">
            <i class="fas fa-utensils"></i>
        </div>
        <h2>Selamat Datang</h2>
        <small>Silakan masuk ke akun restoran Anda</small>
    </header>

    <form method="post" novalidate>
        <?php if (isset($error)): ?>
            <div class="error-msg">
                <i class="fas fa-exclamation-circle"></i> <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <label>Email Address</label>
        <input type="email" name="email" required placeholder="contoh@email.com" autocomplete="off" />
        
        <label>Password</label>
        <div class="password-input">
            <input type="password" name="password" required placeholder="••••••••" />
            <button type="button" class="toggle-password"><i class="far fa-eye"></i></button>
        </div>
        
        <button type="submit" class="btn-login">
            Masuk Sekarang <i class="fas fa-arrow-right" style="margin-left:8px; font-size:14px;"></i>
        </button>

        <p class="form-footer">
            Belum punya akun? <a href="register.php">Daftar disini</a>
        </p>
    </form>
</div>

</body>
</html>