<?php
declare(strict_types=1);
$whatsapp = get_setting('whatsapp_number', '918054798966');
$waMsg = rawurlencode('Hello Prisha Enterprises, I am interested in your disposable products.');
$businessName = get_setting('business_name', SITE_NAME);
$businessPhone = get_setting('phone', '');
$businessEmail = get_setting('email', '');
$businessAddress = get_setting('address', '');
?>
</main>
<footer class="site-footer mt-5">
  <div class="container py-5">
    <div class="row g-4">
      <div class="col-lg-4">
        <div class="footer-brand mb-3">
          <span class="brand-mark">PE</span>
          <strong><?= e($businessName) ?></strong>
        </div>
        <p class="mb-3">Quality disposable products and food packaging for restaurants, caterers, events and everyday use. Wholesale and retail available.</p>
        <p class="mb-1"><i class="fa-solid fa-location-dot me-2"></i><?= e($businessAddress) ?></p>
        <p class="mb-1"><i class="fa-solid fa-phone me-2"></i><?= e($businessPhone) ?></p>
        <p class="mb-0"><i class="fa-solid fa-envelope me-2"></i><?= e($businessEmail) ?></p>
      </div>
      <div class="col-6 col-lg-2">
        <h5>Shop</h5>
        <ul class="list-unstyled footer-links">
          <li><a href="<?= e(url('shop.php')) ?>">All Products</a></li>
          <li><a href="<?= e(url('categories.php')) ?>">Categories</a></li>
          <li><a href="<?= e(url('track-order.php')) ?>">Track Order</a></li>
          <li><a href="<?= e(url('bulk-order.php')) ?>">Bulk Orders</a></li>
        </ul>
      </div>
      <div class="col-6 col-lg-2">
        <h5>Account</h5>
        <ul class="list-unstyled footer-links">
          <li><a href="<?= e(url('login.php')) ?>">Login</a></li>
          <li><a href="<?= e(url('register.php')) ?>">Register</a></li>
          <li><a href="<?= e(url('account.php')) ?>">My Account</a></li>
          <li><a href="<?= e(url('my-orders.php')) ?>">My Orders</a></li>
        </ul>
      </div>
      <div class="col-lg-4">
        <h5>Quick Links</h5>
        <ul class="list-unstyled footer-links">
          <li><a href="<?= e(url('about.php')) ?>">About Us</a></li>
          <li><a href="<?= e(url('contact.php')) ?>">Contact Us</a></li>
          <li><a href="<?= e(url('privacy-policy.php')) ?>">Privacy Policy</a></li>
          <li><a href="<?= e(url('terms-and-conditions.php')) ?>">Terms &amp; Conditions</a></li>
          <li><a href="<?= e(url('cart.php')) ?>">Cart</a></li>
          <li><a href="https://wa.me/<?= e($whatsapp) ?>?text=<?= $waMsg ?>" target="_blank" rel="noopener">WhatsApp Us</a></li>
        </ul>
      </div>
    </div>
  </div>
  <div class="footer-bottom">
    <div class="container d-flex flex-wrap justify-content-between gap-2">
      <span>&copy; <?= date('Y') ?> <?= e($businessName) ?>. All rights reserved.</span>
      <span>
        <a href="<?= e(url('privacy-policy.php')) ?>">Privacy Policy</a>
        &nbsp;|&nbsp;
        <a href="<?= e(url('terms-and-conditions.php')) ?>">Terms &amp; Conditions</a>
      </span>
    </div>
  </div>
</footer>

<a class="whatsapp-float" href="https://wa.me/<?= e($whatsapp) ?>?text=<?= $waMsg ?>" target="_blank" rel="noopener" aria-label="WhatsApp">
  <i class="fa-brands fa-whatsapp"></i>
</a>

<div class="toast-container position-fixed bottom-0 end-0 p-3" id="toastContainer"></div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="<?= e(asset('js/script.js')) ?>"></script>
</body>
</html>
