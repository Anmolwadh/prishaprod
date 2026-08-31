<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/auth.php';

$pageTitle = 'Prisha Enterprises | Disposable Products & Food Packaging';
$metaDescription = 'Buy quality disposable meal trays, containers, glasses, eco-friendly plates and food packaging products from Prisha Enterprises.';

$pdo = getDB();
$categories = $pdo->query("SELECT * FROM categories WHERE status = 'Active' ORDER BY name")->fetchAll();
$featured = $pdo->query("SELECT * FROM products WHERE status = 'Active' ORDER BY featured DESC, id DESC LIMIT 8")->fetchAll();
$clients = $pdo->query("SELECT * FROM clients WHERE status = 'Active' ORDER BY sort_order ASC, name ASC")->fetchAll();

include __DIR__ . '/includes/header.php';
?>
<section class="hero">
  <div class="hero-media" aria-hidden="true"></div>
  <div class="container hero-inner">
    <div class="hero-content">
      <p class="hero-brand">Prisha Enterprises</p>
      <h1>Quality Disposable Products at Wholesale Prices</h1>
      <p>Shop food packaging and disposable products for restaurants, caterers, events, businesses and everyday use.</p>
      <div class="hero-actions d-flex flex-wrap gap-2">
        <a href="<?= e(url('shop.php')) ?>" class="btn btn-pe-light">Shop Now</a>
        <a href="<?= e(url('categories.php')) ?>" class="btn btn-pe-outline">View Categories</a>
      </div>
    </div>
  </div>
</section>

<section class="section-pad">
  <div class="container">
    <h2 class="section-title">Shop by Category</h2>
    <p class="section-sub">Meal trays, containers, glasses, eco plates and more — ready for retail and bulk orders.</p>
    <div class="row g-3">
      <?php foreach ($categories as $cat): ?>
        <div class="col-6 col-md-4 col-lg-3">
          <a class="category-card" href="<?= e(url('shop.php?category=' . urlencode($cat['slug']))) ?>">
            <img src="<?= e(product_image_url($cat['image'] ?? null)) ?>" alt="<?= e($cat['name']) ?>">
            <div class="body">
              <h3><?= e($cat['name']) ?></h3>
              <p><?= e(strlen((string)$cat['description']) > 70 ? substr((string)$cat['description'], 0, 67) . '...' : (string)$cat['description']) ?></p>
            </div>
          </a>
        </div>
      <?php endforeach; ?>
    </div>
    <div class="mt-4">
      <p class="text-muted mb-2"><strong>Popular:</strong>
        <a href="<?= e(url('shop.php?category=meal-trays&q=3+Compartment')) ?>">3 Compartment</a> ·
        <a href="<?= e(url('shop.php?category=meal-trays&q=5+Compartment')) ?>">5 Compartment</a> ·
        <a href="<?= e(url('shop.php?category=meal-trays&q=8+Compartment')) ?>">8 Compartment</a> ·
        <a href="<?= e(url('shop.php?category=disposable-containers&q=500ml')) ?>">500ml</a> ·
        <a href="<?= e(url('shop.php?category=disposable-containers&q=750ml')) ?>">750ml</a> ·
        <a href="<?= e(url('shop.php?category=disposable-containers&q=1000ml')) ?>">1000ml</a>
      </p>
    </div>
  </div>
</section>

<section class="section-pad bg-white border-top border-bottom">
  <div class="container">
    <div class="d-flex flex-wrap justify-content-between align-items-end gap-2 mb-3">
      <div>
        <h2 class="section-title mb-1">Featured Products</h2>
        <p class="section-sub mb-0">Top disposable products for kitchens, events and businesses.</p>
      </div>
      <a href="<?= e(url('shop.php')) ?>" class="btn btn-pe">View All Products</a>
    </div>
    <div class="row g-3">
      <?php foreach ($featured as $product): ?>
        <div class="col-6 col-md-4 col-lg-3">
          <?php include __DIR__ . '/includes/product-card.php'; ?>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<section class="section-pad">
  <div class="container">
    <h2 class="section-title">Why Choose Us</h2>
    <p class="section-sub">Built for reliable supply of disposable food packaging products.</p>
    <div class="row g-3 why-grid">
      <?php
      $why = [
        ['fa-tags', 'Wholesale Prices', 'Competitive rates for retail and bulk buyers.'],
        ['fa-certificate', 'Quality Products', 'Durable trays, containers and packaging.'],
        ['fa-truck-fast', 'Fast Delivery', 'Quick dispatch across serviceable areas.'],
        ['fa-shield-halved', 'Secure Ordering', 'Safe checkout with order tracking.'],
        ['fa-money-bill-wave', 'COD Available', 'Pay with Cash on Delivery.'],
        ['fa-briefcase', 'Business & Bulk Orders', 'Dedicated support for wholesale enquiries.'],
      ];
      foreach ($why as [$icon, $title, $text]): ?>
        <div class="col-md-6 col-lg-4">
          <div class="why-item">
            <i class="fa-solid <?= e($icon) ?>"></i>
            <h3 class="h5"><?= e($title) ?></h3>
            <p class="mb-0 text-muted"><?= e($text) ?></p>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<?php if ($clients): ?>
<section class="section-pad clients-section bg-white border-top">
  <div class="container">
    <h2 class="section-title">Our Clients</h2>
    <p class="section-sub">Trusted by restaurants and food businesses for everyday disposable supply.</p>
    <div class="clients-row">
      <?php foreach ($clients as $i => $client): ?>
      <article class="client-item">
        <div class="client-mark<?= $i % 2 === 1 ? ' client-mark-alt' : '' ?>" aria-hidden="true"><?= e(client_initials((string)$client['name'])) ?></div>
        <div>
          <h3><?= e((string)$client['name']) ?></h3>
          <?php if (!empty($client['description'])): ?>
          <p><?= e((string)$client['description']) ?></p>
          <?php endif; ?>
        </div>
      </article>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>

<section class="section-pad pt-0">
  <div class="container">
    <div class="bulk-cta">
      <div class="row align-items-center">
        <div class="col-lg-8">
          <h2 class="mb-2">Need Bulk Quantity?</h2>
          <p class="mb-0 opacity-90">Contact us for wholesale and bulk disposable product orders.</p>
        </div>
        <div class="col-lg-4 mt-3 mt-lg-0 d-flex flex-wrap gap-2 justify-content-lg-end">
          <a href="<?= e(url('bulk-order.php')) ?>" class="btn btn-pe-light">Request Bulk Quote</a>
          <a href="https://wa.me/<?= e(get_setting('whatsapp_number', '918054798966')) ?>?text=<?= rawurlencode('Hello Prisha Enterprises, I need a bulk quote for disposable products.') ?>" class="btn btn-pe-outline" target="_blank" rel="noopener">WhatsApp Us</a>
        </div>
      </div>
    </div>
  </div>
</section>
<?php include __DIR__ . '/includes/footer.php'; ?>
