<?php
require_once '../../db.php';

if (!isset($_GET['id'])) {
    header("Location: products.php");
    exit;
}

$id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
$stmt->execute([$id]);
$product = $stmt->fetch();

if (!$product) {
    echo "Product not found.";
    exit;
}

// Handle update submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $pdo->prepare("UPDATE products SET
        product_code = ?, name = ?, category = ?, brand = ?, 
        cost_price = ?, selling_price = ?, quantity_in_stock = ?, 
        reorder_level = ?, unit = ?, is_active = ?
        WHERE id = ?");

    $stmt->execute([
        $_POST['product_code'],
        $_POST['name'],
        $_POST['category'],
        $_POST['brand'],
        $_POST['cost_price'],
        $_POST['selling_price'],
        $_POST['quantity_in_stock'],
        $_POST['reorder_level'],
        $_POST['unit'],
        isset($_POST['is_active']) ? 1 : 0,
        $id
    ]);

    header("Location: ../products.php");
    exit;
}

// Helper to fetch enum values from a table column
function getEnumValues($pdo, $table, $column) {
    $query = "SHOW COLUMNS FROM `$table` WHERE Field = ?";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$column]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$row || stripos($row['Type'], 'enum') === false) return [];

    preg_match("/^enum\((.*)\)$/", $row['Type'], $matches);
    $enum = str_getcsv($matches[1], ',', "'");
    return $enum;
}

// Fetch enum options
$categories = getEnumValues($pdo, 'products', 'category');
$brands     = getEnumValues($pdo, 'products', 'brand');
$units      = getEnumValues($pdo, 'products', 'unit');
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Product</title>
    <link rel="stylesheet" href="../css/form.css">
</head>
<body>

    <form method="POST">
        <h2>Edit Product</h2>
        <div class="form-grid">
            <div class="form-col">
                <label>Product Code:</label>
                <input type="text" name="product_code" value="<?= htmlspecialchars($product['product_code']) ?>" required>

                <label>Name:</label>
                <input type="text" name="name" value="<?= htmlspecialchars($product['name']) ?>" required>

                <label>Category:</label>
                <select name="category" required>
                    <?php foreach ($categories as $c): ?>
                        <option value="<?= $c ?>" <?= $product['category'] === $c ? 'selected' : '' ?>><?= $c ?></option>
                    <?php endforeach; ?>
                </select>

                <label>Brand:</label>
                <select name="brand" required>
                    <?php foreach ($brands as $b): ?>
                        <option value="<?= $b ?>" <?= $product['brand'] === $b ? 'selected' : '' ?>><?= $b ?></option>
                    <?php endforeach; ?>
                </select>

                <label>Cost Price:</label>
                <input type="number" step="0.01" name="cost_price" value="<?= $product['cost_price'] ?>">
            </div>

            <div class="form-col">
                <label>Selling Price:</label>
                <input type="number" step="0.01" name="selling_price" value="<?= $product['selling_price'] ?>" required>

                <label>Stock Quantity:</label>
                <input type="number" name="quantity_in_stock" value="<?= $product['quantity_in_stock'] ?>">

                <label>Reorder Level:</label>
                <input type="number" name="reorder_level" value="<?= $product['reorder_level'] ?>">

                <label>Unit:</label>
                <select name="unit" required>
                    <?php foreach ($units as $u): ?>
                        <option value="<?= $u ?>" <?= $product['unit'] === $u ? 'selected' : '' ?>><?= $u ?></option>
                    <?php endforeach; ?>
                </select>

                <label>
                    <input type="checkbox" name="is_active" <?= $product['is_active'] ? 'checked' : '' ?>> Active
                </label>
            </div>
        </div>

        <div class="form-footer">
            <button type="submit">Update</button>
            <a href="../products.php">Cancel</a>
        </div>
    </form>

</body>
</html>
