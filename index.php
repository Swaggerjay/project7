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
      <div class="reveal-fade">
        <p class="eyebrow active reveal-up" style="--delay: 0.2s">Premium Drapery</p>
        <h1 class="reveal-up" style="--delay: 0.4s">Transform Your Space with Elegant Curtains</h1>
        <p class="hero-subtitle reveal-up" style="--delay: 0.6s">Luxury sheers, blackout drapes, and expert tailoring for the discerning home.</p>
        <div class="hero-actions reveal-up" style="--delay: 0.8s">
          <a class="btn primary" href="products.php">Explore Collections</a>
          <a class="btn ghost" href="about.php">Our Story</a>
        </div>
      </div>
    </div>
  </section>


  <!-- FEATURED COLLECTIONS -->
  <section class="section">
    <div class="container">
      <div class="section-header text-center reveal-up">
        <p class="eyebrow">Hand-picked for You</p>
        <h2>Featured Collections</h2>
      </div>
      
      <div class="grid cards-3">
        <?php 
        require_once __DIR__ . '/products_data.php';
        $featured = array_slice(products_all(), 0, 3);
        foreach ($featured as $product): 
        ?>
          <div class="product-card reveal-up">
            <a href="product_details.php?id=<?php echo $product['id']; ?>" class="product-card-link">
              <img src="<?php echo htmlspecialchars($product['image']); ?>" alt="">
              <h3><?php echo htmlspecialchars($product['name']); ?></h3>
            </a>
            <p class="product-price">₹<?php echo number_format($product['price']); ?></p>
            <div class="product-actions">
              <a href="product_details.php?id=<?php echo $product['id']; ?>" class="btn primary" style="width: 100%; text-align: center;">View Details</a>
            </div>
          </div>
        <?php endforeach; ?>
      </div>

      <div class="text-center reveal-up" style="margin-top: 40px;">
        <a href="products.php" class="btn ghost">View All Products</a>
      </div>
    </div>
  </section>
  <?php require __DIR__ . '/includes/footer.php'; ?>
</body>
</html>
