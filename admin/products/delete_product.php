<?php
require_once '../../db.php';

if (!isset($_GET['id'])) {
    header("Location: products.php");
    exit;
}

$id = $_GET['id'];

$stmt = $pdo->prepare("UPDATE products SET is_active = 0 WHERE id = ?");
$stmt->execute([$id]);

header("Location: ../products.php");
exit;
?>
