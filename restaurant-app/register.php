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
<html lang="id">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Daftar Akun - RestoApp</title>
<link href="assets/css/style.css" rel="stylesheet" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<script src="assets/js/register.js" defer></script>
</head>
<body>

<div class="register-container">
    <header class="register-header">
        <div class="icon-round">
            <i class="fas fa-user-plus"></i>
        </div>
        <h2>Buat Akun Baru</h2>
        <small>Bergabunglah bersama kami sekarang</small>
    </header>

    <form method="POST" novalidate>
        
        <?php if (isset($error)): ?>
        <div class="error-msg">
            <i class="fas fa-exclamation-triangle"></i> <?=htmlspecialchars($error)?>
        </div>
        <?php endif; ?>

        <div class="toggle-role">
            <input type="radio" id="roleCustomer" name="role" value="customer" checked />
            <label for="roleCustomer"><i class="fas fa-user"></i> Customer</label>

            <input type="radio" id="roleAdmin" name="role" value="admin" />
            <label for="roleAdmin"><i class="fas fa-user-shield"></i> Admin</label>
        </div>

        <label>Nama Lengkap</label>
        <input type="text" name="fullname" placeholder="Nama Lengkap Anda" required />
        
        <label>Email Address</label>
        <input type="email" name="email" placeholder="email@contoh.com" required />
        
        <label>Password</label>
        <div class="password-input">
            <input type="password" name="password" required placeholder="••••••••" />
            <button type="button" class="toggle-password"><i class="far fa-eye"></i></button>
        </div>
        
        <label>Konfirmasi Password</label>
        <input type="password" name="conf_password" required placeholder="••••••••" />

        <button type="submit" class="btn-register">
          Daftar Sekarang <i class="fas fa-arrow-right" style="margin-left:8px; font-size:14px;"></i>
        </button>

        <p class="form-footer">
            Sudah punya akun? <a href="index.php">Masuk disini</a>
        </p>
    </form>
</div>

</body>
</html>