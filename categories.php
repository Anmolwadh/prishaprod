<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/auth.php';
$categories = getDB()->query("SELECT c.*, (SELECT COUNT(*) FROM products p WHERE p.category_id = c.id AND p.status='Active') AS product_count FROM categories c WHERE c.status='Active' ORDER BY c.name")->fetchAll();
$pageTitle = 'Categories | Prisha Enterprises';
include __DIR__ . '/includes/header.php';
?>
<section class="page-hero">
  <div class="container">
    <h1 class="mb-1">Categories</h1>
    <p class="mb-0 text-muted">Browse disposable products by category.</p>
  </div>
</section>
<section class="section-pad">
  <div class="container">
    <div class="row g-3">
      <?php foreach ($categories as $cat): ?>
        <div class="col-6 col-md-4 col-lg-3">
          <a class="category-card" href="<?= e(url('shop.php?category=' . urlencode($cat['slug']))) ?>">
            <img src="<?= e(product_image_url($cat['image'] ?? null)) ?>" alt="<?= e($cat['name']) ?>">
            <div class="body">
              <h3><?= e($cat['name']) ?></h3>
              <p><?= (int)$cat['product_count'] ?> products</p>
            </div>
          </a>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php include __DIR__ . '/includes/footer.php'; ?>
