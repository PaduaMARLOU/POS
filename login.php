<?php
session_start();
require 'db.php';

if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit();
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user) {
        if (!$user['is_active']) {
            $error = "Account is deactivated.";
        } elseif ($user['is_locked']) {
            $error = "Account is locked due to multiple failed login attempts.";
        } elseif (password_verify($password, $user['password'])) {
            // Successful login
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['account_type'] = $user['account_type'];

            // Reset login attempts
            $update = $pdo->prepare("UPDATE users SET last_login = NOW(), login_attempts = 0 WHERE id = ?");
            $update->execute([$user['id']]);

            header("Location: dashboard.php");
            exit();
        } else {
            // Wrong password: increment login_attempts
            $newAttempts = $user['login_attempts'] + 1;
            $isLocked = $newAttempts >= 5 ? 1 : 0;

            $update = $pdo->prepare("UPDATE users SET login_attempts = ?, is_locked = ? WHERE id = ?");
            $update->execute([$newAttempts, $isLocked, $user['id']]);

            $error = $isLocked ? "Account locked after 5 failed attempts." : "Invalid username or password.";
        }
    } else {
        $error = "Invalid username or password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Login - POS</title>
    <link rel="stylesheet" href="css/login.css">
    <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body>
    <div class="login-container">
        <h2>Login</h2>

        <?php if (!empty($error)): ?>
            <div class="error-message"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <form method="POST">
            <label for="username">Username</label>
            <input type="text" name="username" id="username" required>

            <label for="password">Password</label>
            <div class="password-wrapper">
                <input type="password" name="password" id="password" required>
                <button type="button" id="togglePassword" class="toggle-password" aria-label="Toggle password visibility">
                    <i data-lucide="eye"></i>
                </button>
            </div>

            <button type="submit" id="loginButton">Login</button>
        </form>
    </div>

    <script src="js/login.js"></script>
</body>
</html>
