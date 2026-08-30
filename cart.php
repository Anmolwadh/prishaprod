<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/auth.php';

$cartItems = cart();
$totals = cart_totals();
$pageTitle = 'Shopping Cart | Prisha Enterprises';
include __DIR__ . '/includes/header.php';
?>
<section class="page-hero">
  <div class="container"><h1 class="mb-0">Your Cart</h1></div>
</section>
<section class="section-pad">
  <div class="container" id="cartPage">
    <?php if (!$cartItems): ?>
      <div class="alert alert-light border">Your cart is empty. <a href="<?= e(url('shop.php')) ?>">Continue shopping</a></div>
    <?php else: ?>
      <div class="row g-4">
        <div class="col-lg-8">
          <div class="table-responsive bg-white border rounded-4">
            <table class="table align-middle mb-0 cart-table">
              <thead>
                <tr>
                  <th>Product</th>
                  <th>Qty</th>
                  <th></th>
                </tr>
              </thead>
              <tbody id="cartBody">
                <?php foreach ($cartItems as $item): ?>
                  <tr data-cart-row data-product-id="<?= (int)$item['product_id'] ?>">
                    <td>
                      <div class="d-flex align-items-center gap-3">
                        <img src="<?= e(product_image_url($item['image'] ?? null)) ?>" alt="<?= e($item['name']) ?>">
                        <div>
                          <strong><?= e($item['name']) ?></strong>
                          <div class="small text-muted"><?= e($item['sku']) ?></div>
                        </div>
                      </div>
                    </td>
                    <td>
                      <div class="qty-control">
                        <button type="button" class="btn btn-sm btn-outline-secondary" data-cart-qty="-1" data-product-id="<?= (int)$item['product_id'] ?>">-</button>
                        <input class="qty-input" type="number" value="<?= (int)$item['qty'] ?>" readonly>
                        <button type="button" class="btn btn-sm btn-outline-secondary" data-cart-qty="1" data-product-id="<?= (int)$item['product_id'] ?>">+</button>
                      </div>
                    </td>
                    <td><button class="btn btn-sm btn-outline-danger" data-cart-remove data-product-id="<?= (int)$item['product_id'] ?>"><i class="fa-solid fa-trash"></i></button></td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
          <div class="mt-3 d-flex gap-2">
            <a href="<?= e(url('shop.php')) ?>" class="btn btn-outline-secondary">Continue Shopping</a>
            <button type="button" class="btn btn-outline-danger" id="clearCartBtn">Clear Cart</button>
          </div>
        </div>
        <div class="col-lg-4">
          <div class="summary-box">
            <h2 class="h5 mb-3">Order Summary</h2>
            <div class="d-flex justify-content-between mb-3"><span>Total Amount</span><strong class="fs-5 text-success" id="sumTotal"><?= e(format_money((float)$totals['subtotal'] + (float)$totals['tax'])) ?></strong></div>
            <a href="<?= e(url('checkout.php')) ?>" class="btn btn-pe w-100">Proceed to Checkout</a>
          </div>
        </div>
      </div>
    <?php endif; ?>
  </div>
</section>
<script>
document.getElementById('clearCartBtn')?.addEventListener('click', async function () {
  if (!confirm('Clear all items from cart?')) return;
  try {
    const data = await PECartRequest({ action: 'clear' });
    PEUpdateCartCount(0);
    location.reload();
  } catch (e) { PEToast(e.message, 'error'); }
});
window.refreshCartUI = function (data) {
  location.reload();
};
</script>
<?php include __DIR__ . '/includes/footer.php'; ?>
