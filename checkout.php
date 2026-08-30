<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/auth.php';

if (!cart()) {
    flash('error', 'Your cart is empty.');
    redirect('shop.php');
}

$customer = current_customer();
$preCity = trim((string)($_POST['city'] ?? ($customer['city'] ?? '')));
$preAddress = trim((string)($_POST['address'] ?? ($customer['address'] ?? '')));
$prePincode = trim((string)($_POST['pincode'] ?? ($customer['pincode'] ?? '')));
$totals = cart_totals($preCity, $preAddress, $prePincode);
$shippingCharge = (float)(get_setting('shipping_charge', '60') ?? 60);
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_csrf();

    $name = trim((string)($_POST['customer_name'] ?? ''));
    $phone = trim((string)($_POST['phone'] ?? ''));
    $email = trim((string)($_POST['email'] ?? ''));
    $address = trim((string)($_POST['address'] ?? ''));
    $city = trim((string)($_POST['city'] ?? ''));
    $state = trim((string)($_POST['state'] ?? ''));
    $pincode = trim((string)($_POST['pincode'] ?? ''));
    $landmark = trim((string)($_POST['landmark'] ?? ''));

    if ($name === '') $errors[] = 'Full name is required.';
    if (!validate_phone($phone)) $errors[] = 'Enter a valid 10-digit mobile number.';
    if ($email !== '' && !validate_email($email)) $errors[] = 'Enter a valid email address.';
    if ($address === '') $errors[] = 'Address is required.';
    if ($city === '') $errors[] = 'City is required.';
    if ($state === '') $errors[] = 'State is required.';
    if (!validate_pincode($pincode)) $errors[] = 'Enter a valid 6-digit pincode.';

    if (!$errors) {
        $pdo = getDB();
        try {
            $pdo->beginTransaction();
            $cartItems = cart();
            if (!$cartItems) {
                throw new RuntimeException('Cart is empty.');
            }

            // Re-validate stock and prices
            $orderItems = [];
            $subtotal = 0.0;
            foreach ($cartItems as $item) {
                $stmt = $pdo->prepare("SELECT id, name, sku, price, stock, status FROM products WHERE id = ? FOR UPDATE");
                $stmt->execute([(int)$item['product_id']]);
                $product = $stmt->fetch();
                if (!$product || $product['status'] !== 'Active') {
                    throw new RuntimeException('A product in your cart is no longer available.');
                }
                $qty = (int)$item['qty'];
                if ($qty < 1 || $qty > (int)$product['stock']) {
                    throw new RuntimeException($product['name'] . ' has insufficient stock.');
                }
                $line = round((float)$product['price'] * $qty, 2);
                $subtotal += $line;
                $orderItems[] = [
                    'product_id' => (int)$product['id'],
                    'product_name' => $product['name'],
                    'sku' => $product['sku'],
                    'quantity' => $qty,
                    'price' => (float)$product['price'],
                    'total' => $line,
                ];
            }

            $shipping = shipping_amount($subtotal, $city, $address, $pincode);
            $discount = 0.0;
            $total = round($subtotal + $shipping - $discount, 2);
            $orderNumber = generate_order_number($pdo);

            $ins = $pdo->prepare(
                "INSERT INTO orders (order_number, customer_id, customer_name, email, phone, address, city, state, pincode, landmark,
                 subtotal, shipping, discount, total, payment_method, payment_status, order_status)
                 VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'COD', 'Pending', 'Pending')"
            );
            $ins->execute([
                $orderNumber,
                $customer['id'] ?? null,
                $name,
                $email !== '' ? $email : null,
                $phone,
                $address,
                $city,
                $state,
                $pincode,
                $landmark !== '' ? $landmark : null,
                $subtotal,
                $shipping,
                $discount,
                $total,
            ]);
            $orderId = (int)$pdo->lastInsertId();

            $itemStmt = $pdo->prepare(
                "INSERT INTO order_items (order_id, product_id, product_name, sku, quantity, price, total)
                 VALUES (?, ?, ?, ?, ?, ?, ?)"
            );
            $stockStmt = $pdo->prepare("UPDATE products SET stock = stock - ? WHERE id = ? AND stock >= ?");

            foreach ($orderItems as $oi) {
                $itemStmt->execute([$orderId, $oi['product_id'], $oi['product_name'], $oi['sku'], $oi['quantity'], $oi['price'], $oi['total']]);
                $stockStmt->execute([$oi['quantity'], $oi['product_id'], $oi['quantity']]);
                if ($stockStmt->rowCount() === 0) {
                    throw new RuntimeException('Stock update failed for ' . $oi['product_name']);
                }
            }

            $pdo->commit();
            $_SESSION['cart'] = [];
            $_SESSION['last_order_number'] = $orderNumber;
            redirect('order-success.php?order=' . urlencode($orderNumber));
        } catch (Throwable $e) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }
            error_log($e->getMessage());
            $errors[] = $e->getMessage();
        }
    }
}

$pageTitle = 'Checkout | Prisha Enterprises';
include __DIR__ . '/includes/header.php';
?>
<section class="page-hero">
  <div class="container"><h1 class="mb-0">Checkout</h1></div>
</section>
<section class="section-pad">
  <div class="container">
    <?php if ($errors): ?>
      <div class="alert alert-danger"><ul class="mb-0"><?php foreach ($errors as $err): ?><li><?= e($err) ?></li><?php endforeach; ?></ul></div>
    <?php endif; ?>
    <form method="post" class="row g-4">
      <?= csrf_field() ?>
      <div class="col-lg-7">
        <div class="auth-card">
          <h2 class="h5 mb-3">Customer Information</h2>
          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label">Full Name *</label>
              <input type="text" name="customer_name" class="form-control" required value="<?= e($_POST['customer_name'] ?? ($customer['name'] ?? '')) ?>">
            </div>
            <div class="col-md-6">
              <label class="form-label">Mobile Number *</label>
              <input type="text" name="phone" class="form-control" required maxlength="10" value="<?= e($_POST['phone'] ?? ($customer['phone'] ?? '')) ?>">
            </div>
            <div class="col-12">
              <label class="form-label">Email</label>
              <input type="email" name="email" class="form-control" value="<?= e($_POST['email'] ?? ($customer['email'] ?? '')) ?>">
            </div>
            <div class="col-12">
              <label class="form-label">Address *</label>
              <textarea name="address" id="checkoutAddress" class="form-control" rows="3" required><?= e($_POST['address'] ?? ($customer['address'] ?? '')) ?></textarea>
            </div>
            <div class="col-md-4">
              <label class="form-label">City *</label>
              <input type="text" name="city" id="checkoutCity" class="form-control" required value="<?= e($_POST['city'] ?? ($customer['city'] ?? '')) ?>" placeholder="e.g. Rajpura">
            </div>
            <div class="col-md-4">
              <label class="form-label">State *</label>
              <input type="text" name="state" class="form-control" required value="<?= e($_POST['state'] ?? ($customer['state'] ?? '')) ?>">
            </div>
            <div class="col-md-4">
              <label class="form-label">Pincode *</label>
              <input type="text" name="pincode" id="checkoutPincode" class="form-control" required maxlength="6" value="<?= e($_POST['pincode'] ?? ($customer['pincode'] ?? '')) ?>">
            </div>
            <div class="col-12">
              <label class="form-label">Landmark</label>
              <input type="text" name="landmark" class="form-control" value="<?= e($_POST['landmark'] ?? '') ?>">
            </div>
            <div class="col-12">
              <div class="alert alert-success mb-0 py-2" id="shippingHint">
                <?php if (!empty($totals['is_rajpura'])): ?>
                  Free delivery for Rajpura location.
                <?php else: ?>
                  Delivery charge <?= e(format_money($shippingCharge)) ?> applies outside Rajpura. Enter city as <strong>Rajpura</strong> for free delivery.
                <?php endif; ?>
              </div>
            </div>
          </div>
          <div class="mt-4 p-3 rounded-3 bg-light">
            <strong>Payment Method:</strong> Cash on Delivery (COD)
          </div>
        </div>
      </div>
      <div class="col-lg-5">
        <div class="summary-box">
          <h2 class="h5 mb-3">Order Summary</h2>
          <?php foreach (cart() as $item): ?>
            <div class="d-flex justify-content-between mb-2 small">
              <span><?= e($item['name']) ?> × <?= (int)$item['qty'] ?></span>
              <span><?= e(format_money((float)$item['price'] * (int)$item['qty'])) ?></span>
            </div>
          <?php endforeach; ?>
          <hr>
          <div class="d-flex justify-content-between mb-2"><span>Subtotal</span><strong id="coSubtotal"><?= e(format_money($totals['subtotal'])) ?></strong></div>
          <div class="d-flex justify-content-between mb-2"><span>Shipping</span><strong id="coShipping"><?= e(format_money($totals['shipping'])) ?></strong></div>
          <div class="d-flex justify-content-between mb-3"><span>Total</span><strong class="fs-5 text-success" id="coTotal"><?= e(format_money($totals['total'])) ?></strong></div>
          <button type="submit" class="btn btn-pe w-100">Place Order</button>
        </div>
      </div>
    </form>
  </div>
</section>
<script>
(function () {
  const charge = <?= json_encode($shippingCharge) ?>;
  const subtotal = <?= json_encode((float)$totals['subtotal']) ?>;
  const cityEl = document.getElementById('checkoutCity');
  const addressEl = document.getElementById('checkoutAddress');
  const pinEl = document.getElementById('checkoutPincode');
  const shipEl = document.getElementById('coShipping');
  const totalEl = document.getElementById('coTotal');
  const hintEl = document.getElementById('shippingHint');

  function isRajpura() {
    const text = ((cityEl?.value || '') + ' ' + (addressEl?.value || '') + ' ' + (pinEl?.value || '')).toLowerCase();
    return text.includes('rajpura') || /\b14040\d\b/.test(text);
  }

  function formatMoney(n) {
    return '₹' + Number(n).toFixed(2);
  }

  function refreshShipping() {
    const free = isRajpura();
    const shipping = free ? 0 : (subtotal > 0 ? charge : 0);
    const total = subtotal + shipping;
    if (shipEl) shipEl.textContent = formatMoney(shipping);
    if (totalEl) totalEl.textContent = formatMoney(total);
    if (hintEl) {
      hintEl.className = 'alert ' + (free ? 'alert-success' : 'alert-warning') + ' mb-0 py-2';
      hintEl.innerHTML = free
        ? 'Free delivery for Rajpura location.'
        : 'Delivery charge ' + formatMoney(charge) + ' applies outside Rajpura. Enter city as <strong>Rajpura</strong> for free delivery.';
    }
  }

  ['input', 'change', 'blur'].forEach(function (evt) {
    cityEl?.addEventListener(evt, refreshShipping);
    addressEl?.addEventListener(evt, refreshShipping);
    pinEl?.addEventListener(evt, refreshShipping);
  });
  refreshShipping();
})();
</script>
<?php include __DIR__ . '/includes/footer.php'; ?>
