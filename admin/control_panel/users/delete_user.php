<?php
require '../../../db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];

    if (!is_numeric($id)) {
        die('Invalid user ID.');
    }

    $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
    $stmt->execute([$id]);

    header('Location: ../../control_panel.php?page=users');
    exit;
}
?>
