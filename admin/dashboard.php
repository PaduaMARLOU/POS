<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- important for mobile -->
    <title>Dashboard</title>
    <link rel="stylesheet" href="css/nav.css">
    <link rel="stylesheet" href="css/content.css">
    <!-- Optional: Add icons from FontAwesome or Lucide -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>

    <?php include 'nav.php'; ?>

    <div class="content">
        <h2>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h2>
        <p>You are logged in.</p>
        <!-- Optional action -->
        <a href="products.php" class="button"><i class="fas fa-box"></i> Go to Products</a>
    </div>

</body>
</html>
