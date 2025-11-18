<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'customer') {
    header("Location: ../index.php");
    exit;
}
include '../includes/db.php';

$products = $pdo->query("SELECT * FROM products ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);

// Tambah ke keranjang
if (isset($_POST['add_to_cart'])) {
    $product_id = (int)$_POST['product_id'];
    $qty = max(1, (int)$_POST['quantity']);
    
    if (!isset($_SESSION['cart'])) $_SESSION['cart'] = [];
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id] += $qty;
    } else {
        $_SESSION['cart'][$product_id] = $qty;
    }
    header("Location: menu.php");
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Menu</title>
<link href="../assets/css/customer.css" rel="stylesheet" />
</head>
<body>
<div class="customer-wrapper">
<?php include 'sidebar.php'; ?>

<main>
<h1>Menu</h1>

<div class="products-container">
    <?php foreach ($products as $p): ?>
    <div class="product-card">
        <img src="../assets/img/<?= htmlspecialchars($p['image']) ?>" alt="<?= htmlspecialchars($p['name']) ?>">
        <h4><?= htmlspecialchars($p['name']) ?></h4>
        <p><?= htmlspecialchars($p['description']) ?></p>
        <p>Harga: Rp <?= number_format($p['price'],0,',','.') ?></p>

        <form method="POST" class="add-cart-form">
            <input type="hidden" name="product_id" value="<?= $p['id'] ?>" />
            <input type="number" name="quantity" value="1" min="1" style="width: 50px" />
            <button type="submit" name="add_to_cart">Tambah ke Keranjang</button>
        </form>
    </div>
    <?php endforeach; ?>
</div>
</main>
</div>
</body>
</html>