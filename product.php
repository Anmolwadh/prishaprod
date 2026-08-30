<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/auth.php';

$id = (int)($_GET['id'] ?? 0);
$pdo = getDB();
$stmt = $pdo->prepare(
    "SELECT p.*, c.name AS category_name, c.slug AS category_slug
     FROM products p JOIN categories c ON c.id = p.category_id
     WHERE p.id = ? AND p.status = 'Active' LIMIT 1"
);
$stmt->execute([$id]);
$product = $stmt->fetch();
if (!$product) {
    http_response_code(404);
    include __DIR__ . '/includes/error-404.php';
    exit;
}

$discount = (float)$product['discount'];
if ($discount <= 0) {
    $discount = calc_discount((float)$product['mrp'], (float)$product['price']);
}
$stockInfo = stock_label((int)$product['stock']);

$rel = $pdo->prepare(
    "SELECT * FROM products WHERE status = 'Active' AND category_id = ? AND id <> ? ORDER BY id DESC LIMIT 4"
);
$rel->execute([(int)$product['category_id'], $id]);
$related = $rel->fetchAll();

$pageTitle = $product['name'] . ' | Prisha Enterprises';
$metaDescription = (string)($product['short_description'] ?: $product['description']);
include __DIR__ . '/includes/header.php';
?>
<section class="section-pad">
  <div class="container">
    <nav aria-label="breadcrumb" class="mb-3">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?= e(url('index.php')) ?>">Home</a></li>
        <li class="breadcrumb-item"><a href="<?= e(url('shop.php')) ?>">Shop</a></li>
        <li class="breadcrumb-item"><a href="<?= e(url('shop.php?category=' . urlencode($product['category_slug']))) ?>"><?= e($product['category_name']) ?></a></li>
        <li class="breadcrumb-item active"><?= e($product['name']) ?></li>
      </ol>
    </nav>
    <div class="row g-4">
      <div class="col-lg-6">
        <div class="bg-white border rounded-4 p-3 text-center">
          <img class="img-fluid rounded-3" src="<?= e(product_image_url($product['image'])) ?>" alt="<?= e($product['name']) ?>">
        </div>
      </div>
      <div class="col-lg-6">
        <h1 class="h2 mb-2"><?= e($product['name']) ?></h1>
        <p class="text-muted">SKU: <?= e($product['sku']) ?> · Category: <?= e($product['category_name']) ?></p>
        <div class="price-row mb-2">
          <span class="price fs-3"><?= e(format_money((float)$product['price'])) ?></span>
          <?php if ((float)$product['mrp'] > (float)$product['price']): ?>
            <span class="mrp"><?= e(format_money((float)$product['mrp'])) ?></span>
            <span class="badge text-bg-danger"><?= e(rtrim(rtrim(number_format($discount, 2), '0'), '.')) ?>% OFF</span>
          <?php endif; ?>
        </div>
        <p class="mb-2"><strong>Pack Quantity:</strong> <?= e($product['pack_size'] ?: 'N/A') ?></p>
        <p class="mb-3">
          <span class="badge text-bg-<?= e($stockInfo['class']) ?> badge-stock"><?= e($stockInfo['label']) ?></span>
          <?php if ((int)$product['stock'] > 0): ?>
            <span class="text-muted ms-2"><?= (int)$product['stock'] ?> available</span>
          <?php endif; ?>
        </p>
        <p><?= nl2br(e((string)$product['description'])) ?></p>
        <div class="d-flex flex-wrap align-items-center gap-3 mb-3">
          <div class="qty-control">
            <label class="me-2 fw-semibold">Qty</label>
            <button type="button" class="btn btn-sm btn-outline-secondary" onclick="var i=document.querySelector('[data-qty-for=\'<?= (int)$product['id'] ?>\']'); i.value=Math.max(1,(parseInt(i.value)||1)-1)">-</button>
            <input type="number" min="1" max="<?= (int)$product['stock'] ?>" value="1" data-qty-for="<?= (int)$product['id'] ?>">
            <button type="button" class="btn btn-sm btn-outline-secondary" onclick="var i=document.querySelector('[data-qty-for=\'<?= (int)$product['id'] ?>\']'); i.value=Math.min(<?= (int)$product['stock'] ?>,(parseInt(i.value)||1)+1)">+</button>
          </div>
        </div>
        <div class="d-flex flex-wrap gap-2">
          <button class="btn btn-pe" data-add-to-cart data-product-id="<?= (int)$product['id'] ?>" <?= (int)$product['stock'] <= 0 ? 'disabled' : '' ?>>
            <i class="fa-solid fa-cart-plus me-1"></i> Add to Cart
          </button>
          <button class="btn btn-outline-success" data-buy-now data-product-id="<?= (int)$product['id'] ?>" <?= (int)$product['stock'] <= 0 ? 'disabled' : '' ?>>
            Buy Now
          </button>
        </div>
      </div>
    </div>

    <?php if ($related): ?>
      <div class="related-wrap">
        <h2 class="h3 mb-3">Related Products</h2>
        <div class="row g-3">
          <?php foreach ($related as $product): ?>
            <div class="col-6 col-md-3">
              <?php include __DIR__ . '/includes/product-card.php'; ?>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    <?php endif; ?>
  </div>
</section>
<?php include __DIR__ . '/includes/footer.php'; ?>
