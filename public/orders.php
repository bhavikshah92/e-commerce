<?php include '../includes/header.php'; ?>
<?php include '../includes/navbar.php'; ?>

<?php

include '../config/database.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}
else{
    $stmt = $pdo->prepare("SELECT * FROM orders WHERE user_id IN (?)");
    //echo "SELECT * FROM products WHERE id IN ($in_qry)";
    $stmt->execute([$_SESSION['user_id']]);
    $orders = $stmt->fetchAll();
}
?>
<div class="content">
    <h1>Order History</h1>
        <table>
            <thead>
                <tr>
                    <th>Sr No</th>
                    <th>Order ID</th>
                    <th>Order Date</th>
                    <th>Payment ID</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <?php $cnt = 1; ?>
                <?php foreach ($orders as $order): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($cnt); ?></td>
                        <td><a href="orderdetails.php?id=<?php echo htmlspecialchars($order['id']); ?>"><?php echo htmlspecialchars($order['id']); ?></a></td>
                        <td><?php echo date('d-m-Y',strtotime($order['created_at'])); ?></td>
                        <td><?php echo htmlspecialchars($order['payment_id']); ?></td>
                        <td>Rs. <?php echo number_format($order['total'], 2); ?></td>
                    </tr>
                    <?php $cnt++;  ?>
                <?php endforeach; ?>
            </tbody>
        </table>
</div>

<?php include '../includes/footer.php'; ?>
