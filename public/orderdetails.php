<?php include '../includes/header.php'; ?>
<?php include '../includes/navbar.php'; ?>

<?php

include '../config/database.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}
else{
    $stmt = $pdo->prepare("SELECT * FROM order_items WHERE order_id IN (?)");
    //echo "SELECT * FROM products WHERE id IN ($in_qry)";
    $stmt->execute([$_REQUEST['id']]);
    $order_items = $stmt->fetchAll();
}
?>
<div class="content">
    <h1>Order Items for Order #:<?php echo $_REQUEST["id"] ?></h1>
        <table>
            <thead>
                <tr>
                    <th>Sr No</th>
                    <th>Product</th>
                    <th>Image</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <?php $total=0;$cnt = 1;$subtotal=0;$total_qty=0; ?>
                <?php foreach ($order_items as $item): ?>
                    <?php

                        $quantity = isset($item['quantity']) ? $item['quantity'] : 1;
                        $total_qty+=$quantity;
                        $subtotal = $item['price'] * $quantity;
                        $total += $subtotal;

                        $stmt = $pdo->prepare("SELECT * FROM products WHERE id IN (?)");
                        //echo "SELECT * FROM products WHERE id IN ($in_qry)";
                        $stmt->execute([$item["product_id"]]);
                        $products = $stmt->fetchAll();
                        $product_name="";
                        $product_image="";
                        foreach ($products as $product){
                            $product_name=$product["name"];
                            $product_image=$product["image"];
                        }
                    ?>

                    <tr>
                        <td><?php echo htmlspecialchars($cnt); ?></td>
                        <td><?php echo htmlspecialchars($product_name); ?></td>
                        <td><img src="<?php echo htmlspecialchars($product_image); ?>" alt="<?php echo htmlspecialchars($product_name); ?>" style="width: 50px; height: auto;"></td>
                        <td>Rs. <?php echo number_format($item['price'], 2); ?></td>
                        <td><?php echo htmlspecialchars($item['quantity']); ?></td>
                        <td>Rs. <?php echo number_format($subtotal, 2); ?></td>
                    </tr>
                    <?php $cnt++;  ?>
                    <?php endforeach; ?>
                <tr>
                    <td colspan="3" style="text-align: right;">Total:</td>
                    <td>Rs. <?php echo number_format($total, 2); ?></td>
                    <td><?php echo $total_qty; ?></td>
                    <td></td>
                </tr>
            </tbody>
        </table>
</div>

<?php include '../includes/footer.php'; ?>
