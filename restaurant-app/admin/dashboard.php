<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header('Location: ../index.php');
    exit;
}

include '../includes/db.php';

// Data pelanggan
$stmtCustomers = $pdo->query("SELECT COUNT(*) AS total FROM users WHERE role='customer'");
$totalCustomers = $stmtCustomers->fetch(PDO::FETCH_ASSOC)['total'];

// Data pendapatan
$stmtIncome = $pdo->query("SELECT SUM(total) as total_income FROM orders WHERE payment_status='Lunas'");
$totalIncome = $stmtIncome->fetch(PDO::FETCH_ASSOC)['total_income'] ?? 0;

// Data rata-rata pemesanan
$stmtOrders = $pdo->query("SELECT AVG(total) as avg_order FROM orders");
$avgOrder = $stmtOrders->fetch(PDO::FETCH_ASSOC)['avg_order'] ?? 0;

// Note: Untuk lengkapnya bisa tambahkan query dan chart js pada tampilan sesuai gambar

?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Admin Dashboard</title>
<link href="../assets/css/admin.css" rel="stylesheet" />
</head>
<body>
<div class="admin-dashboard">
    <header>
        <h1>Admin Dashboard</h1>
        <nav>
            <a href="profile.php"><?=$_SESSION['user']['fullname']?></a>
            <a href="../logout.php">Keluar</a>
        </nav>
    </header>

    <main>
        <section class="stats">
            <div class="card purple">
                <h3>Total Pelanggan</h3>
                <p><?= $totalCustomers ?></p>
            </div>
            <div class="card green">
                <h3>Total Pendapatan</h3>
                <p>Rp <?= number_format($totalIncome, 0, ',', '.') ?></p>
            </div>
            <div class="card blue">
                <h3>Rata-rata Pesanan</h3>
                <p>Rp <?= number_format($avgOrder, 0, ',', '.') ?></p>
            </div>
        </section>
        <section class="main-charts">
            <!-- Chart JS bisa ditambahkan di sini -->
        </section>
    </main>
</div>
</body>
</html>