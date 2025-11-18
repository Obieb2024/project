<?php
session_start();
include 'includes/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $role = $_POST['role'] ?? 'customer';
    $fullname = $_POST['fullname'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $conf_password = $_POST['conf_password'];

    if ($password !== $conf_password) {
        $error = "Password dan konfirmasi tidak sama.";
    } else {
        // Cek email sudah terdaftar?
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->rowCount() > 0) {
            $error = "Email sudah terdaftar.";
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO users (fullname, email, password, role) VALUES (?, ?, ?, ?)");
            if ($stmt->execute([$fullname, $email, $hash, $role])) {
                $_SESSION['success'] = "Registrasi berhasil, silakan login.";
                header("Location: index.php");
                exit;
            } else {
                $error = "Gagal registrasi.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Daftar Akun Baru</title>
<link href="assets/css/style.css" rel="stylesheet" />
<script src="assets/js/register.js" defer></script>
</head>
<body>
<div class="register-container">
    <header class="register-header">
        <div class="icon-round"><img src="assets/img/chef-icon.svg" alt="chef icon"></div>
        <h2>Buat Akun Baru</h2>
        <small>Pilih jenis akun Anda</small>
    </header>

    <form method="POST" novalidate>
        <div class="toggle-role">
            <label><input type="radio" name="role" value="customer" checked /> Customer</label>
            <label><input type="radio" name="role" value="admin" /> Admin</label>
        </div>
        <label>Nama Lengkap</label>
        <input type="text" name="fullname" placeholder="John Doe" required />
        <label>Email</label>
        <input type="email" name="email" placeholder="email@example.com" required />
        <label>Password</label>
        <div class="password-input">
            <input type="password" name="password" required />
            <button type="button" class="toggle-password">👁️</button>
        </div>
        <label>Konfirmasi Password</label>
        <input type="password" name="conf_password" required />

        <button type="submit" class="btn-register">
          Daftar sebagai <span class="role-label">Customer</span>
        </button>
    </form>

    <p>Sudah punya akun? <a href="index.php">Masuk sekarang</a></p>

    <?php if (isset($error)): ?>
    <div class="error-msg"><?=htmlspecialchars($error)?></div>
    <?php endif; ?>
    
    <div class="toggle-role">
   <input type="radio" id="roleCustomer" name="role" value="customer" checked />
   <label for="roleCustomer"><i class="icon-user"></i> Customer</label>
   <input type="radio" id="roleAdmin" name="role" value="admin" />
   <label for="roleAdmin"><i class="icon-chef"></i> Admin</label>
</div>
</div>
</body>
</html>