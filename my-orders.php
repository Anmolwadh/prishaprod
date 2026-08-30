<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/auth.php';
require_customer();

$pdo = getDB();
$orderId = (int)($_GET['id'] ?? 0);

if ($orderId > 0) {
    $stmt = $pdo->prepare('SELECT * FROM orders WHERE id = ? AND customer_id = ? LIMIT 1');
    $stmt->execute([$orderId, (int)$_SESSION['customer_id']]);
    $order = $stmt->fetch();
    if (!$order) {
        flash('error', 'Order not found.');
        redirect('my-orders.php');
    }
    $items = $pdo->prepare('SELECT * FROM order_items WHERE order_id = ?');
    $items->execute([$orderId]);
    $orderItems = $items->fetchAll();
    $pageTitle = 'Order ' . $order['order_number'] . ' | Prisha Enterprises';
    include __DIR__ . '/includes/header.php';
    ?>
    <section class="page-hero"><div class="container"><h1 class="h3 mb-0">Order <?= e($order['order_number']) ?></h1></div></section>
    <section class="section-pad"><div class="container">
      <div class="account-card">
        <p><strong>Status:</strong> <?= e($order['order_status']) ?> · <strong>Payment:</strong> <?= e($order['payment_status']) ?> · <strong>Total:</strong> <?= e(format_money((float)$order['total'])) ?></p>
        <div class="table-responsive"><table class="table"><thead><tr><th>Product</th><th>Qty</th><th>Price</th><th>Total</th></tr></thead><tbody>
          <?php foreach ($orderItems as $it): ?>
            <tr><td><?= e($it['product_name']) ?></td><td><?= (int)$it['quantity'] ?></td><td><?= e(format_money((float)$it['price'])) ?></td><td><?= e(format_money((float)$it['total'])) ?></td></tr>
          <?php endforeach; ?>
        </tbody></table></div>
        <a href="<?= e(url('track-order.php?order=' . urlencode($order['order_number']) . '&phone=' . urlencode($order['phone']))) ?>" class="btn btn-pe">Track Order</a>
        <a href="<?= e(url('my-orders.php')) ?>" class="btn btn-outline-secondary">Back</a>
      </div>
    </div></section>
    <?php include __DIR__ . '/includes/footer.php'; exit;
}

$orders = $pdo->prepare('SELECT * FROM orders WHERE customer_id = ? ORDER BY created_at DESC');
$orders->execute([(int)$_SESSION['customer_id']]);
$rows = $orders->fetchAll();

$pageTitle = 'My Orders | Prisha Enterprises';
include __DIR__ . '/includes/header.php';
?>
<section class="page-hero"><div class="container"><h1 class="mb-0">My Orders</h1></div></section>
<section class="section-pad">
  <div class="container">
    <div class="table-responsive bg-white border rounded-4">
      <table class="table mb-0 align-middle">
        <thead><tr><th>Order</th><th>Date</th><th>Total</th><th>Status</th><th>Payment</th><th></th></tr></thead>
        <tbody>
          <?php if (!$rows): ?>
            <tr><td colspan="6" class="text-center py-4">No orders yet. <a href="<?= e(url('shop.php')) ?>">Start shopping</a></td></tr>
          <?php else: foreach ($rows as $o): ?>
            <tr>
              <td><?= e($o['order_number']) ?></td>
              <td><?= e(date('d M Y', strtotime($o['created_at']))) ?></td>
              <td><?= e(format_money((float)$o['total'])) ?></td>
              <td><span class="badge text-bg-secondary"><?= e($o['order_status']) ?></span></td>
              <td><?= e($o['payment_status']) ?></td>
              <td><a href="<?= e(url('my-orders.php?id=' . (int)$o['id'])) ?>" class="btn btn-sm btn-outline-success">View</a></td>
            </tr>
          <?php endforeach; endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</section>
<?php include __DIR__ . '/includes/footer.php'; ?>
