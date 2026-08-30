<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/auth.php';

$businessName = get_setting('business_name', SITE_NAME);
$businessPhone = get_setting('phone', '+918054798966');
$businessEmail = get_setting('email', 'info@prishaenterprises.com');
$businessAddress = get_setting('address', '');

$pageTitle = 'Privacy Policy | Prisha Enterprises';
$metaDescription = 'Read the Privacy Policy of Prisha Enterprises for how we collect, use and protect your personal information.';
include __DIR__ . '/includes/header.php';
?>
<section class="page-hero">
  <div class="container">
    <h1 class="mb-1">Privacy Policy</h1>
    <p class="mb-0 text-muted">Last updated: <?= e(date('d M Y')) ?></p>
  </div>
</section>
<section class="section-pad">
  <div class="container">
    <div class="account-card policy-content">
      <p><?= e($businessName) ?> (“we”, “us”, “our”) respects your privacy and is committed to protecting the personal information you share while using our website and services.</p>

      <h2 class="h4 mt-4">1. Information We Collect</h2>
      <p>We may collect the following information when you browse, register, place an order, submit a bulk enquiry or contact us:</p>
      <ul>
        <li>Name, phone number, email address and delivery address</li>
        <li>Order details, product preferences and communication history</li>
        <li>Account login details (passwords are stored in hashed form)</li>
        <li>Technical data such as browser type, device information and IP address</li>
      </ul>

      <h2 class="h4 mt-4">2. How We Use Your Information</h2>
      <p>We use your information to:</p>
      <ul>
        <li>Process and deliver orders (including Cash on Delivery)</li>
        <li>Provide order tracking and customer support</li>
        <li>Respond to bulk/wholesale enquiries</li>
        <li>Improve website performance, security and user experience</li>
        <li>Send important order or account related updates</li>
      </ul>

      <h2 class="h4 mt-4">3. Sharing of Information</h2>
      <p>We do not sell your personal information. We may share limited data only with:</p>
      <ul>
        <li>Delivery/logistics partners to fulfil your order</li>
        <li>Service providers who help us operate the website securely</li>
        <li>Authorities when required by law</li>
      </ul>

      <h2 class="h4 mt-4">4. Cookies &amp; Session Data</h2>
      <p>Our website uses cookies/session storage for login, cart and security features. You can control cookies through your browser settings, but some features may not work without them.</p>

      <h2 class="h4 mt-4">5. Data Security</h2>
      <p>We use reasonable technical and organisational measures to protect your data, including secure password hashing and protected admin access. However, no method of transmission over the internet is 100% secure.</p>

      <h2 class="h4 mt-4">6. Data Retention</h2>
      <p>We retain order and account information as needed for business, legal and accounting purposes. You may request account updates through your profile or by contacting us.</p>

      <h2 class="h4 mt-4">7. Your Rights</h2>
      <p>You may request access, correction or deletion of your personal information, subject to applicable law and order/record requirements.</p>

      <h2 class="h4 mt-4">8. Third-Party Links</h2>
      <p>Our website may include links such as WhatsApp. We are not responsible for the privacy practices of third-party platforms.</p>

      <h2 class="h4 mt-4">9. Children’s Privacy</h2>
      <p>Our services are intended for business and adult customers. We do not knowingly collect personal information from children.</p>

      <h2 class="h4 mt-4">10. Changes to This Policy</h2>
      <p>We may update this Privacy Policy from time to time. The updated version will be posted on this page with a revised date.</p>

      <h2 class="h4 mt-4">11. Contact Us</h2>
      <p>For privacy related questions, contact:</p>
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
