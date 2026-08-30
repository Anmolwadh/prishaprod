<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/auth.php';

$orderNumber = trim((string)($_GET['order'] ?? ($_SESSION['last_order_number'] ?? '')));
$order = null;
if ($orderNumber !== '') {
    $stmt = getDB()->prepare('SELECT * FROM orders WHERE order_number = ? LIMIT 1');
    $stmt->execute([$orderNumber]);
    $order = $stmt->fetch() ?: null;
}

if (!$order) {
    flash('error', 'Order not found.');
    redirect('track-order.php');
}

$pageTitle = 'Order Placed Successfully | Prisha Enterprises';
include __DIR__ . '/includes/header.php';
?>
<section class="section-pad">
  <div class="container">
    <div class="auth-card mx-auto" style="max-width:720px">
      <div class="text-center mb-4">
        <div class="brand-mark mx-auto mb-3" style="width:64px;height:64px;font-size:1.4rem"><i class="fa-solid fa-check"></i></div>
        <h1 class="h3">Order Placed Successfully!</h1>
        <p class="text-muted mb-0">Thank you for shopping with Prisha Enterprises.</p>
      </div>
      <div class="row g-3">
        <div class="col-md-6"><strong>Order Number</strong><div><?= e($order['order_number']) ?></div></div>
        <div class="col-md-6"><strong>Customer Name</strong><div><?= e($order['customer_name']) ?></div></div>
        <div class="col-md-6"><strong>Total Amount</strong><div class="text-success fw-bold"><?= e(format_money((float)$order['total'])) ?></div></div>
        <div class="col-md-6"><strong>Payment Method</strong><div><?= e($order['payment_method']) ?> (<?= e($order['payment_status']) ?>)</div></div>
        <div class="col-12">
          <strong>Delivery Address</strong>
          <div><?= e($order['address']) ?>, <?= e($order['city']) ?>, <?= e($order['state']) ?> - <?= e($order['pincode']) ?></div>
        </div>
      </div>
      <div class="d-flex flex-wrap gap-2 mt-4 justify-content-center">
        <a class="btn btn-pe" href="<?= e(url('track-order.php?order=' . urlencode($order['order_number']) . '&phone=' . urlencode($order['phone']))) ?>">Track Order</a>
        <a class="btn btn-outline-success" href="<?= e(url('shop.php')) ?>">Continue Shopping</a>
      </div>
    </div>
  </div>
</section>
<?php include __DIR__ . '/includes/footer.php'; ?>
