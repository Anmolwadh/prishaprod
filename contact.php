<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/auth.php';

$success = null;
$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_csrf();
    $name = trim((string)($_POST['name'] ?? ''));
    $email = trim((string)($_POST['email'] ?? ''));
    $phone = trim((string)($_POST['phone'] ?? ''));
    $message = trim((string)($_POST['message'] ?? ''));
    if ($name === '' || $message === '') $errors[] = 'Name and message are required.';
    if ($email !== '' && !validate_email($email)) $errors[] = 'Invalid email.';
    if ($phone !== '' && !validate_phone($phone)) $errors[] = 'Invalid phone.';
    if (!$errors) {
        $stmt = getDB()->prepare(
            "INSERT INTO bulk_enquiries (name, business_name, phone, email, product, quantity, message) VALUES (?, NULL, ?, ?, 'General Contact', NULL, ?)"
        );
        $stmt->execute([$name, $phone ?: '0000000000', $email ?: null, $message]);
        $success = 'Thank you! We will get back to you soon.';
        $_POST = [];
    }
}

$pageTitle = 'Contact Us | Prisha Enterprises';
include __DIR__ . '/includes/header.php';
?>
<section class="page-hero">
  <div class="container">
    <h1 class="mb-1">Contact Us</h1>
    <p class="mb-0 text-muted">Questions about products, wholesale or orders? Reach out.</p>
  </div>
</section>
<section class="section-pad">
  <div class="container">
    <div class="row g-4">
      <div class="col-lg-5">
        <div class="account-card h-100">
          <h2 class="h5">Business Details</h2>
          <p class="mb-2"><i class="fa-solid fa-location-dot me-2 text-success"></i><?= e(get_setting('address', '')) ?></p>
          <p class="mb-2"><i class="fa-solid fa-phone me-2 text-success"></i><?= e(get_setting('phone', '')) ?></p>
          <p class="mb-2"><i class="fa-solid fa-envelope me-2 text-success"></i><?= e(get_setting('email', '')) ?></p>
          <a class="btn btn-success mt-2" href="https://wa.me/<?= e(get_setting('whatsapp_number', '')) ?>?text=<?= rawurlencode('Hello Prisha Enterprises, I am interested in your disposable products.') ?>" target="_blank" rel="noopener">
            <i class="fa-brands fa-whatsapp me-1"></i> WhatsApp Us
          </a>
        </div>
      </div>
      <div class="col-lg-7">
        <div class="account-card">
          <?php if ($success): ?><div class="alert alert-success"><?= e($success) ?></div><?php endif; ?>
          <?php if ($errors): ?><div class="alert alert-danger"><?php foreach ($errors as $err): ?><div><?= e($err) ?></div><?php endforeach; ?></div><?php endif; ?>
          <form method="post" class="row g-3">
            <?= csrf_field() ?>
            <div class="col-md-6"><label class="form-label">Name *</label><input name="name" class="form-control" required value="<?= e($_POST['name'] ?? '') ?>"></div>
            <div class="col-md-6"><label class="form-label">Phone</label><input name="phone" class="form-control" maxlength="10" value="<?= e($_POST['phone'] ?? '') ?>"></div>
            <div class="col-12"><label class="form-label">Email</label><input type="email" name="email" class="form-control" value="<?= e($_POST['email'] ?? '') ?>"></div>
            <div class="col-12"><label class="form-label">Message *</label><textarea name="message" class="form-control" rows="4" required><?= e($_POST['message'] ?? '') ?></textarea></div>
            <div class="col-12"><button class="btn btn-pe" type="submit">Send Message</button></div>
          </form>
        </div>
      </div>
    </div>
  </div>
</section>
<?php include __DIR__ . '/includes/footer.php'; ?>
