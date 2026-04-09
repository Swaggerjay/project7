<?php
require_once __DIR__ . '/../config/session_bootstrap.php';
require_once __DIR__ . '/products_data.php';
require_once __DIR__ . '/../core/cart_helpers.php';

$products = products_all();
$cartCount = cart_count();
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Tiksha Furnishing | Curtain Collections</title>
  <link rel="stylesheet" href="/phpcourse/project7/css/styles.css" />
</head>

<body>

<?php require __DIR__ . '/../includes/header.php'; ?>

<main>
  <section class="page-hero">
    <div class="container">
      <h1>Curtain Collections</h1>
      <p>Premium curtains crafted for elegant interiors.</p>
    </div>
  </section>

  <?php if ((int)($_GET['added'] ?? 0) === 1): ?>
  <div class="container" style="margin-top: 30px;">
      <div class="form-success form-message" style="display: block; text-align: center; font-weight: 500;">
        Item successfully added to your cart! You can continue shopping or <a href="/phpcourse/project7/pages/cart.php" style="text-decoration: underline; color: var(--brown); font-weight: 700;">view your cart</a>.
      </div>
  </div>
  <?php endif; ?>

  <section class="section">
    <div class="container grid cards-3">
      <?php foreach ($products as $product): ?>
        <div class="product-card">
          <a href="product_details.php?id=<?php echo $product['id']; ?>" class="product-card-link">
            <img src="<?php echo htmlspecialchars($product['image']); ?>" alt="">
            <h3><?php echo htmlspecialchars($product['name']); ?></h3>
          </a>
          <p class="product-price">₹<?php echo number_format($product['price']); ?></p>
          <div class="product-actions">
            <form action="/phpcourse/project7/actions/add_to_cart.php" method="post">
              <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>" />
              <input type="hidden" name="qty" value="1" />
              <button class="btn ghost" type="submit" style="width: 100%;">Add to Cart</button>
            </form>
            <a href="product_details.php?id=<?php echo $product['id']; ?>" class="btn primary" style="text-align: center; font-size: 0.85rem; padding: 12px 5px;">View Details</a>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </section>
</main>

<?php require __DIR__ . '/../includes/footer.php'; ?>
</body>
</html>
