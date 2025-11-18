<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'customer') {
    header("Location: ../index.php");
    exit;
}
include '../includes/db.php';

$userID = $_SESSION['user']['id'];

$stmt = $pdo->prepare("SELECT * FROM orders WHERE user_id = ? ORDER BY order_date DESC");
$stmt->execute([$userID]);
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Pesanan Saya</title>
<link href="../assets/css/customer.css" rel="stylesheet" />
</head>
<body>
<div class="customer-wrapper">
<?php include 'sidebar.php'; ?>

<main>
<h1>Pesanan Saya</h1>
<p>Lihat riwayat dan status pesanan Anda</p>

<?php if (count($orders) === 0): ?>
    <p>Anda belum melakukan pesanan.</p>
<?php else: ?>
    <?php foreach ($orders as $order): ?>
        <div class="order-card">
            <h3>Pesanan <?= htmlspecialchars($order['order_id']) ?></h3>
            <div><small><?= $order['order_date'] ?></small></div>
            <div>Status: <span class="status"><?= htmlspecialchars($order['order_status']) ?></span></div>
            <div>Total Pembayaran: <strong>Rp <?= number_format($order['total'], 0, ',', '.') ?></strong></div>
            <div>Status Bayar: <span class="payment-status"><?= htmlspecialchars($order['payment_status']) ?></span></div>

            <?php
            // Dapatkan item order
            $stmtItems = $pdo->prepare("SELECT oi.quantity, p.name, p.image, p.price FROM order_items oi JOIN products p ON oi.product_id = p.id WHERE oi.order_id = ?");
            $stmtItems->execute([$order['id']]);
            $items = $stmtItems->fetchAll(PDO::FETCH_ASSOC);
            ?>
            <div class="order-items">
                <?php foreach ($items as $item): ?>
                <div class="item">
                    <img src="../assets/img/<?= htmlspecialchars($item['image']) ?>" alt="<?= htmlspecialchars($item['name']) ?>" />
                    <div>
                        <div><?= htmlspecialchars($item['name']) ?></div>
                        <small><?= $item['quantity'] ?>x Rp <?= number_format($item['price'],0,',','.') ?></small>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

            <div>Pengiriman: <?= htmlspecialchars($order['shipping']) ?></div>
            <div>Pembayaran: <?= htmlspecialchars($order['payment_method']) ?></div>
        </div>
    <?php endforeach; ?>
<?php endif; ?>

</main>
</div>
</body>
</html>