<?php
require_once '../../db.php';

// Fetch ENUM values dynamically for dropdowns
function getEnumValues($pdo, $table, $column) {
    $stmt = $pdo->query("SHOW COLUMNS FROM `$table` WHERE Field = '$column'");
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    preg_match_all("/'(.*?)'/", $row['Type'], $matches);
    return $matches[1];
}

$categories = getEnumValues($pdo, 'products', 'category');
$brands = getEnumValues($pdo, 'products', 'brand');
$units = getEnumValues($pdo, 'products', 'unit');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $pdo->prepare("INSERT INTO products 
        (product_code, name, category, brand, cost_price, selling_price, quantity_in_stock, unit) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([
        $_POST['product_code'],
        $_POST['name'],
        $_POST['category'],
        $_POST['brand'],
        $_POST['cost_price'],
        $_POST['selling_price'],
        $_POST['quantity_in_stock'],
        $_POST['unit']
    ]);
    header("Location: ../products.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Product</title>
    <link rel="stylesheet" href="../css/form.css">
</head>
<body>
<form method="POST">
    <h2>Add Product</h2>
    <div class="form-grid">
        <div class="form-col">
            <label>Product Code:</label>
            <input type="text" name="product_code" required>

            <label>Name:</label>
            <input type="text" name="name" required>

            <label>Category:</label>
            <select name="category">
                <?php foreach ($categories as $cat): ?>
                    <option value="<?= htmlspecialchars($cat) ?>"><?= htmlspecialchars($cat) ?></option>
                <?php endforeach; ?>
            </select>

            <label>Brand:</label>
            <select name="brand">
                <?php foreach ($brands as $brand): ?>
                    <option value="<?= htmlspecialchars($brand) ?>"><?= htmlspecialchars($brand) ?></option>
                <?php endforeach; ?>
            </select>

            <label>Cost Price:</label>
            <input type="number" step="0.01" name="cost_price">
        </div>

        <div class="form-col">
            <label>Selling Price:</label>
            <input type="number" step="0.01" name="selling_price" required>

            <label>Stock Quantity:</label>
            <input type="number" name="quantity_in_stock">

            <label>Unit:</label>
            <select name="unit">
                <?php foreach ($units as $unit): ?>
                    <option value="<?= htmlspecialchars($unit) ?>"><?= htmlspecialchars($unit) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>

    <div class="form-footer">
        <button type="submit">Save</button>
        <a href="../products.php">Cancel</a>
    </div>
</form>
</body>
</html>