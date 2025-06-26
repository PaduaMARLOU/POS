<?php
session_start();

// Redirect if not logged in
if (!isset($_SESSION['user_id']) || !isset($_SESSION['account_type'])) {
    header("Location: ../forbidden.php");
    exit();
}

// Check account type
if ($_SESSION['account_type'] === 'admin') {
    header("Location: dashboard.php");
    exit();
} else {
    header("Location: ../forbidden.php");
    exit();
}
