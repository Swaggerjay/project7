<?php
require_once __DIR__ . '/session_bootstrap.php';
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Contact Tiksha Furnishing</title>
  <meta name="description" content="Contact Tiksha Furnishing for premium curtain consultations." />
  <link rel="stylesheet" href="css/styles.css" />
</head>

<body>

<?php $showConfirm = (($_GET['code'] ?? '') === 'success'); ?>
<?php if ($showConfirm): ?>
  <div class="modal-overlay is-visible" role="dialog" aria-modal="true" aria-labelledby="contact-confirm-title">
    <div class="modal-card">
      <div class="modal-icon">✓</div>
      <h3 id="contact-confirm-title">Message Sent!</h3>
      <p>Thank you for reaching out. Our design team will contact you within 24 hours.</p>
      <button class="btn primary modal-close" type="button" onclick="window.location='contact.php'">Close</button>
    </div>
  </div>
  <script>
    const overlay = document.querySelector('.modal-overlay');
    const closeBtn = document.querySelector('.modal-close');
    if (overlay && closeBtn) {
      closeBtn.addEventListener('click', () => { window.location = 'contact.php'; });
      overlay.addEventListener('click', (e) => {
        if (e.target === overlay) window.location = 'contact.php';
      });
    }
  </script>
<?php endif; ?>

  <!-- PAGE LOADER
  <div id="page-loader" class="page-loader">
    <div class="loader-ring"></div>
    <p>Preparing your space...</p>
  </div>
  -->

  <!-- HEADER -->
  <?php require __DIR__ . '/includes/header.php'; ?>

  <!-- MAIN -->
  <main>
    <section class="page-hero">
      <div class="container">
        <h1>Book a Consultation</h1>
        <p>Tell us about your space. We respond within 24 hours.</p>
      </div>
    </section>

    <section class="form-section">
      <div class="container contact-grid">

        <div class="form-card">
          <form action="contact_action.php" method="post">
            <div class="form-grid">
              <div class="form-field">
                <label>Name</label>
                <input name="name" type="text" required>
              </div>

              <div class="form-field">
                <label>Email</label>
                <input name="email" type="email" required>
              </div>

              <div class="form-field">
                <label>Phone</label>
                <input name="phone" type="tel" required>
              </div>

              <div class="form-field">
                <label>Message</label>
                <textarea name="message" required></textarea>
              </div>
            </div>

            <button class="btn primary" type="submit">Send Message</button>
          </form>
        </div>

        <div>
          <div class="about-panel">
            <h2>Business Address</h2>
            <p>Surat, Gujarat<br>+91 84878 37129<br>hello@tikshafurnishing.com</p>
          </div>
        </div>

      </div>
    </section>
  </main>

  <!-- FOOTER -->
  <?php require __DIR__ . '/includes/footer.php'; ?>
</body>
</html>
