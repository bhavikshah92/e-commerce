<?php include '../includes/header.php'; ?>
<?php include '../includes/admin_navbar.php'; ?>

<?php
include '../config/database.php';

$id = $_GET['id'];

$stmt = $pdo->prepare('DELETE FROM products WHERE id = ?');
$stmt->execute([$id]);

header('Location: index.php');

include '../includes/footer.php';
?>
