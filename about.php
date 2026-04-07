<?php
require_once __DIR__ . '/session_bootstrap.php';
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>About Tiksha Furnishing</title>
  <meta name="description" content="Learn about Tiksha Furnishing, our mission, vision, and commitment to premium curtain craftsmanship." />
  <link rel="stylesheet" href="css/styles.css" />
</head>

<body>

  <!-- PAGE LOADER -->
  <div id="page-loader" class="page-loader">
    <div class="loader-ring"></div>
    <p>Preparing your space...</p>
  </div>

  <!-- HEADER -->
  <header class="site-header" id="top">
    <div class="container header-inner">
      <a class="brand" href="index.php">
        <span class="brand-mark">TF</span>
        <span class="brand-text">
          <strong>Tiksha Furnishing</strong>
          <small>Premium Curtain Studio</small>
        </span>
      </a>

      <button class="nav-toggle"><span></span><span></span><span></span></button>

      <nav class="site-nav">
        <a href="index.php">Home</a>
        <a href="products.php">Products</a>
        <a href="about.php" class="active">About</a>
        <a href="contact.php">Contact</a>

        <a href="login.php">
          <?php echo $userName ? "Hi, " . htmlspecialchars($userName) : "Login"; ?>
        </a>

        <?php if ($userName): ?>
          <a href="logout.php">Logout</a>
        <?php endif; ?>
      </nav>
    </div>
  </header>

  <!-- MAIN CONTENT -->
  <main>

    <section class="page-hero">
      <div class="container">
        <p class="eyebrow">Our Story</p>
        <h1>Crafting Curtain Experiences Since 2008</h1>
        <p>We combine design guidance, premium fabrics, and meticulous tailoring to deliver curtains that feel luxurious and last for years.</p>
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
          <h2>Experience & Quality</h2>
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
          <a class="btn primary" href="contact.php">Talk to Our Team</a>
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
  <?php require __DIR__ . '/includes/footer.php'; ?>
</body>
</html>
