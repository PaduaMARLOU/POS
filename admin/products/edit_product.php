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

// Helper to fetch enum values
function getEnumValues($pdo, $table, $column) {
    $stmt = $pdo->prepare("SHOW COLUMNS FROM `$table` WHERE Field = ?");
    $stmt->execute([$column]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    preg_match("/^enum\((.*)\)$/", $row['Type'], $matches);
    return str_getcsv($matches[1], ',', "'");
}

// Helper to add new enum value if needed
function addEnumValueIfNeeded($pdo, $table, $column, $newValue) {
    if (!$newValue) return;
    $existing = getEnumValues($pdo, $table, $column);
    if (in_array($newValue, $existing)) return;

    $newEnumList = array_merge($existing, [$newValue]);
    $enumString = implode("','", array_map(fn($v) => addslashes($v), $newEnumList));
    $sql = "ALTER TABLE `$table` MODIFY `$column` ENUM('$enumString')";
    $pdo->exec($sql);
}

// Fetch enum options
$categories = getEnumValues($pdo, 'products', 'category');
$brands     = getEnumValues($pdo, 'products', 'brand');
$units      = getEnumValues($pdo, 'products', 'unit');

// Handle update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $category = ($_POST['category'] === '__add_new__') ? trim($_POST['custom_category']) : $_POST['category'];
    $brand    = ($_POST['brand'] === '__add_new__') ? trim($_POST['custom_brand']) : $_POST['brand'];
    $unit     = ($_POST['unit'] === '__add_new__') ? trim($_POST['custom_unit']) : $_POST['unit'];

    // Add new enums if necessary
    addEnumValueIfNeeded($pdo, 'products', 'category', $category);
    addEnumValueIfNeeded($pdo, 'products', 'brand', $brand);
    addEnumValueIfNeeded($pdo, 'products', 'unit', $unit);

    $stmt = $pdo->prepare("UPDATE products SET
        product_code = ?, name = ?, category = ?, brand = ?, 
        cost_price = ?, selling_price = ?, quantity_in_stock = ?, 
        reorder_level = ?, unit = ?, is_active = ?
        WHERE id = ?");

    $stmt->execute([
        $_POST['product_code'],
        $_POST['name'],
        $category,
        $brand,
        $_POST['cost_price'],
        $_POST['selling_price'],
        $_POST['quantity_in_stock'],
        $_POST['reorder_level'],
        $unit,
        isset($_POST['is_active']) ? 1 : 0,
        $id
    ]);

    header("Location: ../products.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
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
            <select name="category" id="category-select" onchange="toggleCustomInput(this, 'custom-category')" required>
                <?php foreach ($categories as $c): ?>
                    <option value="<?= $c ?>" <?= $product['category'] === $c ? 'selected' : '' ?>><?= $c ?></option>
                <?php endforeach; ?>
                <option value="__add_new__">Add New...</option>
            </select>
            <input type="text" name="custom_category" id="custom-category" placeholder="Enter new category" style="display: none;" />

            <label>Brand:</label>
            <select name="brand" id="brand-select" onchange="toggleCustomInput(this, 'custom-brand')" required>
                <?php foreach ($brands as $b): ?>
                    <option value="<?= $b ?>" <?= $product['brand'] === $b ? 'selected' : '' ?>><?= $b ?></option>
                <?php endforeach; ?>
                <option value="__add_new__">Add New...</option>
            </select>
            <input type="text" name="custom_brand" id="custom-brand" placeholder="Enter new brand" style="display: none;" />
        </div>

        <div class="form-col">
            <label>Cost Price:</label>
            <input type="number" step="0.01" name="cost_price" value="<?= $product['cost_price'] ?>">
            
            <label>Selling Price:</label>
            <input type="number" step="0.01" name="selling_price" value="<?= $product['selling_price'] ?>" required>

            <label>Stock Quantity:</label>
            <input type="number" name="quantity_in_stock" value="<?= $product['quantity_in_stock'] ?>">

            <label>Reorder Level:</label>
            <input type="number" name="reorder_level" value="<?= $product['reorder_level'] ?>">

            <label>Unit:</label>
            <select name="unit" id="unit-select" onchange="toggleCustomInput(this, 'custom-unit')" required>
                <?php foreach ($units as $u): ?>
                    <option value="<?= $u ?>" <?= $product['unit'] === $u ? 'selected' : '' ?>><?= $u ?></option>
                <?php endforeach; ?>
                <option value="__add_new__">Add New...</option>
            </select>
            <input type="text" name="custom_unit" id="custom-unit" placeholder="Enter new unit" style="display: none;" />

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

<script>
function toggleCustomInput(selectElement, inputId) {
    const input = document.getElementById(inputId);
    if (selectElement.value === '__add_new__') {
        input.style.display = 'block';
        input.required = true;
    } else {
        input.style.display = 'none';
        input.required = false;
    }
}
</script>

</body>
</html>
