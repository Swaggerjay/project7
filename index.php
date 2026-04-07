<?php
require_once __DIR__ . '/session_bootstrap.php';

// Debug: Output session info
if (isset($_GET['debug'])) {
    echo "<!-- Debug: userName = " . ($userName ?? 'null') . " -->";
    echo "<!-- Debug: session_id = " . session_id() . " -->";
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Tiksha Furnishing | Premium Curtains</title>
  <meta name="description" content="Premium curtains for homes, offices, and hotels." />
  <link rel="stylesheet" href="css/styles.css" />
</head>

<body>

  <!-- HEADER -->
  <?php require __DIR__ . '/includes/header.php'; ?>

  <!-- HERO -->
  <section class="hero home-hero">
    <div class="hero-overlay"></div>
    <div class="container hero-content text-center">
      <div class="reveal">
        <p class="eyebrow active">Premium Drapery</p>
        <h1>Transform Your Space with Elegant Curtains</h1>
        <p class="hero-subtitle">Luxury sheers, blackout drapes, and expert tailoring for the discerning home.</p>
        <div class="hero-actions">
          <a class="btn primary" href="products.php">Explore Collections</a>
          <a class="btn ghost" href="about.php">Our Story</a>
        </div>
      </div>
    </div>
  </section>

  <!-- FOOTER -->
  <?php require __DIR__ . '/includes/footer.php'; ?>
</body>
</html>
