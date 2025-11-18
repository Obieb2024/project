<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: ../index.php");
    exit;
}
include '../includes/db.php';

// Cari pelanggan
$search = $_GET['search'] ?? '';
$query = "SELECT * FROM users WHERE role = 'customer' AND (fullname LIKE :search OR email LIKE :search) ORDER BY id DESC";
$stmt = $pdo->prepare($query);
$stmt->execute(['search' => "%$search%"]);
$customers = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Statistik sederhana
$stmtCount = $pdo->query("SELECT COUNT(id) as total_customers FROM users WHERE role='customer'");
$totalCustomers = $stmtCount->fetchColumn();

// Statistik pendapatan, avg order, dan pelanggan terbaik bisa diambil dari tabel orders (contoh di dashboard.php)
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Admin - Pelanggan</title>
<link href="../assets/css/admin.css" rel="stylesheet" />
</head>
<body>
<div class="admin-wrapper">
    <?php include 'sidebar.php'; ?>

    <main>
        <h1>Pelanggan</h1>
        <p>Kelola data dan riwayat pelanggan</p>

        <div class="search-box">
            <form action="" method="get">
                <input type="search" name="search" placeholder="Cari pelanggan..." value="<?=htmlspecialchars($search)?>">
                <button type="submit">Cari</button>
            </form>
        </div>

        <div class="customer-list">
            <?php foreach ($customers as $c): ?>
            <div class="customer-card">
                <div class="avatar"><?= strtoupper($c['fullname'][0]) ?></div>
                <h4><?= htmlspecialchars($c['fullname']) ?></h4>
                <div class="rating">⭐ <?= number_format((float)$c['rating'], 1) ?></div>
                <p>Email: <?= htmlspecialchars($c['email']) ?></p>
                <p>Telepon: <?= htmlspecialchars($c['phone']) ?></p>
                <p>Alamat: <?= htmlspecialchars($c['address']) ?></p>
                <div class="badges">VIP Customer</div>
                <!-- Total pesanan dan total belanja harus hitung query -->
            </div>
            <?php endforeach; ?>
        </div>
    </main>
</div>
</body>
</html>