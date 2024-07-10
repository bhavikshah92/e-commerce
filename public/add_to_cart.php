<?php
//session_start();
include '../includes/header.php';
include '../includes/navbar.php';
//unset($_SESSION['cart']);
//die;
if (!isset($_SESSION['cart'])) {
    unset($_SESSION['cart']);
    unset($_SESSION['product_qty']);
}

$product_id = $_GET['id'];
echo $product_id;
$cart=$_SESSION['cart'];
//echo "<pre>";
//print_r($cart);
if (!in_array($product_id, $cart)) {
    $_SESSION['cart'][] = $product_id;
    $_SESSION['product_qty'][]=1;
    //echo "<pre>";
    //print_r($_SESSION['cart']);
}
else{
    //echo "Else";
}
//die;
header('Location: cart.php');

include '../includes/footer.php';
?>
