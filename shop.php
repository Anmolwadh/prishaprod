<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/auth.php';

$pdo = getDB();
$q = trim((string)($_GET['q'] ?? ''));
$categorySlug = trim((string)($_GET['category'] ?? ''));
$minPrice = isset($_GET['min_price']) && $_GET['min_price'] !== '' ? (float)$_GET['min_price'] : null;
$maxPrice = isset($_GET['max_price']) && $_GET['max_price'] !== '' ? (float)$_GET['max_price'] : null;
$sort = (string)($_GET['sort'] ?? 'newest');
$page = max(1, (int)($_GET['page'] ?? 1));

$categories = $pdo->query("SELECT * FROM categories WHERE status = 'Active' ORDER BY name")->fetchAll();

$where = ["p.status = 'Active'"];
$params = [];

if ($q !== '') {
    $where[] = '(p.name LIKE ? OR p.sku LIKE ? OR c.name LIKE ? OR p.short_description LIKE ?)';
    $like = '%' . $q . '%';
    array_push($params, $like, $like, $like, $like);
}
if ($categorySlug !== '') {
    $where[] = 'c.slug = ?';
    $params[] = $categorySlug;
}
if ($minPrice !== null) {
    $where[] = 'p.price >= ?';
    $params[] = $minPrice;
}
if ($maxPrice !== null) {
    $where[] = 'p.price <= ?';
    $params[] = $maxPrice;
}

$orderBy = match ($sort) {
    'price_asc' => 'p.price ASC',
    'price_desc' => 'p.price DESC',
    'name' => 'p.name ASC',
    default => 'p.created_at DESC',
};

$sqlWhere = implode(' AND ', $where);
$countStmt = $pdo->prepare("SELECT COUNT(*) FROM products p JOIN categories c ON c.id = p.category_id WHERE $sqlWhere");
$countStmt->execute($params);
$total = (int)$countStmt->fetchColumn();
$pager = paginate($total, $page, 12);

$stmt = $pdo->prepare(
    "SELECT p.*, c.name AS category_name FROM products p
     JOIN categories c ON c.id = p.category_id
     WHERE $sqlWhere ORDER BY $orderBy LIMIT {$pager['per_page']} OFFSET {$pager['offset']}"
);
$stmt->execute($params);
$products = $stmt->fetchAll();

$pageTitle = 'Shop Disposable Products | Prisha Enterprises';
$metaDescription = 'Browse disposable meal trays, containers, glasses, plates and food packaging from Prisha Enterprises.';
include __DIR__ . '/includes/header.php';
?>
<section class="page-hero">
  <div class="container">
    <h1 class="mb-1">Shop</h1>
    <p class="mb-0 text-muted">Find the right disposable products for your business or home.</p>
  </div>
</section>
<section class="section-pad">
  <div class="container">
    <div class="row g-4">
      <div class="col-lg-3">
        <form class="filter-box" method="get" action="<?= e(url('shop.php')) ?>">
          <h2 class="h5 mb-3">Filters</h2>
          <div class="mb-3">
            <label class="form-label">Search</label>
            <input type="text" name="q" class="form-control" value="<?= e($q) ?>" placeholder="Name, SKU, category">
          </div>
          <div class="mb-3">
            <label class="form-label">Category</label>
            <select name="category" class="form-select">
              <option value="">All Categories</option>
              <?php foreach ($categories as $cat): ?>
                <option value="<?= e($cat['slug']) ?>" <?= $categorySlug === $cat['slug'] ? 'selected' : '' ?>><?= e($cat['name']) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="row g-2 mb-3">
            <div class="col-6">
              <label class="form-label">Min Price</label>
              <input type="number" step="0.01" name="min_price" class="form-control" value="<?= e($minPrice !== null ? (string)$minPrice : '') ?>">
            </div>
            <div class="col-6">
              <label class="form-label">Max Price</label>
              <input type="number" step="0.01" name="max_price" class="form-control" value="<?= e($maxPrice !== null ? (string)$maxPrice : '') ?>">
            </div>
          </div>
          <div class="mb-3">
            <label class="form-label">Sort By</label>
            <select name="sort" class="form-select">
              <option value="newest" <?= $sort === 'newest' ? 'selected' : '' ?>>Newest</option>
              <option value="price_asc" <?= $sort === 'price_asc' ? 'selected' : '' ?>>Price: Low to High</option>
              <option value="price_desc" <?= $sort === 'price_desc' ? 'selected' : '' ?>>Price: High to Low</option>
              <option value="name" <?= $sort === 'name' ? 'selected' : '' ?>>Name</option>
            </select>
          </div>
          <button class="btn btn-pe w-100" type="submit">Apply Filters</button>
          <a href="<?= e(url('shop.php')) ?>" class="btn btn-outline-secondary w-100 mt-2">Reset</a>
        </form>
      </div>
      <div class="col-lg-9">
        <div class="d-flex justify-content-between align-items-center mb-3">
          <p class="mb-0 text-muted"><?= (int)$total ?> product(s) found</p>
        </div>
        <?php if (!$products): ?>
          <div class="alert alert-light border">No products matched your filters.</div>
        <?php else: ?>
          <div class="row g-3">
            <?php foreach ($products as $product): ?>
              <div class="col-6 col-md-4">
                <?php include __DIR__ . '/includes/product-card.php'; ?>
              </div>
            <?php endforeach; ?>
          </div>
          <?php if ($pager['pages'] > 1): ?>
            <nav class="mt-4">
              <ul class="pagination justify-content-center">
                <?php for ($i = 1; $i <= $pager['pages']; $i++):
                  $qs = $_GET; $qs['page'] = $i; ?>
                  <li class="page-item <?= $i === $pager['page'] ? 'active' : '' ?>">
                    <a class="page-link" href="?<?= e(http_build_query($qs)) ?>"><?= $i ?></a>
                  </li>
                <?php endfor; ?>
              </ul>
            </nav>
          <?php endif; ?>
        <?php endif; ?>
      </div>
    </div>
  </div>
</section>
<?php include __DIR__ . '/includes/footer.php'; ?>
