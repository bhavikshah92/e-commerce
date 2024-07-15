<?php include '../includes/header.php'; ?>
<?php include '../includes/navbar.php'; ?>

<?php
include '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    //$password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $password = md5($_POST['password']);

    $error_msg="";

    $regex = '/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/'; 
    if(!preg_match($regex, $email)){
        $error_msg="Invalid Email ID<br/>";
    }

    $stmt=$pdo->prepare("Select * from users where username=? or email=?");
    $stmt->execute([$username,$email]);
    $user = $stmt->fetch();
    if(!empty($user)){
        if($user["username"]==$username){
            $error_msg.="Username Already Exist<br/>";
        }

        if($user["email"]==$email){
            $error_msg.="Email ID Already Exist<br/>";
        }
    }

    if($error_msg!=""){
        //Do Nothing
    }
    else{
        $stmt = $pdo->prepare('INSERT INTO users (username, email, password) VALUES (?, ?, ?)');
        $stmt->execute([$username, $email, $password]);

        header('Location: login.php?msg=User Registered Successfully');
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
<h1>Register</h1>
<div class="center-screen">
<form method="POST" action="register.php">
    <input type="text" name="username" placeholder="Username" required>
    <input type="email" name="email" placeholder="Email" required>
    <input type="password" name="password" placeholder="Password" required>
    <button type="submit">Register</button>
</form>
<?php
    if(isset($error_msg)){
        echo "<b>".$error_msg."</b>";
    }
    
?>
</div>
<?php include '../includes/footer.php'; ?>
