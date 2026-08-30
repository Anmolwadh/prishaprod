<?php
declare(strict_types=1);
/** @var array $product */
$displayPrice = product_price_incl_gst($product);
$mrp = (float)$product['mrp'];
$discount = (float)$product['discount'];
if ($discount <= 0) {
    $discount = calc_discount($mrp, (float)$product['price']);
}
?>
<div class="product-card">
  <div class="img-wrap">
    <?php if ($discount > 0 && $mrp > $displayPrice): ?>
      <span class="discount-badge"><?= e(rtrim(rtrim(number_format($discount, 2), '0'), '.')) ?>% OFF</span>
    <?php endif; ?>
    <a href="<?= e(url('product.php?id=' . (int)$product['id'])) ?>">
      <img src="<?= e(product_image_url($product['image'] ?? null)) ?>" alt="<?= e($product['name']) ?>" loading="lazy">
    </a>
  </div>
  <div class="body">
    <h3><a href="<?= e(url('product.php?id=' . (int)$product['id'])) ?>"><?= e($product['name']) ?></a></h3>
    <?php if (!empty($product['short_description'])): ?>
      <p class="short"><?= e($product['short_description']) ?></p>
    <?php endif; ?>
    <div class="price-row">
      <span class="price"><?= e(format_money($displayPrice)) ?></span>
      <?php if ($mrp > $displayPrice): ?>
        <span class="mrp"><?= e(format_money($mrp)) ?></span>
      <?php endif; ?>
    </div>
    <?php if (!empty($product['pack_size'])): ?>
      <div class="pack-size"><i class="fa-solid fa-box-open me-1"></i><?= e($product['pack_size']) ?></div>
    <?php endif; ?>
    <div class="product-actions">
      <button type="button" class="btn btn-outline-success" data-add-to-cart data-product-id="<?= (int)$product['id'] ?>" <?= (int)$product['stock'] <= 0 ? 'disabled' : '' ?>>
        <i class="fa-solid fa-cart-plus me-1"></i>Add
      </button>
      <button type="button" class="btn btn-success" data-buy-now data-product-id="<?= (int)$product['id'] ?>" <?= (int)$product['stock'] <= 0 ? 'disabled' : '' ?>>
        Buy Now
      </button>
    </div>
  </div>
</div>
