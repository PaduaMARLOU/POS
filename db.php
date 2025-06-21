<?php
// db.php
$pdo = new PDO('mysql:host=localhost;dbname=pos_db', 'root', '');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
// Ensure the database connection is established
if (!$pdo) {
    die("Database connection failed: " . $pdo->errorInfo());
}