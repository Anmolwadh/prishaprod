<?php
declare(strict_types=1);
require_once __DIR__ . '/../includes/auth.php';
require_admin();

$pdo = getDB();
$categories = $pdo->query("SELECT id, name FROM categories WHERE status='Active' ORDER BY name")->fetchAll();
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_csrf();
    $name = trim((string)($_POST['name'] ?? ''));
    $sku = trim((string)($_POST['sku'] ?? ''));
    $categoryId = (int)($_POST['category_id'] ?? 0);
    $description = trim((string)($_POST['description'] ?? ''));
    $short = trim((string)($_POST['short_description'] ?? ''));
    $mrp = (float)($_POST['mrp'] ?? 0);
    $price = (float)($_POST['price'] ?? 0);
    $stock = (int)($_POST['stock'] ?? 0);
    $pack = trim((string)($_POST['pack_size'] ?? ''));
    $status = ($_POST['status'] ?? 'Active') === 'Inactive' ? 'Inactive' : 'Active';
    $featured = !empty($_POST['featured']) ? 1 : 0;
    $gst = max(0, min(100, (float)($_POST['gst'] ?? 18)));
    $discount = calc_discount($mrp, $price);
    $slug = slugify($name);

    if ($name === '' || $sku === '' || $categoryId <= 0) $errors[] = 'Name, SKU and category are required.';
    if ($price < 0 || $mrp < 0) $errors[] = 'Prices cannot be negative.';

    $imageName = null;
    if (!empty($_FILES['image']['name'])) {
        $up = upload_product_image($_FILES['image']);
        if (!$up['success']) $errors[] = $up['message'];
        else $imageName = $up['filename'];
    }

    if (!$errors) {
        try {
            $stmt = $pdo->prepare(
                'INSERT INTO products (category_id, name, slug, sku, description, short_description, price, mrp, discount, gst, stock, pack_size, image, status, featured)
                 VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)'
            );
            $stmt->execute([$categoryId, $name, $slug . '-' . substr(bin2hex(random_bytes(2)), 0, 4), $sku, $description, $short, $price, $mrp, $discount, $gst, $stock, $pack, $imageName, $status, $featured]);
            flash('success', 'Product added.');
            redirect('admin/products.php');
        } catch (PDOException $e) {
            error_log($e->getMessage());
            $errors[] = 'Could not save product. SKU may already exist.';
        }
    }
}

$pageTitle = 'Add Product';
include __DIR__ . '/includes/header.php';
?>
<div class="admin-card">
  <?php if ($errors): ?><div class="alert alert-danger"><?php foreach ($errors as $err): ?><div><?= e($err) ?></div><?php endforeach; ?></div><?php endif; ?>
  <form method="post" enctype="multipart/form-data" class="row g-3">
    <?= csrf_field() ?>
    <div class="col-md-8"><label class="form-label">Name *</label><input name="name" class="form-control" required value="<?= e($_POST['name'] ?? '') ?>"></div>
    <div class="col-md-4"><label class="form-label">SKU *</label><input name="sku" class="form-control" required value="<?= e($_POST['sku'] ?? '') ?>"></div>
    <div class="col-md-6">
      <label class="form-label">Category *</label>
      <select name="category_id" class="form-select" required>
        <option value="">Select</option>
        <?php foreach ($categories as $c): ?>
          <option value="<?= (int)$c['id'] ?>" <?= ((int)($_POST['category_id'] ?? 0) === (int)$c['id']) ? 'selected' : '' ?>><?= e($c['name']) ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="col-md-3"><label class="form-label">MRP</label><input type="number" step="0.01" name="mrp" class="form-control" id="mrp" value="<?= e($_POST['mrp'] ?? '0') ?>"></div>
    <div class="col-md-3"><label class="form-label">Selling Price</label><input type="number" step="0.01" name="price" class="form-control" id="price" value="<?= e($_POST['price'] ?? '0') ?>"></div>
    <div class="col-md-3"><label class="form-label">Discount %</label><input type="text" class="form-control" id="discountPreview" readonly value="0"></div>
    <div class="col-md-3"><label class="form-label">GST %</label><input type="number" step="0.01" min="0" max="100" name="gst" class="form-control" value="<?= e($_POST['gst'] ?? '18') ?>"></div>
    <div class="col-md-3"><label class="form-label">Stock</label><input type="number" name="stock" class="form-control" value="<?= e($_POST['stock'] ?? '0') ?>"></div>
    <div class="col-md-6"><label class="form-label">Pack Size</label><input name="pack_size" class="form-control" value="<?= e($_POST['pack_size'] ?? '') ?>"></div>
    <div class="col-md-3">
      <label class="form-label">Status</label>
      <select name="status" class="form-select">
        <option value="Active">Active</option>
        <option value="Inactive">Inactive</option>
      </select>
    </div>
    <div class="col-md-3 d-flex align-items-end"><div class="form-check"><input class="form-check-input" type="checkbox" name="featured" id="featured"><label class="form-check-label" for="featured">Featured</label></div></div>
    <div class="col-12"><label class="form-label">Short Description</label><input name="short_description" class="form-control" value="<?= e($_POST['short_description'] ?? '') ?>"></div>
    <div class="col-12"><label class="form-label">Description</label><textarea name="description" class="form-control" rows="4"><?= e($_POST['description'] ?? '') ?></textarea></div>
    <div class="col-md-6"><label class="form-label">Image (JPG/PNG/WEBP, max 5MB)</label><input type="file" name="image" class="form-control" accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp"></div>
    <div class="col-12"><button class="btn btn-success" type="submit">Save Product</button> <a href="<?= e(url('admin/products.php')) ?>" class="btn btn-outline-secondary">Cancel</a></div>
  </form>
</div>
<script>
function updDisc(){const m=+document.getElementById('mrp').value||0,p=+document.getElementById('price').value||0;document.getElementById('discountPreview').value=(m>0&&p<m)?(((m-p)/m)*100).toFixed(2):'0';}
document.getElementById('mrp').addEventListener('input',updDisc);
document.getElementById('price').addEventListener('input',updDisc);
</script>
<?php include __DIR__ . '/includes/footer.php'; ?>
