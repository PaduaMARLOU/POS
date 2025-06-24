<?php
require '../../../db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $account_type = $_POST['account_type'];
    $password = $_POST['password'];

    // New additional fields
    $is_active = isset($_POST['is_active']) ? (int)$_POST['is_active'] : 1;
    $is_locked = isset($_POST['is_locked']) ? (int)$_POST['is_locked'] : 0;
    $login_attempts = isset($_POST['login_attempts']) ? (int)$_POST['login_attempts'] : 0;

    // Validate ID
    if (!is_numeric($id)) {
        die('Invalid user ID.');
    }

    // Optional: update password only if provided
    if (!empty($password)) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $sql = "UPDATE users SET username = ?, email = ?, phone = ?, account_type = ?, password = ?, is_active = ?, is_locked = ?, login_attempts = ? WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$username, $email, $phone, $account_type, $hashedPassword, $is_active, $is_locked, $login_attempts, $id]);
    } else {
        $sql = "UPDATE users SET username = ?, email = ?, phone = ?, account_type = ?, is_active = ?, is_locked = ?, login_attempts = ? WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$username, $email, $phone, $account_type, $is_active, $is_locked, $login_attempts, $id]);
    }

    header('Location: ../../control_panel.php?page=users');
    exit;
}
?>
