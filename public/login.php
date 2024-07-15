<?php include '../includes/header.php'; ?>
<?php include '../includes/navbar.php'; ?>

<?php
include '../config/database.php';
$msg="";
if(isset($_GET["msg"])){
    $msg=$_GET["msg"];
}
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $msg="";
    $username = $_POST['username'];
    //$password = $_POST['password'];
    $password = md5($_POST['password']);
    $stmt = $pdo->prepare('SELECT * FROM users WHERE username = ?');
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    $error_msg="";

    if ($user && ($password==$user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        header('Location: index.php');
    } else {
        $error_msg="Invalid username or password";
    }
}
?>
<style>
    .center-screen {
        position: absolute;
      top: 50%;
      left: 50%;
      margin-right: -50%;
      transform: translate(-50%, -50%);
}
</style>
<h1>Login</h1>
<div class="center-screen">
<form method="POST" action="login.php">
    <input type="text" name="username" placeholder="Username" required>
    <input type="password" name="password" placeholder="Password" required>
    <button type="submit">Login</button>
</form>
<?php
    if(isset($error_msg)){
        echo "<b>".$error_msg."</b>";
    }

    if(isset($msg)){
        echo "<b>".$msg."</b>";
    }
    
?>
</div>
<?php include '../includes/footer.php'; ?>
