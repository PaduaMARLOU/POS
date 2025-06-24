<?php
require '../../../db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username       = trim($_POST['username'] ?? '');
    $password       = $_POST['password'] ?? '';
    $email          = trim($_POST['email'] ?? '');
    $phone          = trim($_POST['phone'] ?? '');
    $account_type   = $_POST['account_type'] ?? 'user';

    $is_active      = isset($_POST['is_active']) ? (int)$_POST['is_active'] : 1;
    $is_locked      = isset($_POST['is_locked']) ? (int)$_POST['is_locked'] : 0;
    $login_attempts = isset($_POST['login_attempts']) ? (int)$_POST['login_attempts'] : 0;

    // Validation
    if (empty($username) || empty($password)) {
        echo "<script>
            alert('Username and password are required.');
            window.history.back();
        </script>";
        exit;
    }

    // Check if username already exists
    $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ?");
    $stmt->execute([$username]);
    if ($stmt->fetch()) {
        echo "<script>
            alert('Username already exists.');
            window.history.back();
        </script>";
        exit;
    }

    // Hash password and insert
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("
        INSERT INTO users (username, password, email, phone, account_type, is_active, is_locked, login_attempts)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)
    ");
    $stmt->execute([$username, $hashedPassword, $email, $phone, $account_type, $is_active, $is_locked, $login_attempts]);

    // Redirect on success
    header('Location: ../../control_panel.php?page=users');
    exit;
}
?>
