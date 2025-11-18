<nav class="customer-sidebar">
<ul>
    <li><a href="menu.php" class="<?=basename($_SERVER['PHP_SELF'])=='menu.php'?'active':''?>">Menu</a></li>
    <li><a href="cart.php" class="<?=basename($_SERVER['PHP_SELF'])=='cart.php'?'active':''?>">Keranjang</a></li>
    <li><a href="orders.php" class="<?=basename($_SERVER['PHP_SELF'])=='orders.php'?'active':''?>">Pesanan Saya</a></li>
    <li><a href="profile.php" class="<?=basename($_SERVER['PHP_SELF'])=='profile.php'?'active':''?>">Profil</a></li>
</ul>
</nav>