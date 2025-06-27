<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}

require_once '../db.php';

// Fetch active products only
$stmt = $pdo->query("SELECT * FROM products WHERE is_active = 1 ORDER BY name ASC");
$products = $stmt->fetchAll();
$categories = array_unique(array_column($products, 'category'));
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Cashier</title>
  <link rel="stylesheet" href="css/nav.css">
  <link rel="stylesheet" href="css/content.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
</head>
<body>

<?php include 'nav.php'; ?>

<div class="content">
  <h2>Cashier Panel</h2>
  <p>Sell products, add to cart, and process payments.</p>

  <!-- Search and category filter -->
  <div class="tab-buttons">
    <input type="text" id="searchInput" placeholder="Search product..." style="padding: 10px; width: 200px;" onkeyup="filterProducts()">
    <select id="categoryFilter" onchange="filterProducts()" style="padding: 10px;">
      <option value="">All Categories</option>
      <?php foreach ($categories as $cat): ?>
        <option value="<?= htmlspecialchars($cat) ?>"><?= htmlspecialchars($cat) ?></option>
      <?php endforeach; ?>
    </select>
  </div>

  <!-- Product List -->
  <div class="table-wrapper">
    <table class="table" id="productTable">
      <thead>
        <tr>
          <th>Name</th><th>Price</th><th>Stock</th><th>Add</th>
        </tr>
      </thead>
      <tbody id="tableBody">
        <?php foreach ($products as $product): ?>
          <tr data-id="<?= $product['id'] ?>" data-category="<?= $product['category'] ?>">
            <td><?= htmlspecialchars($product['name']) ?></td>
            <td>₱<?= number_format($product['selling_price'], 2) ?></td>
            <td class="stock"><?= $product['quantity_in_stock'] ?></td>
            <td>
              <button onclick="addToCart(<?= $product['id'] ?>, '<?= htmlspecialchars(addslashes($product['name'])) ?>', <?= $product['selling_price'] ?>)">
                <i class="fa fa-cart-plus"></i>
              </button>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>

  <!-- Cart Display -->
  <div style="margin-top: 30px;">
    <h3>Cart</h3>
    <table class="table" id="cartTable">
      <thead>
        <tr><th>Item</th><th>Qty</th><th>Price</th><th>Total</th><th>Remove</th></tr>
      </thead>
      <tbody id="cartBody"></tbody>
    </table>
    <h3>Total: ₱<span id="totalAmount">0.00</span></h3>
    <button onclick="checkout()">Checkout</button>
  </div>
</div>

<script src="js/cashier.js"></script>


</body>
</html>
