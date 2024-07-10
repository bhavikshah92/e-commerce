<?php include '../includes/header.php'; ?>
<?php include '../includes/admin_navbar.php'; ?>

<?php
include '../config/database.php';

$id = $_GET['id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $image = $_POST['image'];

    $stmt = $pdo->prepare('UPDATE products SET name = ?, description = ?, price = ?, image = ? WHERE id = ?');
    $stmt->execute([$name, $description, $price, $image, $id]);

    header('Location: index.php');
} else {
    $stmt = $pdo->prepare('SELECT * FROM products WHERE id = ?');
    $stmt->execute([$id]);
    $product = $stmt->fetch();
}
?>

<h1>Edit Product</h1>
<form method="POST" action="edit_product.php?id=<?php echo $id; ?>">
    <input type="text" name="name" value="<?php echo htmlspecialchars($product['name']); ?>" required>
    <textarea name="description" required><?php echo htmlspecialchars($product['description']); ?></textarea>
    <input type="text" name="price" value="<?php echo htmlspecialchars($product['price']); ?>" required>
    <input type="text" name="image" value="<?php echo htmlspecialchars($product['image']); ?>" required>
    <button type="submit">Update Product</button>
</form>

<?php include '../includes/footer.php'; ?>
