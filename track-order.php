<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/auth.php';

$order = null;
$items = [];
$error = null;
$orderNumber = trim((string)($_GET['order'] ?? $_POST['order_number'] ?? ''));
$phone = trim((string)($_GET['phone'] ?? $_POST['phone'] ?? ''));

if (($_SERVER['REQUEST_METHOD'] === 'POST') || ($orderNumber && $phone)) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        require_csrf();
    }
    if ($orderNumber === '' || $phone === '') {
        $error = 'Please enter order number and mobile number.';
    } else {
        $stmt = getDB()->prepare('SELECT * FROM orders WHERE order_number = ? AND phone = ? LIMIT 1');
        $stmt->execute([$orderNumber, $phone]);
        $order = $stmt->fetch() ?: null;
        if (!$order) {
            $error = 'No order found with the given details.';
        } else {
            $itemStmt = getDB()->prepare('SELECT * FROM order_items WHERE order_id = ?');
            $itemStmt->execute([(int)$order['id']]);
            $items = $itemStmt->fetchAll();
        }
    }
}

$steps = ['Pending', 'Confirmed', 'Processing', 'Shipped', 'Out for Delivery', 'Delivered'];
$statusMap = order_status_steps();
$currentStep = $order ? ($statusMap[$order['order_status']] ?? 0) : 0;

$pageTitle = 'Track Order | Prisha Enterprises';
include __DIR__ . '/includes/header.php';
?>
<section class="page-hero">
  <div class="container">
    <h1 class="mb-1">Track Order</h1>
    <p class="mb-0 text-muted">Enter your order number and registered mobile number.</p>
  </div>
</section>
<section class="section-pad">
  <div class="container" style="max-width:900px">
    <form method="post" class="auth-card mb-4">
      <?= csrf_field() ?>
      <div class="row g-3">
        <div class="col-md-5">
          <label class="form-label">Order Number</label>
          <input type="text" name="order_number" class="form-control" required value="<?= e($orderNumber) ?>" placeholder="PE202608090001">
        </div>
        <div class="col-md-5">
          <label class="form-label">Mobile Number</label>
          <input type="text" name="phone" class="form-control" required maxlength="10" value="<?= e($phone) ?>">
        </div>
        <div class="col-md-2 d-flex align-items-end">
          <button class="btn btn-pe w-100" type="submit">Track</button>
        </div>
      </div>
    </form>

    <?php if ($error): ?>
      <div class="alert alert-danger"><?= e($error) ?></div>
    <?php endif; ?>

    <?php if ($order): ?>
      <div class="auth-card">
        <div class="row g-3 mb-4">
          <div class="col-md-4"><strong>Order Number</strong><div><?= e($order['order_number']) ?></div></div>
          <div class="col-md-4"><strong>Order Date</strong><div><?= e(date('d M Y, h:i A', strtotime($order['created_at']))) ?></div></div>
          <div class="col-md-4"><strong>Current Status</strong><div class="fw-bold text-success"><?= e($order['order_status']) ?></div></div>
          <div class="col-md-4"><strong>Payment Status</strong><div><?= e($order['payment_status']) ?></div></div>
          <div class="col-md-4"><strong>Total Amount</strong><div><?= e(format_money((float)$order['total'])) ?></div></div>
        </div>

        <?php if ($order['order_status'] === 'Cancelled'): ?>
          <ul class="timeline">
            <li class="cancelled"><span class="dot"></span><strong>Cancelled</strong><div class="text-muted small">This order has been cancelled.</div></li>
          </ul>
        <?php else: ?>
          <ul class="timeline">
            <?php foreach ($steps as $i => $step):
              $stepNum = $i + 1;
              $class = $stepNum < $currentStep ? 'done' : ($stepNum === $currentStep ? 'current' : '');
            ?>
              <li class="<?= e($class) ?>">
                <span class="dot"></span>
                <strong><?= e($step === 'Pending' ? 'Order Placed' : $step) ?></strong>
              </li>
            <?php endforeach; ?>
          </ul>
        <?php endif; ?>

        <h2 class="h5 mt-4">Products</h2>
        <div class="table-responsive">
          <table class="table">
            <thead><tr><th>Product</th><th>Qty</th></tr></thead>
            <tbody>
              <?php foreach ($items as $it): ?>
                <tr>
                  <td><?= e($it['product_name']) ?></td>
                  <td><?= (int)$it['quantity'] ?></td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
    <?php endif; ?>
  </div>
</section>
<?php include __DIR__ . '/includes/footer.php'; ?>
