<?php include '../includes/header.php'; ?>
<?php include '../includes/navbar.php'; ?>

<?php
include '../config/database.php';

$stmt = $pdo->query('SELECT * FROM products');
$products = $stmt->fetchAll();
?>

<div class="content">
    <h1>Products</h1>
    <ul class="products">
        <?php foreach ($products as $product): ?>
            <li>
                <img src="<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
                <h2><?php echo htmlspecialchars($product['name']); ?></h2>
                <p><?php echo htmlspecialchars($product['description']); ?></p>
                <p>Rs. <?php echo htmlspecialchars($product['price']); ?></p>
                <a href="add_to_cart.php?id=<?php echo $product['id']; ?>">Add to Cart</a>
            </li>
        <?php endforeach; ?>
    </ul>
</div>

<?php include '../includes/footer.php'; ?>
