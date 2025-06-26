<?php
require_once '../../db.php';

// Function to get ENUM values from a column
function getEnumValues($pdo, $table, $column) {
    $stmt = $pdo->query("SHOW COLUMNS FROM `$table` WHERE Field = '$column'");
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    preg_match_all("/'(.*?)'/", $row['Type'], $matches);
    return $matches[1];
}

// Function to add new ENUM value if needed
function addEnumValueIfNeeded($pdo, $table, $column, $newValue) {
    if (!$newValue) return;

    $existing = getEnumValues($pdo, $table, $column);
    if (in_array($newValue, $existing)) return;

    $newEnumList = array_merge($existing, [$newValue]);
    $enumString = implode("','", array_map(fn($v) => addslashes($v), $newEnumList));
    $sql = "ALTER TABLE `$table` MODIFY `$column` ENUM('$enumString')";
    $pdo->exec($sql);
}

// Fetch current ENUM values
$categories = getEnumValues($pdo, 'products', 'category');
$brands = getEnumValues($pdo, 'products', 'brand');
$units = getEnumValues($pdo, 'products', 'unit');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle "Add New..." custom entries
    $category = ($_POST['category'] === '__add_new__') ? trim($_POST['custom_category']) : $_POST['category'];
    $brand = ($_POST['brand'] === '__add_new__') ? trim($_POST['custom_brand']) : $_POST['brand'];
    $unit = ($_POST['unit'] === '__add_new__') ? trim($_POST['custom_unit']) : $_POST['unit'];

    // Add to ENUM if new
    addEnumValueIfNeeded($pdo, 'products', 'category', $category);
    addEnumValueIfNeeded($pdo, 'products', 'brand', $brand);
    addEnumValueIfNeeded($pdo, 'products', 'unit', $unit);

    // Insert product
    $stmt = $pdo->prepare("INSERT INTO products 
        (product_code, name, category, brand, cost_price, selling_price, quantity_in_stock, unit) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([
        $_POST['product_code'],
        $_POST['name'],
        $category,
        $brand,
        $_POST['cost_price'],
        $_POST['selling_price'],
        $_POST['quantity_in_stock'],
        $unit
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
            <select name="category" id="category-select" onchange="toggleCustomInput(this, 'custom-category')">
                <?php foreach ($categories as $cat): ?>
                    <option value="<?= htmlspecialchars($cat) ?>"><?= htmlspecialchars($cat) ?></option>
                <?php endforeach; ?>
                <option value="__add_new__">Add New...</option>
            </select>
            <input type="text" name="custom_category" id="custom-category" placeholder="Enter new category" style="display: none;" />

            <label>Brand:</label>
            <select name="brand" id="brand-select" onchange="toggleCustomInput(this, 'custom-brand')">
                <?php foreach ($brands as $brand): ?>
                    <option value="<?= htmlspecialchars($brand) ?>"><?= htmlspecialchars($brand) ?></option>
                <?php endforeach; ?>
                <option value="__add_new__">Add New...</option>
            </select>
            <input type="text" name="custom_brand" id="custom-brand" placeholder="Enter new brand" style="display: none;" />
        </div>

        <div class="form-col">
            <label>Cost Price:</label>
            <input type="number" step="0.01" name="cost_price">

            <label>Selling Price:</label>
            <input type="number" step="0.01" name="selling_price" required>

            <label>Stock Quantity:</label>
            <input type="number" name="quantity_in_stock">

            <label>Unit:</label>
            <select name="unit" id="unit-select" onchange="toggleCustomInput(this, 'custom-unit')">
                <?php foreach ($units as $unit): ?>
                    <option value="<?= htmlspecialchars($unit) ?>"><?= htmlspecialchars($unit) ?></option>
                <?php endforeach; ?>
                <option value="__add_new__">Add New...</option>
            </select>
            <input type="text" name="custom_unit" id="custom-unit" placeholder="Enter new unit" style="display: none;" />
        </div>
    </div>

    <div class="form-footer">
        <button type="submit">Save</button>
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
