<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/auth.php';

$businessName = get_setting('business_name', SITE_NAME);
$businessPhone = get_setting('phone', '+918054798966');
$businessEmail = get_setting('email', 'info@prishaenterprises.com');
$businessAddress = get_setting('address', '');

$pageTitle = 'Terms and Conditions | Prisha Enterprises';
$metaDescription = 'Read the Terms and Conditions for shopping with Prisha Enterprises, including orders, COD, delivery and returns.';
include __DIR__ . '/includes/header.php';
?>
<section class="page-hero">
  <div class="container">
    <h1 class="mb-1">Terms and Conditions</h1>
    <p class="mb-0 text-muted">Last updated: <?= e(date('d M Y')) ?></p>
  </div>
</section>
<section class="section-pad">
  <div class="container">
    <div class="account-card policy-content">
      <p>Welcome to <?= e($businessName) ?>. By accessing our website and placing an order, you agree to the following Terms and Conditions.</p>

      <h2 class="h4 mt-4">1. About Us</h2>
      <p><?= e($businessName) ?> sells disposable products and food packaging for retail and wholesale customers, including meal trays, containers, glasses, eco-friendly plates, butter paper and related items.</p>

      <h2 class="h4 mt-4">2. Eligibility</h2>
      <p>You confirm that you are legally capable of entering into a binding contract and that the information you provide during registration or checkout is accurate and complete.</p>

      <h2 class="h4 mt-4">3. Products &amp; Pricing</h2>
      <ul>
        <li>Product images are for representation; actual packaging may vary slightly.</li>
        <li>Prices are shown in Indian Rupees (₹) and may change without prior notice.</li>
        <li>MRP, selling price, discount and pack size are displayed on product pages.</li>
        <li>Stock availability is updated regularly but may change before order confirmation.</li>
      </ul>

      <h2 class="h4 mt-4">4. Orders</h2>
      <ul>
        <li>An order is accepted when we confirm it and begin processing.</li>
        <li>We may cancel an order due to stock unavailability, pricing error, incomplete address details or suspected misuse.</li>
        <li>You will receive an order number for tracking after successful placement.</li>
      </ul>

      <h2 class="h4 mt-4">5. Payment</h2>
      <p>Currently, orders are accepted with <strong>Cash on Delivery (COD)</strong>. Payment is collected at the time of delivery. For COD orders, payment status remains pending until marked paid by our team after successful delivery.</p>

      <h2 class="h4 mt-4">6. Shipping &amp; Delivery</h2>
      <ul>
        <li>Delivery timelines are estimates and may vary by location and courier conditions.</li>
        <li>Please ensure your phone number and address are correct for smooth delivery.</li>
      </ul>

      <h2 class="h4 mt-4">7. Bulk / Wholesale Orders</h2>
      <p>Bulk enquiries submitted through our website are requests for quotation. Final pricing, quantity and delivery terms for wholesale orders may be confirmed separately by our team.</p>

      <h2 class="h4 mt-4">8. Cancellations</h2>
      <ul>
        <li>You may request cancellation before the order is shipped by contacting us with your order number.</li>
        <li>Once shipped or out for delivery, cancellation may not be possible.</li>
        <li>We reserve the right to cancel orders in case of stock, logistics or compliance issues.</li>
      </ul>

      <h2 class="h4 mt-4">9. Returns &amp; Damaged Products</h2>
      <ul>
        <li>Please check packages at delivery for visible damage.</li>
        <li>Report damaged, missing or incorrect items within 48 hours of delivery with order details and photos where possible.</li>
        <li>Returns/replacements are reviewed case by case for manufacturing defects or shipping damage.</li>
        <li>Used, opened or customer-damaged products may not be eligible for return.</li>
      </ul>

      <h2 class="h4 mt-4">10. User Accounts</h2>
      <p>You are responsible for maintaining the confidentiality of your account credentials and for all activity under your account.</p>

      <h2 class="h4 mt-4">11. Intellectual Property</h2>
      <p>Website content, branding, product descriptions and design elements of <?= e($businessName) ?> are protected and may not be copied or reused without permission.</p>

      <h2 class="h4 mt-4">12. Limitation of Liability</h2>
      <p>To the fullest extent permitted by law, <?= e($businessName) ?> is not liable for indirect or consequential losses arising from delays, stock shortages, courier issues or misuse of products. Our liability for any order is limited to the order value paid/payable for that order.</p>

      <h2 class="h4 mt-4">13. Governing Law</h2>
      <p>These Terms are governed by the laws of India. Any disputes shall be subject to the jurisdiction of competent courts in the area of our business operations.</p>

      <h2 class="h4 mt-4">14. Changes to Terms</h2>
      <p>We may revise these Terms and Conditions at any time. Continued use of the website after updates means you accept the revised terms.</p>

      <h2 class="h4 mt-4">15. Contact</h2>
      <ul class="mb-0">
        <li><strong><?= e($businessName) ?></strong></li>
        <li>Phone: <?= e($businessPhone) ?></li>
        <li>Email: <?= e($businessEmail) ?></li>
        <?php if ($businessAddress): ?><li>Address: <?= e($businessAddress) ?></li><?php endif; ?>
      </ul>
    </div>
  </div>
</section>
<?php include __DIR__ . '/includes/footer.php'; ?>
