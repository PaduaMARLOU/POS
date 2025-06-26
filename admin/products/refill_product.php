<?php
require_once '../../db.php';

// Fetch all products
$stmt = $pdo->query("SELECT id, product_code, name, quantity_in_stock FROM products ORDER BY name ASC");
$products = $stmt->fetchAll();

// Handle stock update (add or subtract)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id'], $_POST['action_type'], $_POST['quantity'])) {
    $id = (int)$_POST['product_id'];
    $qty = (int)$_POST['quantity'];
    $action = $_POST['action_type'];

    if ($qty > 0) {
        $stmt = $pdo->prepare("SELECT quantity_in_stock FROM products WHERE id = ?");
        $stmt->execute([$id]);
        $current = (int)$stmt->fetchColumn();

        if ($action === 'add') {
            $newQty = $current + $qty;
        } elseif ($action === 'subtract') {
            $newQty = max(0, $current - $qty); // prevent negative stock
        }

        $stmt = $pdo->prepare("UPDATE products SET quantity_in_stock = ? WHERE id = ?");
        $stmt->execute([$newQty, $id]);

        header("Location: refill_product.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Refill / Adjust Products</title>
  <link rel="stylesheet" href="../css/form.css">
  <link rel="stylesheet" href="../css/content.css">
  <style>
    .search-bar {
      max-width: 400px;
      margin: 1rem auto;
      display: flex;
      justify-content: center;
    }
    .search-bar input {
      width: 100%;
      padding: 0.6rem 1rem;
      border: 1px solid #ccc;
      border-radius: 6px;
    }

    .action-form {
    display: flex;
    gap: 0.5rem;
    align-items: center;
    background: none;
    border: none;
    padding: 0;
    margin: 0;
    box-shadow: none;
    }

    .action-form input[type="number"] {
      width: 70px;
      padding: 5px;
      font-size: 14px;
    }

    .action-form button {
      padding: 5px 10px;
      margin-top: -15px;
      border: none;
      border-radius: 4px;
      font-size: 14px;
      cursor: pointer;
    }

    .action-form .add-btn {
      background-color: #28a745;
      color: white;
    }

    .action-form .sub-btn {
      background-color: #dc3545;
      color: white;
    }

    .action-form button:hover {
      opacity: 0.85;
    }
  </style>
</head>
<body>

<div style="padding: 1rem;">
  <a href="../products.php" style="
    display: inline-block;
    padding: 8px 16px;
    background-color: #6c757d;
    color: white;
    border-radius: 4px;
    text-decoration: none;
    font-weight: bold;
  ">← Back to Products</a>
</div>


<div class="search-bar">
  <input type="text" id="searchInput" placeholder="Search products...">
</div>

<div class="table-wrapper" style="padding: 0 1rem;">
  <table class="table" id="productTable">
    <thead>
      <tr>
        <th>Product Code</th>
        <th>Name</th>
        <th>Current Stock</th>
        <th colspan="2">Actions</th>
      </tr>
    </thead>
    <tbody>
    <?php foreach ($products as $product): ?>
      <tr>
        <td><?= htmlspecialchars($product['product_code']) ?></td>
        <td><?= htmlspecialchars($product['name']) ?></td>
        <td><?= $product['quantity_in_stock'] ?></td>
        <td colspan="2">
        <form method="POST" class="action-form">
            <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
            
            <select name="action_type" required style="width: auto; min-width: 80px; padding: 5px; font-size: 14px;">
            <option value="add">➕ Add</option>
            <option value="subtract">➖ Subtract</option>
            </select>

            <input type="number" name="quantity" min="1" required>
            <button type="submit" class="add-btn">✔</button>
        </form>
        </td>

      </tr>
    <?php endforeach; ?>
    </tbody>
  </table>
</div>

<script>
document.getElementById("searchInput").addEventListener("keyup", function () {
  const query = this.value.toLowerCase();
  const rows = document.querySelectorAll("#productTable tbody tr");

  rows.forEach(row => {
    const text = row.innerText.toLowerCase();
    row.style.display = text.includes(query) ? "" : "none";
  });
});
</script>

</body>
</html>
