<?php include '../includes/header.php'; ?>
<?php include '../includes/navbar.php'; ?>

<?php
include '../config/database.php';

$payment_id = $_POST['razorpay_payment_id'];
if ($payment_id) {
    // Payment success logic here (e.g., save order details in the database)
    $cart = $_SESSION['cart'];
    $product_qty = $_SESSION['product_qty'];
    //echo "<pre>";
    //print_r($_SESSION);

    try {
        $pdo->beginTransaction();

        // Insert order
        $stmt = $pdo->prepare("INSERT INTO orders (user_id, total,payment_id) VALUES (?,?,?)");
        $stmt->execute([$_SESSION['user_id'], $_SESSION['total_amount'],$payment_id]);
        $order_id = $pdo->lastInsertId();

        // Insert order items
        $cart=$_SESSION['cart'];
        $productqty = $_SESSION['product_qty'];
        //foreach ($_SESSION['cart'] as $product_id => $quantity) {
        for($i=0;$i<count($cart);$i++){
            if(count($cart)>0){
                for($i=0;$i<count($cart);$i++){

                    $stmt = $pdo->prepare("SELECT * FROM products WHERE id IN (?)");
                    //echo "SELECT * FROM products WHERE id IN ($in_qry)";
                    $stmt->execute([$cart[$i]]);
                    $stmt->execute();
                    $products = $stmt->fetchAll();
                    //echo "<pre>";
                    //print_r($products);
                    $stmt = $pdo->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
                    $stmt->execute([$order_id, $cart[$i], $productqty[$i], $products[0]['price']]);
                }               
            }           
        }

        $pdo->commit();

        // Clear cart and redirect to success page
        unset($_SESSION['cart']);
        unset($_SESSION['product_qty']);
        unset($_SESSION['total_amount']);
        echo "<h1>Payment Successful</h1>";
        echo "<h4>Your payment ID is " . htmlspecialchars($payment_id) . "</h4>";

        exit();
    } catch (Exception $e) {
        $pdo->rollBack();
        echo "Failed to save order: " . $e->getMessage();
    }

    
} else {
    echo "<h1>Payment Failed</h1>";
}
?>

<?php include '../includes/footer.php'; ?>
