<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: ../index.php");
    exit;
}
include '../includes/db.php';

// Hapus produk
if (isset($_GET['delete'])) {
    $id = (int) $_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
    $stmt->execute([$id]);
    header("Location: products.php");
    exit;
}

// Tambah atau edit produk (bisa buat form di bawah)

$products = $pdo->query("SELECT * FROM products ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);

// Proses simpan produk di bawah jika POST

?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Admin - Produk</title>
<link href="../assets/css/admin.css" rel="stylesheet" />
</head>
<body>
<div class="admin-wrapper">
<?php include 'sidebar.php'; ?>

<main>
<h1>Produk</h1>
<div class="product-list">
    <?php foreach ($products as $p): ?>
    <div class="product-card">
        <img src="../assets/img/<?= htmlspecialchars($p['image']) ?>" alt="<?= htmlspecialchars($p['name']) ?>">
        <h3><?= htmlspecialchars($p['name']) ?></h3>
        <p><?= htmlspecialchars($p['description']) ?></p>
        <p>Harga Jual: Rp <?= number_format($p['price'], 0, ',', '.') ?></p>
        <p>Profit: Rp <?= number_format($p['profit'], 0, ',', '.') ?></p>
        <p>Stok: <?= $p['stock'] ?></p>
        <p>Margin: <?= $p['margin'] ?>%</p>
        <p>Kategori: <?= htmlspecialchars($p['category']) ?></p>
        <a href="edit_product.php?id=<?= $p['id'] ?>" class="btn btn-edit">Edit</a>
        <a href="?delete=<?= $p['id'] ?>" class="btn btn-delete" onclick="return confirm('Hapus produk ini?')">Hapus</a>
    </div>
    <?php endforeach; ?>
</div>

<a href="add_product.php" class="btn btn-add">+ Tambah Produk</a>

</main>
</div>
</body>
</html>