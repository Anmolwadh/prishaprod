<?php
declare(strict_types=1);
require_once __DIR__ . '/../includes/auth.php';
require_admin();

$id = (int)($_GET['id'] ?? 0);
$pdo = getDB();
$stmt = $pdo->prepare('SELECT * FROM orders WHERE id = ?');
$stmt->execute([$id]);
$order = $stmt->fetch();
if (!$order) {
    exit('Order not found');
}
$items = $pdo->prepare('SELECT * FROM order_items WHERE order_id = ?');
$items->execute([$id]);
$orderItems = $items->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Invoice <?= e($order['order_number']) ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body { background:#fff; color:#111; }
    .invoice { max-width: 900px; margin: 24px auto; }
    @media print { .no-print { display:none !important; } }
  </style>
</head>
<body>
  <div class="invoice">
    <div class="d-flex justify-content-between align-items-start mb-4">
      <div>
        <h1 class="h3 mb-1"><?= e(get_setting('business_name', 'Prisha Enterprises')) ?></h1>
        <div><?= e(get_setting('address', '')) ?></div>
        <div>Phone: <?= e(get_setting('phone', '')) ?> | Email: <?= e(get_setting('email', '')) ?></div>
      </div>
      <div class="text-end">
        <h2 class="h4">Invoice</h2>
        <div><strong><?= e($order['order_number']) ?></strong></div>
        <div><?= e(date('d M Y', strtotime($order['created_at']))) ?></div>
      </div>
    </div>
    <div class="row mb-4">
      <div class="col-6">
        <h3 class="h6">Bill To</h3>
        <div><?= e($order['customer_name']) ?></div>
        <div><?= e($order['phone']) ?></div>
        <div><?= e((string)$order['email']) ?></div>
      </div>
      <div class="col-6">
        <h3 class="h6">Ship To</h3>
        <div><?= e($order['address']) ?></div>
        <div><?= e($order['city']) ?>, <?= e($order['state']) ?> - <?= e($order['pincode']) ?></div>
      </div>
    </div>
    <table class="table table-bordered">
      <thead><tr><th>Product</th><th>SKU</th><th>Qty</th><th>Price</th><th>Total</th></tr></thead>
      <tbody>
        <?php foreach ($orderItems as $it): ?>
          <tr>
            <td><?= e($it['product_name']) ?></td>
            <td><?= e($it['sku']) ?></td>
            <td><?= (int)$it['quantity'] ?></td>
            <td><?= e(format_money((float)$it['price'])) ?></td>
            <td><?= e(format_money((float)$it['total'])) ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
    <div class="text-end">
      <div>Subtotal: <?= e(format_money((float)$order['subtotal'])) ?></div>
      <div>Shipping: <?= e(format_money((float)$order['shipping'])) ?></div>
      <div>GST: <?= e(format_money((float)($order['tax'] ?? 0))) ?></div>
      <div class="fw-bold">Total: <?= e(format_money((float)$order['total'])) ?></div>
      <div class="mt-2">Payment: <?= e($order['payment_method']) ?> (<?= e($order['payment_status']) ?>)</div>
      <div>Order Status: <?= e($order['order_status']) ?></div>
    </div>
    <div class="no-print mt-4">
      <button class="btn btn-success" onclick="window.print()">Print</button>
      <a class="btn btn-outline-secondary" href="<?= e(url('admin/order-details.php?id=' . $id)) ?>">Back</a>
    </div>
  </div>
</body>
</html>
