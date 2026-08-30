<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/auth.php';
$pageTitle = 'About Us | Prisha Enterprises';
$metaDescription = 'Learn about Prisha Enterprises — suppliers of disposable meal trays, containers, glasses and eco-friendly food packaging.';
include __DIR__ . '/includes/header.php';
?>
<section class="page-hero">
  <div class="container">
    <h1 class="mb-1">About Prisha Enterprises</h1>
    <p class="mb-0 text-muted">Trusted source for disposable products and food packaging.</p>
  </div>
</section>
<section class="section-pad">
  <div class="container">
    <div class="row g-4 align-items-center">
      <div class="col-lg-6">
        <img class="img-fluid rounded-4 border" src="<?= e(asset('images/hero-banner.jpg')) ?>" alt="Prisha Enterprises disposable packaging">
      </div>
      <div class="col-lg-6">
        <h2 class="h3">Quality disposables for every kitchen</h2>
        <p>Prisha Enterprises supplies disposable meal trays, containers, glasses, eco-friendly plates, butter paper and food packaging products for restaurants, caterers, events, businesses and everyday use.</p>
        <p>We focus on reliable quality, wholesale pricing and easy Cash on Delivery ordering so you can stock your kitchen without hassle.</p>
        <ul>
          <li>Wholesale and retail supply</li>
          <li>Food-safe disposable packaging</li>
          <li>Bulk order support</li>
          <li>Fast order processing and tracking</li>
        </ul>
        <a href="<?= e(url('shop.php')) ?>" class="btn btn-pe">Explore Products</a>
      </div>
    </div>
  </div>
</section>
<?php include __DIR__ . '/includes/footer.php'; ?>
