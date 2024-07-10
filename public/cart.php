<?php include '../includes/header.php'; ?>
<?php include '../includes/navbar.php'; ?>

<?php

include '../config/database.php';

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
    $_SESSION['product_qty']=[];
}

$cart = $_SESSION['cart'];
//$productqty = $_SESSION['product_qty'];
//echo "<pre>";
//print_r($cart);
//print_r($product_qty);
$products = [];

if (!empty($cart)) {
   //$placeholders = implode(',', array_fill(0, count($cart), '?'));
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
?>
<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    //echo $_SERVER['REQUEST_METHOD'];    
    if (isset($_POST['update_cart'])) {
        //echo "Qty:<pre>";
        //print_r($_POST['quantity']);
        //die;
        foreach ($_POST['quantity'] as $id => $quantity) {
            //echo "ID:".$id;
            //echo "Qty:".$quantity;
            
            if ($quantity == 0) {
                unset($_SESSION['product_qty'][$id]);
            } else {
                $_SESSION['product_qty'][$id] = $quantity;
            }
        }
        header('Location: cart.php?msg=Product Quantity Updated Successfully');
    } elseif (isset($_POST['remove_product'])) {
        $remove_id = $_POST['remove_product'];
        $cart_remove=$_SESSION['cart'];
        $qty_remove=$_SESSION['product_qty'];

        //echo "1:<pre>";
        //print_r($_SESSION["cart"]);
        //print_r($_SESSION["product_qty"]);

        unset($cart_remove[$remove_id]);
        unset($qty_remove[$remove_id]);

        $cart_remove = array_values($cart_remove);
        $qty_remove = array_values($qty_remove);

        $_SESSION["cart"]=$cart_remove;
        $_SESSION["product_qty"]=$qty_remove;

        
        // Reset the array keys
        //$_SESSION['cart'] = array_values($_SESSION['cart']);
        //$_SESSION['product_qty'] = array_values($_SESSION['product_qty']);

        // Print the result
        //echo "2:<pre>";
        //print_r($_SESSION['cart']);
        //print_r($_SESSION['product_qty']);
        //echo '<script>alert("Product Quantity Updated Successfully");</script>';
        header('Location: cart.php?msg=Product Removed Successfully');
    }
    
}
?>
<div class="content">
    <h1>Cart</h1>
    <?php
        if(isset($_REQUEST["msg"])){
            echo "<h4 style='color:green;text-align:center;'>".$_REQUEST["msg"]."</h4>";
        }
    ?>
    <form method="POST" action="cart.php">
        <table>
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Image</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Total</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php $total = 0;
               
                    $cnt=0;
                
                ?>
                <?php foreach ($products as $product): ?>
                    <?php
                    
                    $product_id = $product['id'];
                    $quantity = isset($_SESSION['product_qty'][$cnt]) ? $_SESSION['product_qty'][$cnt] : 1;
                    $subtotal = $product['price'] * $quantity;
                    $total += $subtotal;
                    ?>
                    <tr>
                        <td><?php echo htmlspecialchars($product['name']); ?></td>
                        <td><img src="<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" style="width: 50px; height: auto;"></td>
                        <td>Rs. <?php echo htmlspecialchars($product['price']); ?></td>
                        <td>
                            <button type="button" onclick="decrementQuantity(<?php echo $product_id; ?>)">-</button>
                            <!-- <input type="text" name="quantity[<?php echo $product_id; ?>]" id="quantity_<?php echo $product_id; ?>" value="<?php echo $quantity; ?>" size="2" readonly> -->
                            <input type="text" name="quantity[<?php echo $cnt; ?>]" id="quantity_<?php echo $product_id; ?>" value="<?php echo $quantity; ?>" size="2" readonly>
                            <button type="button" onclick="incrementQuantity(<?php echo $product_id; ?>)">+</button>
                        </td>
                        <td>Rs. <?php echo number_format($subtotal, 2); ?></td>
                        <td>
                            <button type="submit" name="remove_product" value="<?php echo $cnt; ?>">Remove</button>
                        </td>
                    </tr>
                    <?php 
                   
                    $cnt++;
                     ?>
                <?php endforeach; ?>
                <tr>
                    <td colspan="4" style="text-align: right;">Total:</td>
                    <td>Rs. <?php echo number_format($total, 2); ?></td>
                    <td></td>
                </tr>
            </tbody>
        </table>
        <button type="submit" name="update_cart">Update Cart</button>
        <!--<input type="submit" name="update_cart" value="Update Cart" />-->
    </form>
    <a href="checkout.php" class="button">Checkout</a>
</div>

<script>
    function incrementQuantity(productId) {
        var quantityInput = document.getElementById('quantity_' + productId);
        var currentQuantity = parseInt(quantityInput.value);
        quantityInput.value = currentQuantity + 1;
    }

    function decrementQuantity(productId) {
        var quantityInput = document.getElementById('quantity_' + productId);
        var currentQuantity = parseInt(quantityInput.value);
        if (currentQuantity > 1) {
            quantityInput.value = currentQuantity - 1;
        }
    }
</script>

<?php include '../includes/footer.php'; ?>
