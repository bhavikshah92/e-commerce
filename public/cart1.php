<?php include '../includes/header.php'; ?>
<?php include '../includes/navbar.php'; ?>

<?php
include '../config/database.php';

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

$cart = $_SESSION['cart'];

$products = [];

if (!empty($cart)) {
    //$placeholders = implode(',', array_fill(0, count($cart), '?'));
    $in_qry="";
    foreach($cart as $c){
        $in_qry.=$c.",";
    }
    if(strlen($in_qry)>0){
        $in_qry=substr($in_qry,0,strlen($in_qry)-1);
    }
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id IN ($in_qry)");
    //echo "SELECT * FROM products WHERE id IN ($placeholders)";
    //$stmt->execute(array_keys($cart));
    $stmt->execute();
    $products = $stmt->fetchAll();
    
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['update_quantity'])) {
        foreach ($_POST['quantity'] as $id => $quantity) {
            if ($quantity == 0) {
                unset($_SESSION['cart'][$id]);
            } else {
                $_SESSION['cart'][$id] = $quantity;
            }
        }
    } elseif (isset($_POST['remove_product'])) {
        $remove_id = $_POST['remove_product'];
        unset($_SESSION['cart'][$remove_id]);
    }
    header('Location: cart.php');
}
?>

<div class="content">
    <h1>Cart</h1>
    <form method="POST" action="cart.php">
        <table>
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Image</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Total</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php $total = 0; ?>
                <?php foreach ($products as $product): ?>
                    <?php $subtotal = $product['price'] * $_SESSION['cart'][$product['id']]; ?>
                    <?php $total += $subtotal; ?>
                    <tr>
                        <td><?php echo htmlspecialchars($product['name']); ?></td>
                        <td><img src="<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" style="width: 50px; height: auto;"></td>
                        <td>$<?php echo htmlspecialchars($product['price']); ?></td>
                        <td>
                            <button type="submit" name="quantity[<?php echo $product['id']; ?>]" value="<?php echo $_SESSION['cart'][$product['id']] - 1; ?>" <?php echo $_SESSION['cart'][$product['id']] <= 1 ? 'disabled' : ''; ?>>-</button>
                            <input type="text" name="quantity[<?php echo $product['id']; ?>]" value="<?php echo $_SESSION['cart'][$product['id']]; ?>" size="2" readonly>
                            <button type="submit" name="quantity[<?php echo $product['id']; ?>]" value="<?php echo $_SESSION['cart'][$product['id']] + 1; ?>">+</button>
                        </td>
                        <td>$<?php echo number_format($subtotal, 2); ?></td>
                        <td>
                            <button type="submit" name="remove_product" value="<?php echo $product['id']; ?>">Remove</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <tr>
                    <td colspan="4" style="text-align: right;">Total:</td>
                    <td>$<?php echo number_format($total, 2); ?></td>
                    <td></td>
                </tr>
            </tbody>
        </table>
        <button type="submit" name="update_quantity">Update Cart</button>
    </form>
    <a href="checkout.php" class="button">Checkout</a>
</div>

<?php include '../includes/footer.php'; ?>
