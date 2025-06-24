<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}

require '../db.php';

$page = $_GET['page'] ?? 'users';
$allowedPages = ['users', 'configs'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Control Panel</title>
    <link rel="stylesheet" href="css/nav.css">
    <link rel="stylesheet" href="css/content.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>

    <?php include 'nav.php'; ?>

    <div class="content">
        <h2>Control Panel</h2>
        <p>This is the control panel. Use the buttons below to manage different settings.</p>

        <div class="tab-buttons">
            <a href="?page=users" class="<?= $page === 'users' ? 'active' : '' ?>">Users</a>
            <a href="?page=configs" class="<?= $page === 'configs' ? 'active' : '' ?>">Configs</a>
            <!-- Add more tabs here -->
        </div>

        <?php
        $fileToInclude = "control_panel/{$page}.php";
        if (in_array($page, $allowedPages) && file_exists($fileToInclude)) {
            include $fileToInclude;
        } else {
            echo "<p><strong>Invalid section.</strong></p>";
        }
        ?>
    </div>

</body>
</html>
