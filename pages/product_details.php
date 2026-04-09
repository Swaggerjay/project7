<?php
require_once __DIR__ . '/../config/session_bootstrap.php';
require_once __DIR__ . '/products_data.php';

$productId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$product = product_find($productId);

if (!$product) {
    header('Location: /phpcourse/project7/pages/products.php');
    exit;
}

// Prepare specifications for display
$specs = $product['specifications'] ?? [];
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title><?php echo htmlspecialchars($product['name']); ?> | Tiksha Furnishing</title>
  <link rel="stylesheet" href="/phpcourse/project7/css/styles.css" />
</head>
<body class="product-detail-page">

  <!-- PAGE LOADER -->
  <div id="page-loader" class="page-loader">
    <div class="loader-ring"></div>
    <p>Loading Product Details...</p>
  </div>

  <!-- HEADER -->
  <?php require __DIR__ . '/../includes/header.php'; ?>

  <main class="container detail-container">

    <?php if ((int)($_GET['added'] ?? 0) === 1): ?>
    <div class="reveal-up" style="margin-bottom: 25px;">
        <div class="form-success form-message" style="display: block; text-align: center; font-weight: 500;">
            Item added to your cart! <a href="/phpcourse/project7/pages/cart.php" style="text-decoration: underline; color: var(--brown); font-weight: 700;">View Cart</a>
        </div>
    </div>
    <?php endif; ?>
    
    <!-- BREADCRUMBS -->
    <nav class="breadcrumbs reveal-up">
        <a href="/phpcourse/project7/">Home</a> / 
        <a href="/phpcourse/project7/pages/products.php">Products</a> / 
        <span><?php echo htmlspecialchars($product['category'] ?? 'General'); ?></span>
    </nav>

    <div class="detail-grid">
        
        <!-- LEFT: IMAGE GALLERY -->
        <div class="gallery-section reveal-left">
            <div class="main-image-container glass">
                <img id="main-image" src="<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
                <div class="image-zoom-hint">Hover to Zoom</div>
            </div>
            <div class="thumb-strip">
                <div class="thumb active glass"><img src="<?php echo htmlspecialchars($product['image']); ?>" onclick="document.getElementById('main-image').src=this.src"></div>
                <!-- Mockup secondary images -->
                <div class="thumb glass"><img src="/phpcourse/project7/images/real-4.jpg" onclick="document.getElementById('main-image').src=this.src"></div>
                <div class="thumb glass"><img src="/phpcourse/project7/images/real-2.jpg" onclick="document.getElementById('main-image').src=this.src"></div>
            </div>
            <div class="action-buttons desktop-only">
                <form action="/phpcourse/project7/actions/add_to_cart.php" method="post" style="flex: 1;">
                    <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                    <input type="hidden" name="qty" value="1">
                    <button type="submit" class="btn ghost large" style="width: 100%;">ADD TO CART</button>
                </form>
                <form action="/phpcourse/project7/actions/buy_now.php" method="post" style="flex: 1;">
                    <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                    <button type="submit" class="btn primary large" style="width: 100%;">BUY NOW</button>
                </form>
            </div>
        </div>

        <!-- RIGHT: PRODUCT INFO -->
        <div class="info-section reveal-right">
            <p class="eyebrow"><?php echo htmlspecialchars($product['category'] ?? 'Premium Collection'); ?></p>
            <h1><?php echo htmlspecialchars($product['name']); ?></h1>
            
            <div class="rating-strip">
                <span class="stars">★★★★★</span>
                <span class="review-count">(128 Reviews)</span>
            </div>

            <div class="price-strip">
                <span class="current-price">₹<?php echo number_format($product['price'], 2); ?></span>
                <span class="original-price">₹<?php echo number_format($product['price'] * 1.25, 2); ?></span>
                <span class="discount-tag">25% OFF</span>
            </div>

            <div class="short-desc">
                <p><?php echo nl2br(htmlspecialchars($product['description'] ?? 'No description available.')); ?></p>
            </div>

            <div class="trust-strip reveal-up" style="--delay: 0.2s">
                <div class="trust-item">
                    <span class="icon">🚚</span>
                    <small>Free Delivery</small>
                </div>
                <div class="trust-item">
                    <span class="icon">🛡️</span>
                    <small>7 Day Replacement</small>
                </div>
                <div class="trust-item">
                    <span class="icon">💎</span>
                    <small>Quality Assured</small>
                </div>
            </div>

            <!-- MOBILE ACTIONS -->
            <div class="action-buttons mobile-only">
                <form action="/phpcourse/project7/actions/add_to_cart.php" method="post" style="flex: 1;">
                    <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                    <input type="hidden" name="qty" value="1">
                    <button type="submit" class="btn ghost" style="width: 100%;">CART</button>
                </form>
                <form action="/phpcourse/project7/actions/buy_now.php" method="post" style="flex: 1;">
                    <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                    <button type="submit" class="btn primary" style="width: 100%;">BUY NOW</button>
                </form>
            </div>

            <!-- SPECS TABLE -->
            <div class="specs-section reveal-up" style="--delay: 0.4s">
                <h3>Technical Specifications</h3>
                <table class="specs-table glass">
                    <?php if (!empty($specs)): ?>
                        <?php foreach ($specs as $key => $value): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($key); ?></td>
                                <td><?php echo htmlspecialchars($value); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="2">Standard Curtains Specification</td></tr>
                    <?php endif; ?>
                </table>
            </div>
        </div>

    </div>

    <!-- RELATED PRODUCTS -->
    <section class="related-section reveal-up">
        <h2>You May Also Like</h2>
        <div class="related-grid">
            <!-- Dynamically fetch or mock related products -->
            <p class="text-center opacity-50">Hand-picked drapes matching your style...</p>
        </div>
    </section>

  </main>

  <!-- STICKY ACTION BAR (VISIBLE ON MOBILE ONLY) -->
  <div class="sticky-actions reveal">
    <form action="/phpcourse/project7/actions/add_to_cart.php" method="post">
        <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
        <input type="hidden" name="qty" value="1">
        <button type="submit" class="btn-icon">🛒</button>
    </form>
    <form action="/phpcourse/project7/actions/buy_now.php" method="post" style="flex: 1;">
        <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
        <button type="submit" class="btn primary" style="width: 100%;">BUY NOW</button>
    </form>
  </div>

  <!-- FOOTER -->
  <?php require __DIR__ . '/../includes/footer.php'; ?>

  <script src="/phpcourse/project7/js/main.js"></script>
</body>
</html>
