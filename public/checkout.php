<?php include '../includes/header.php'; ?>
<?php include '../includes/navbar.php'; ?>

<?php
include '../config/database.php';
if(!isset($_SESSION['user_id'])){
    header('Location: login.php');
    exit();
}
if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    header('Location: cart.php');
    exit();
}

$cart = $_SESSION['cart'];
$products = [];

if (!empty($cart)) {
    $in_qry="";
   if(count($cart)>0){
        for($i=0;$i<count($cart);$i++){
            $in_qry.=$cart[$i].",";
        }
        if(strlen($in_qry)>0){
            $in_qry=substr($in_qry,0,strlen($in_qry)-1);
        }
        //echo $in_qry;
        $stmt = $pdo->prepare("SELECT * FROM products WHERE id IN ($in_qry)");
        //echo "SELECT * FROM products WHERE id IN ($in_qry)";
        //$stmt->execute(array_keys($cart));
        $stmt->execute();
        $products = $stmt->fetchAll();
   }
}

$total = 0;
$cnt=0;
foreach ($products as $product) {

    $quantity = isset($_SESSION['product_qty'][$cnt]) ? $_SESSION['product_qty'][$cnt] : 1;
    $subtotal = $product['price'] * $quantity;
    $total += $subtotal;
$cnt++;
    //$total += $product['price'] * $_SESSION['cart'][$product['id']];
}
$_SESSION['total_amount']=$total;
$apiKey = "rzp_test_VPwajtGngVxrcn";
?>

<div class="content">
    <h1>Checkout</h1>
    <form action="payment_success.php" method="POST">
        <script
            src="https://checkout.razorpay.com/v1/checkout.js"
            data-key="<?php echo $apiKey; ?>"
            data-amount="<?php echo $total * 100; ?>"
            data-currency="INR"
            data-buttontext="Pay with Razorpay"
            data-name="E-Commerce Website"
            data-description="Purchase Description"
            data-image=""
            data-prefill.name=""
            data-prefill.email=""
            data-theme.color="#F37254"
        ></script>
        <input type="hidden" name="shopping_order_id" value="3456">
    </form>
</div>

<?php include '../includes/footer.php'; ?>
