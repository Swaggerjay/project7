<?php
require_once __DIR__ . '/../config/session_bootstrap.php';
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>About Tiksha Furnishing</title>
  <meta name="description" content="Learn about Tiksha Furnishing, our mission, vision, and commitment to premium curtain craftsmanship." />
  <link rel="stylesheet" href="/phpcourse/project7/css/styles.css" />
</head>

<body>

  <!-- PAGE LOADER -->
  <div id="page-loader" class="page-loader">
    <div class="loader-ring"></div>
    <p>Preparing your space...</p>
  </div>

  <!-- HEADER -->
  <?php require __DIR__ . '/../includes/header.php'; ?>

  <!-- MAIN CONTENT -->
  <main>

    <section class="page-hero">
      <div class="container reveal-fade">
        <p class="eyebrow reveal-up" style="--delay: 0.1s">Our Story</p>
        <h1 class="reveal-up" style="--delay: 0.3s">Crafting Curtain Experiences Since 2008</h1>
        <p class="reveal-up" style="--delay: 0.5s">We combine design guidance, premium fabrics, and meticulous tailoring to deliver curtains that feel luxurious and last for years.</p>
      </div>
    </section>

    <section class="section">
      <div class="container about-grid">
        <div class="about-panel">
          <h2>Who We Are</h2>
          <p>Tiksha Furnishing is a boutique curtain studio specializing in premium-quality drapery for homes, offices, hotels, and commercial spaces.</p>
        </div>
        <div class="about-panel">
          <h2>Mission</h2>
          <p>To elevate interior spaces with tailored curtains that blend elegance, functionality, and lasting craftsmanship.</p>
        </div>
        <div class="about-panel">
          <h2>Vision</h2>
          <p>To be the most trusted curtain partner for premium homes and hospitality brands across India.</p>
        </div>
        <div class="about-panel">
          <h2>Experience &amp; Quality</h2>
          <p>With 18+ years of industry expertise, we follow a strict quality checklist before installation.</p>
        </div>
      </div>
    </section>

    <section class="section highlight">
      <div class="container why-grid">
        <div>
          <p class="eyebrow">Our Promise</p>
          <h2>Assured Quality and Aftercare</h2>
          <p>Every order includes fabric care guidance, maintenance options, and support.</p>
          <a class="btn primary" href="/phpcourse/project7/pages/contact.php">Talk to Our Team</a>
        </div>

        <div class="grid cards-2">
          <div class="mini-card"><h3>Fabric Certifications</h3></div>
          <div class="mini-card"><h3>Tailored Fit</h3></div>
          <div class="mini-card"><h3>Installation Experts</h3></div>
          <div class="mini-card"><h3>Design Support</h3></div>
        </div>
      </div>
    </section>

  </main>

  <!-- FOOTER -->
  <?php require __DIR__ . '/../includes/footer.php'; ?>
</body>
</html>
