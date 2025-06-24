<?php
require '../../../db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $account_type = $_POST['account_type'];
    $password = $_POST['password'];

    // Validate ID
    if (!is_numeric($id)) {
        die('Invalid user ID.');
    }

    // Optional: update password only if provided
    if (!empty($password)) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $sql = "UPDATE users SET username = ?, email = ?, phone = ?, account_type = ?, password = ? WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$username, $email, $phone, $account_type, $hashedPassword, $id]);
    } else {
        $sql = "UPDATE users SET username = ?, email = ?, phone = ?, account_type = ? WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$username, $email, $phone, $account_type, $id]);
    }

    header('Location: ../../control_panel.php?page=users');
    exit;
}
?>
