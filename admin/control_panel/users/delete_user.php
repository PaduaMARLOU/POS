<?php
require '../../../db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];

    if (!is_numeric($id)) {
        die('Invalid user ID.');
    }

    // Soft delete: set is_active = 0 and is_locked = 1
    $stmt = $pdo->prepare("UPDATE users SET is_active = 0, is_locked = 1 WHERE id = ?");
    $stmt->execute([$id]);

    header('Location: ../../control_panel.php?page=users');
    exit;
}
?>
