<?php
declare(strict_types=1);
require_once __DIR__ . '/../includes/auth.php';
require_admin();

$pdo = getDB();
$keys = [
    'business_name' => 'Business Name',
    'phone' => 'Phone',
    'email' => 'Email',
    'address' => 'Address',
    'shipping_charge' => 'Shipping Charge',
    'free_shipping_minimum' => 'Free Shipping Minimum',
    'whatsapp_number' => 'WhatsApp Number',
    'low_stock_threshold' => 'Low Stock Threshold',
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_csrf();
    foreach ($keys as $key => $label) {
        if (isset($_POST[$key])) {
            set_setting($key, trim((string)$_POST[$key]));
        }
    }
    if (!empty($_POST['admin_password'])) {
        $hash = password_hash((string)$_POST['admin_password'], PASSWORD_DEFAULT);
        $pdo->prepare('UPDATE admins SET password = ? WHERE id = ?')->execute([$hash, (int)$_SESSION['admin_id']]);
    }
    flash('success', 'Settings saved.');
    redirect('admin/settings.php');
}

$values = [];
foreach ($keys as $key => $label) {
    $values[$key] = get_setting($key, '');
}

$pageTitle = 'Settings';
include __DIR__ . '/includes/header.php';
?>
<div class="admin-card">
  <form method="post" class="row g-3">
    <?= csrf_field() ?>
    <?php foreach ($keys as $key => $label): ?>
      <div class="col-md-6">
        <label class="form-label"><?= e($label) ?></label>
        <?php if ($key === 'address'): ?>
          <textarea name="<?= e($key) ?>" class="form-control" rows="2"><?= e((string)$values[$key]) ?></textarea>
        <?php else: ?>
          <input name="<?= e($key) ?>" class="form-control" value="<?= e((string)$values[$key]) ?>">
        <?php endif; ?>
      </div>
    <?php endforeach; ?>
    <div class="col-md-6">
      <label class="form-label">Change Admin Password (optional)</label>
      <input type="password" name="admin_password" class="form-control" placeholder="Leave blank to keep current">
    </div>
    <div class="col-12"><button class="btn btn-success" type="submit">Save Settings</button></div>
  </form>
</div>
<?php include __DIR__ . '/includes/footer.php'; ?>
