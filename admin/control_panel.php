<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Control Panel</title>
</head>
<body>

<div class="container">
    <?php include 'sidebar.php'; ?>

    <div id="main-content">
        <h2>Control Panel</h2>
        <p>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>! Manage your settings here.</p>
        <a href="logout.php">Logout</a>
    </div>
</div>

</body>
</html>
