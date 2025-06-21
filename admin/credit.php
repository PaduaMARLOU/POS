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
    <title>Credit</title>
</head>
<body>

<div class="container">
    <?php include 'sidebar.php'; ?>

    <div id="main-content">
        <h2>Credit Page</h2>
        <p>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>! Here are your credit details.</p>
        <a href="logout.php">Logout</a>
    </div>
</div>

</body>
</html>
