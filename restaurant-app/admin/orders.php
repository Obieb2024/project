<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: ../index.php");
    exit;
}
include '../includes/db.php';

$status_filter = $_GET['status'] ?? 'Semua';
$params = [];
$sql = "SELECT o.*, u.fullname, u.phone FROM orders o JOIN users u ON o.user_id = u.id";

if ($status_filter !== 'Semua') {
    $sql .= " WHERE o.order_status = ?";
    $params[] = $status_filter;
}

$sql .= " ORDER BY order_date DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Update status via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_id'], $_POST['status'])) {
    $update = $pdo->prepare("UPDATE orders SET order_status = ? WHERE id = ?");
    $update->execute([$_POST['status'], $_POST['order_id']]);
    header("Location: orders.php");
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Admin - Pesanan</title>
<link href="../assets/css/admin.css" rel="stylesheet" />
</head>
<body>
<div class="admin-wrapper">
<?php include 'sidebar.php';?>

<main>
<h1>Pesanan</h1>

<div class="status-filter">
    <a href="?status=Semua" class="<?= $status_filter=='Semua'?'active':'' ?>">Semua</a>
    <a href="?status=Menunggu" class="<?= $status_filter=='Menunggu'?'active':'' ?>">Menunggu</a>
    <a href="?status=Diproses" class="<?= $status_filter=='Diproses'?'active':'' ?>">Diproses</a>
    <a href="?status=Siap" class="<?= $status_filter=='Siap'?'active':'' ?>">Siap</a>
    <a href="?status=Selesai" class="<?= $status_filter=='Selesai'?'active':'' ?>">Selesai</a>
    <a href="?status=Dibatalkan" class="<?= $status_filter=='Dibatalkan'?'active':'' ?>">Dibatalkan</a>
</div>

<table class="orders-table">
<thead>
<tr>
    <th>ID</th>
    <th>Pelanggan</th>
    <th>Item</th>
    <th>Total</th>
    <th>Pengiriman</th>
    <th>Pembayaran</th>
    <th>Status Bayar</th>
    <th>Status</th>
    <th>Tanggal</th>
    <th>Aksi</th>
</tr>
</thead>
<tbody>
<?php foreach ($orders as $order): ?>
<tr>
    <td><?= htmlspecialchars($order['order_id']) ?></td>
    <td><?= htmlspecialchars($order['fullname']) ?><br><?= htmlspecialchars($order['phone']) ?></td>
    <td>
        <?php 
        $stmtItems = $pdo->prepare("SELECT oi.quantity, p.name FROM order_items oi JOIN products p ON oi.product_id = p.id WHERE oi.order_id = ?");
        $stmtItems->execute([$order['id']]);
        $items = $stmtItems->fetchAll(PDO::FETCH_ASSOC);
        foreach ($items as $item) {
            echo $item['name'] . ' ' . $item['quantity'] . 'x<br>';
        }
        ?>
    </td>
    <td>Rp <?= number_format($order['total'], 0, ',', '.') ?></td>
    <td><?= htmlspecialchars($order['shipping']) ?></td>
    <td><?= htmlspecialchars($order['payment_method']) ?></td>
    <td><?= htmlspecialchars($order['payment_status']) ?></td>
    <td>
        <form method="POST" class="status-form">
            <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
            <select name="status" onchange="this.form.submit()">
                <?php 
                $statuses = ['Menunggu','Diproses','Siap','Selesai','Dibatalkan'];
                foreach ($statuses as $status): ?>
                    <option value="<?= $status ?>" <?=$order['order_status']==$status?'selected':''?>><?= $status ?></option>
                <?php endforeach;?>
            </select>
        </form>
    </td>
    <td><?= $order['order_date'] ?></td>
    <td><a href="order_detail.php?id=<?= $order['id'] ?>">Detail</a></td>
</tr>
<?php endforeach; ?>
</tbody>
</table>
</main>
</div>
</body>
</html>