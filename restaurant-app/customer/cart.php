<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'customer') {
    header("Location: ../index.php");
    exit;
}
include '../includes/db.php';

// Keranjang disimpan di session: $_SESSION['cart'] = [product_id=>qty,...]

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Hapus item keranjang
if (isset($_GET['remove'])) {
    $id = (int)$_GET['remove'];
    unset($_SESSION['cart'][$id]);
    header("Location: cart.php");
    exit;
}

// Update qty lewat POST
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['qty'], $_POST['product_id'])) {
    $pid = (int)$_POST['product_id'];
    $qty = (int)$_POST['qty'];
    if ($qty <= 0) {
        unset($_SESSION['cart'][$pid]);
    } else {
        $_SESSION['cart'][$pid] = $qty;
    }
    header("Location: cart.php");
    exit;
}

// Ambil data produk keranjang
$cartItems = [];
$total = 0;
if (!empty($_SESSION['cart'])) {
    $ids = implode(',', array_keys($_SESSION['cart']));
    $stmt = $pdo->query("SELECT * FROM products WHERE id IN ($ids)");
    $cartItems = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Keranjang</title>
<link href="../assets/css/customer.css" rel="stylesheet" />
</head>
<body>
<div class="customer-wrapper">
<?php include 'sidebar.php'; ?>

<main>
<h1>Keranjang</h1>
<p>Review pesanan Anda sebelum checkout</p>

<?php if (empty($cartItems)): ?>
    <div class="empty-cart">
        <img src="../assets/img/cart-empty.svg" alt="Keranjang Kosong">
        <h3>Keranjang Anda Kosong</h3>
        <p>Mulai belanja untuk menambahkan item</p>
    </div>
<?php else: ?>
<table class="cart-table">
<thead>
<tr>
    <th>Produk</th>
    <th>Harga</th>
    <th>Jumlah</th>
    <th>Subtotal</th>
    <th>Aksi</th>
</tr>
</thead>
<tbody>
<?php foreach ($cartItems as $item):
    $qty = $_SESSION['cart'][$item['id']];
    $subtotal = $qty * $item['price'];
    $total += $subtotal;
?>
<tr>
    <td><?= htmlspecialchars($item['name']) ?></td>
    <td>Rp <?= number_format($item['price'],0,',','.') ?></td>
    <td>
        <form method="POST">
            <input type="hidden" name="product_id" value="<?= $item['id'] ?>">
            <input type="number" name="qty" value="<?= $qty ?>" min="1" style="width: 50px" />
            <button type="submit">Update</button>
        </form>
    </td>
    <td>Rp <?= number_format($subtotal,0,',','.') ?></td>
    <td><a href="?remove=<?= $item['id'] ?>" onclick="return confirm('Hapus item?')">Hapus</a></td>
</tr>
<?php endforeach; ?>
</tbody>
<tfoot>
<tr>
    <td colspan="3" style="text-align:right"><strong>Total:</strong></td>
    <td colspan="2">Rp <?= number_format($total,0,',','.') ?></td>
</tr>
</tfoot>
</table>

<a href="checkout.php" class="btn-checkout">Checkout Sekarang</a>
<?php endif; ?>

</main>
</div>
</body>
</html>