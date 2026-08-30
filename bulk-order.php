<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/auth.php';

$success = null;
$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_csrf();
    $name = trim((string)($_POST['name'] ?? ''));
    $business = trim((string)($_POST['business_name'] ?? ''));
    $phone = trim((string)($_POST['phone'] ?? ''));
    $email = trim((string)($_POST['email'] ?? ''));
    $product = trim((string)($_POST['product'] ?? ''));
    $quantity = trim((string)($_POST['quantity'] ?? ''));
    $message = trim((string)($_POST['message'] ?? ''));

    if ($name === '') $errors[] = 'Name is required.';
    if (!validate_phone($phone)) $errors[] = 'Valid phone is required.';
    if ($email !== '' && !validate_email($email)) $errors[] = 'Invalid email.';
    if ($product === '') $errors[] = 'Product is required.';
    if ($quantity === '') $errors[] = 'Required quantity is required.';

    if (!$errors) {
        $stmt = getDB()->prepare(
            'INSERT INTO bulk_enquiries (name, business_name, phone, email, product, quantity, message) VALUES (?, ?, ?, ?, ?, ?, ?)'
        );
        $stmt->execute([$name, $business ?: null, $phone, $email ?: null, $product, $quantity, $message ?: null]);
        $success = 'Bulk enquiry submitted. Our team will contact you shortly.';
        $_POST = [];
    }
}

$pageTitle = 'Bulk Order Enquiry | Prisha Enterprises';
include __DIR__ . '/includes/header.php';
?>
<section class="page-hero">
  <div class="container">
    <h1 class="mb-1">Need Bulk Quantity?</h1>
    <p class="mb-0 text-muted">Request a wholesale quote for disposable products.</p>
  </div>
</section>
<section class="section-pad">
  <div class="container" style="max-width:760px">
    <div class="account-card">
      <?php if ($success): ?><div class="alert alert-success"><?= e($success) ?></div><?php endif; ?>
      <?php if ($errors): ?><div class="alert alert-danger"><?php foreach ($errors as $e): ?><div><?= e($e) ?></div><?php endforeach; ?></div><?php endif; ?>
      <form method="post" class="row g-3">
        <?= csrf_field() ?>
        <div class="col-md-6"><label class="form-label">Name *</label><input name="name" class="form-control" required value="<?= e($_POST['name'] ?? '') ?>"></div>
        <div class="col-md-6"><label class="form-label">Business Name</label><input name="business_name" class="form-control" value="<?= e($_POST['business_name'] ?? '') ?>"></div>
        <div class="col-md-6"><label class="form-label">Phone *</label><input name="phone" class="form-control" required maxlength="10" value="<?= e($_POST['phone'] ?? '') ?>"></div>
        <div class="col-md-6"><label class="form-label">Email</label><input type="email" name="email" class="form-control" value="<?= e($_POST['email'] ?? '') ?>"></div>
        <div class="col-md-6"><label class="form-label">Product *</label><input name="product" class="form-control" required value="<?= e($_POST['product'] ?? '') ?>" placeholder="e.g. 5 Compartment Meal Tray"></div>
        <div class="col-md-6"><label class="form-label">Required Quantity *</label><input name="quantity" class="form-control" required value="<?= e($_POST['quantity'] ?? '') ?>"></div>
        <div class="col-12"><label class="form-label">Message</label><textarea name="message" class="form-control" rows="4"><?= e($_POST['message'] ?? '') ?></textarea></div>
        <div class="col-12 d-flex flex-wrap gap-2">
          <button class="btn btn-pe" type="submit">Request Bulk Quote</button>
          <a class="btn btn-outline-success" href="https://wa.me/<?= e(get_setting('whatsapp_number', '')) ?>?text=<?= rawurlencode('Hello Prisha Enterprises, I need a bulk quote.') ?>" target="_blank" rel="noopener">WhatsApp Us</a>
        </div>
      </form>
    </div>
  </div>
</section>
<?php include __DIR__ . '/includes/footer.php'; ?>
