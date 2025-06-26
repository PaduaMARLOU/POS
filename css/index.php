<?php
session_start();

if (!isset($_SESSION['account_type']) || $_SESSION['account_type'] !== 'admin') {
    header("Location: ../forbidden.php");
    exit();
}

// If admin, show content or redirect as needed
header("Location: ../login.php");
exit();
