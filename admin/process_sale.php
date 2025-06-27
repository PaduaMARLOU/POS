<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
  echo json_encode(['success' => false, 'message' => 'Unauthorized']);
  exit;
}

require_once '../db.php';

$data = json_decode(file_get_contents("php://input"), true);
$cart = $data['cart'] ?? [];
$payment_method = $data['payment_method'] ?? 'cash';
$amount_paid = floatval($data['amount_paid']);

if (empty($cart)) {
  echo json_encode(['success' => false, 'message' => 'Empty cart']);
  exit;
}

try {
  $pdo->beginTransaction();

  // Calculate total
  $total = 0;
  foreach ($cart as $item) {
    $total += $item['qty'] * $item['price'];
  }

  $change = $amount_paid - $total;

  // Generate unique sale number
  $saleNumber = 'S-' . date('Ymd-His');

  // Insert sale
  $stmt = $pdo->prepare("INSERT INTO sales (sale_number, cashier_id, total_amount, total_items, payment_method, amount_paid, change_given, is_credit) 
    VALUES (?, ?, ?, ?, ?, ?, ?, 0)");
  $stmt->execute([
    $saleNumber,
    $_SESSION['user_id'],
    $total,
    array_sum(array_column($cart, 'qty')),
    $payment_method,
    $amount_paid,
    $change
  ]);

  $saleId = $pdo->lastInsertId();

  // Insert sale_items and update product stock
  $insertItem = $pdo->prepare("INSERT INTO sale_items (sale_id, product_id, quantity, unit_price, total_price) VALUES (?, ?, ?, ?, ?)");
  $updateStock = $pdo->prepare("UPDATE products SET quantity_in_stock = quantity_in_stock - ? WHERE id = ?");

  foreach ($cart as $item) {
    $itemTotal = $item['qty'] * $item['price'];

    $insertItem->execute([$saleId, $item['id'], $item['qty'], $item['price'], $itemTotal]);
    $updateStock->execute([$item['qty'], $item['id']]);
  }

  $pdo->commit();

  echo json_encode([
    'success' => true,
    'change' => number_format($change, 2),
    'receipt' => [
        'sale_number' => $saleNumber,
        'items' => $cart,
        'total' => number_format($total, 2),
        'amount_paid' => number_format($amount_paid, 2),
        'change' => number_format($change, 2),
        'payment_method' => $payment_method,
        'datetime' => date("Y-m-d H:i:s")
    ]
    ]);

} catch (Exception $e) {
  $pdo->rollBack();
  echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
