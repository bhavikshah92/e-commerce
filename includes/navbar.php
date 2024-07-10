<?php
session_start();
?>

<nav>
    <ul>
        <li><a href="index.php">Home</a></li>
        <li><a href="products.php">Products</a></li>
        <?php if (isset($_SESSION['user_id'])): ?>
            <li><a href="cart.php">Cart</a></li>
            <li><a href="orders.php">Order History</a></li>
            <li><a href="logout.php">Logout</a></li>
        <?php else: ?>
            <li><a href="login.php">Login</a></li>
            <li><a href="register.php">Register</a></li>
        <?php endif; ?>
    </ul>
</nav>
