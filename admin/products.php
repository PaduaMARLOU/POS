<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}

require_once '../db.php';
$stmt = $pdo->query("SELECT * FROM products");
$products = $stmt->fetchAll();
$categories = array_unique(array_column($products, 'category'));
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Products</title>
  <link rel="stylesheet" href="css/nav.css">
  <link rel="stylesheet" href="css/content.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
</head>
<body>

<?php include 'nav.php'; ?>

<div class="content">
  <h2>Product Management</h2>
  <p>Manage product inventory. Add, search, or filter your products.</p>

  <div class="tab-buttons">
    <a href="products/add_product.php" class="button">+ Add Product</a>
    <a href="products/refill_product.php" class="button">+ Refill Product</a>

    <input type="text" id="searchInput" placeholder="Search product..." style="padding: 10px; width: 200px;" onkeyup="filterTable()">
    <select id="categoryFilter" onchange="filterTable()" style="padding: 10px;">
      <option value="">All Categories</option>
      <?php foreach ($categories as $cat): ?>
        <option value="<?= htmlspecialchars($cat) ?>"><?= htmlspecialchars($cat) ?></option>
      <?php endforeach; ?>
    </select>
  </div>

  <div class="table-wrapper">
    <table class="table" id="productTable">
      <thead>
        <tr>
          <th>Code</th><th>Name</th><th>Category</th><th>Stock</th><th>Price</th><th>Active</th><th>Actions</th>
        </tr>
      </thead>
      <tbody id="tableBody">
        <?php foreach ($products as $product): ?>
          <tr>
            <td><?= $product['product_code'] ?></td>
            <td><?= $product['name'] ?></td>
            <td><?= $product['category'] ?></td>
            <td><?= $product['quantity_in_stock'] ?></td>
            <td>₱<?= number_format($product['selling_price'], 2) ?></td>
            <td class="<?= $product['is_active'] ? 'status-active' : 'status-inactive' ?>">
              <?= $product['is_active'] ? 'Yes' : 'No' ?>
            </td>
            <td class="actions">
              <a href="products/edit_product.php?id=<?= $product['id'] ?>"><i class="fa fa-edit"></i></a>
              <a href="products/delete_product.php?id=<?= $product['id'] ?>" onclick="return confirm('Are you sure?')"><i class="fa fa-trash"></i></a>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>

  <!-- Pagination -->
  <div style="text-align:center; margin-top:20px;">
    <button onclick="prevPage()">Prev</button>
    <span id="pageNum">1</span>
    <button onclick="nextPage()">Next</button>
  </div>
</div>

<script>
let currentPage = 1;
const rowsPerPage = 5;

function filterTable() {
  const keyword = document.getElementById('searchInput').value.toLowerCase();
  const category = document.getElementById('categoryFilter').value.toLowerCase();
  const rows = document.querySelectorAll('#tableBody tr');
  rows.forEach(row => {
    const cells = row.getElementsByTagName('td');
    const matchesKeyword = [...cells].some(td => td.innerText.toLowerCase().includes(keyword));
    const matchesCategory = category === "" || cells[2].innerText.toLowerCase() === category;
    row.style.display = matchesKeyword && matchesCategory ? "" : "none";
  });
  currentPage = 1;
  paginateTable();
}

function paginateTable() {
  const rows = [...document.querySelectorAll('#tableBody tr')].filter(row => row.style.display !== 'none');
  const start = (currentPage - 1) * rowsPerPage;
  const end = start + rowsPerPage;
  rows.forEach((row, index) => {
    row.style.display = (index >= start && index < end) ? "" : "none";
  });
  document.getElementById('pageNum').innerText = currentPage;
}

function nextPage() {
  const rows = [...document.querySelectorAll('#tableBody tr')].filter(row => row.style.display !== 'none');
  if ((currentPage * rowsPerPage) < rows.length) {
    currentPage++;
    paginateTable();
  }
}

function prevPage() {
  if (currentPage > 1) {
    currentPage--;
    paginateTable();
  }
}

// Initial paginate
window.onload = () => {
  filterTable();
};
</script>

</body>
</html>
