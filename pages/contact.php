<?php
require_once __DIR__ . '/../config/session_bootstrap.php';
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Book a Consultation | Tiksha Furnishing</title>
  <meta name="description" content="Contact Tiksha Furnishing for premium curtain consultations and bespoke drapery." />
  <link rel="stylesheet" href="/phpcourse/project7/css/styles.css" />
</head>

<body class="luxury-contact-page">

<?php $showConfirm = (($_GET['code'] ?? '') === 'success'); ?>
<?php if ($showConfirm): ?>
  <div class="modal-overlay is-visible" role="dialog" aria-modal="true">
    <div class="modal-card">
      <div class="modal-icon">✓</div>
      <h3>Consultation Requested</h3>
      <p>Thank you for reaching out. Our design expert will contact you shortly to discuss your project.</p>
      <button class="btn primary modal-close" type="button" onclick="window.location='/phpcourse/project7/pages/contact.php'">Return Home</button>
    </div>
  </div>
<?php endif; ?>

  <!-- HEADER -->
  <?php require __DIR__ . '/../includes/header.php'; ?>

  <main>
    <!-- LUXURY HERO -->
    <section class="page-hero reveal-fade">
      <div class="container text-center">
        <p class="eyebrow reveal-up">Bespoke Design Studio</p>
        <h1 class="reveal-up" style="--delay: 0.2s">Let's Craft Your Dream Space</h1>
        <p class="reveal-up" style="--delay: 0.4s">Speak with our consultants about bespoke drapery and luxury furnishings.</p>
      </div>
    </section>

    <section class="section">
      <div class="container contact-grid">

        <!-- LEFT: LUXURY CONSULTATION FORM -->
        <div class="contact-luxury-card reveal-left">
          <h2>Start a Project</h2>
          <form action="/phpcourse/project7/actions/contact_action.php" method="post">
            <div class="floating-group">
                <input name="name" type="text" placeholder=" " required id="name">
                <label for="name">Your Full Name</label>
            </div>

            <div class="floating-group">
                <input name="email" type="email" placeholder=" " required id="email">
                <label for="email">Email Address</label>
            </div>

            <div class="floating-group">
                <input name="phone" type="tel" placeholder=" " required id="phone">
                <label for="phone">Phone Number</label>
            </div>

            <div class="floating-group">
                <textarea name="message" placeholder=" " required id="message" rows="4"></textarea>
                <label for="message">Tell us about your space (Room type, style, etc.)</label>
            </div>

            <button class="btn primary large shimmer" type="submit" style="width: 100%; padding: 20px;">REQUEST CONSULTATION</button>
          </form>
        </div>

        <!-- RIGHT: CONTACT INFO SIDEBAR -->
        <div class="info-sidebar reveal-right">
          
          <a href="tel:+918487837129" class="info-tile">
            <div class="icon">📞</div>
            <div>
                <h4>Direct Line</h4>
                <p>+91 84878 37129</p>
                <p><small class="opacity-50">Mon - Sat, 10am - 8pm</small></p>
            </div>
          </a>

          <a href="mailto:hello@tikshafurnishing.com" class="info-tile" style="transition-delay: 0.1s">
            <div class="icon">✉️</div>
            <div>
                <h4>Email Enquiry</h4>
                <p>hello@tikshafurnishing.com</p>
                <p><small class="opacity-50">Response within 24 hours</small></p>
            </div>
          </a>

          <div class="info-tile" style="transition-delay: 0.2s">
            <div class="icon">📍</div>
            <div>
                <h4>Visit Our Studio</h4>
                <p>Premium Curtains Hub<br>Surat, Gujarat, India</p>
            </div>
          </div>

        </div>

      </div>

    </section>
  </main>

  <!-- FOOTER -->
  <?php require __DIR__ . '/../includes/footer.php'; ?>

  <script src="/phpcourse/project7/js/main.js"></script>
</body>
</html>
