<?php include '../includes/header.php'; ?>
<?php include '../includes/admin_navbar.php'; ?>

<?php
include '../config/database.php';

$stmt = $pdo->query('SELECT * FROM products');
$products = $stmt->fetchAll();
?>

<h1>Admin - Products</h1>
<ul class="products">
    <?php foreach ($products as $product): ?>
        <li>
            <img src="<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
            <h2><?php echo htmlspecialchars($product['name']); ?></h2>
            <p><?php echo htmlspecialchars($product['description']); ?></p>
            <p>$<?php echo htmlspecialchars($product['price']); ?></p>
            <a href="edit_product.php?id=<?php echo $product['id']; ?>">Edit</a>
            <a href="delete_product.php?id=<?php echo $product['id']; ?>">Delete</a>
        </li>
    <?php endforeach; ?>
</ul>

<?php include '../includes/footer.php'; ?>
