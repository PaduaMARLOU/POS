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
    <title>Dashboard</title>
</head>
<body>

<div class="container">
    <?php include 'sidebar.php'; ?>

    <div id="main-content">
        <h2>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h2>
        <p>You are logged in.</p>
        <a href="logout.php">Logout</a>
    </div>
</div>

</body>
</html>
