<?php include '../includes/header.php'; ?>
<?php include '../includes/admin_navbar.php'; ?>

<?php
include '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $image = $_POST['image'];

    $stmt = $pdo->prepare('INSERT INTO products (name, description, price, image) VALUES (?, ?, ?, ?)');
    $stmt->execute([$name, $description, $price, $image]);

    header('Location: index.php');
}
?>

<h1>Add Product</h1>
<form method="POST" action="add_product.php">
    <input type="text" name="name" placeholder="Product Name" required>
    <textarea name="description" placeholder="Product Description" required></textarea>
    <input type="text" name="price" placeholder="Product Price" required>
    <input type="text" name="image" placeholder="Product Image URL" required>
    <button type="submit">Add Product</button>
</form>

<?php include '../includes/footer.php'; ?>
