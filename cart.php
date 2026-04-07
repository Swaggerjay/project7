<?php
require_once __DIR__ . '/session_bootstrap.php';
require_once __DIR__ . '/products_data.php';
require_once __DIR__ . '/cart_helpers.php';

$next = 'cart.php';
require __DIR__ . '/auth_check.php';

$products = products_all();
$cartCount = cart_count();
$cartData = cart_totals($products);
$lines = $cartData['lines'];
$total = $cartData['total'];
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Your Cart | Tiksha Furnishing</title>
  <link rel="stylesheet" href="css/styles.css" />
</head>
<body>

<?php require __DIR__ . '/includes/header.php'; ?>

<main>
  <section class="page-hero">
    <div class="container">
      <h1>Your Cart</h1>
      <p>Review your selected curtains before checkout.</p>
    </div>
  </section>

  <section class="section">
    <div class="container">
      <?php if (empty($lines)): ?>
        <div class="card">
          <h3>Your cart is empty.</h3>
          <p style="margin-top:10px;">Browse our latest curtains to add items.</p>
          <div style="margin-top:18px;">
            <a class="btn primary" href="products.php">Shop Products</a>
          </div>
        </div>
      <?php else: ?>
        <form action="cart_action.php" method="post">
          <div class="cart-table">
            <div class="cart-row cart-head">
              <span>Product</span>
              <span>Price</span>
              <span>Qty</span>
              <span>Total</span>
              <span>Action</span>
            </div>
            <?php foreach ($lines as $line): ?>
              <div class="cart-row">
                <span><?php echo htmlspecialchars($line['product']['name']); ?></span>
                <span>₹<?php echo number_format($line['product']['price']); ?></span>
                <span>
                  <input class="cart-qty" type="number" name="qty[<?php echo $line['product']['id']; ?>]" min="1" max="10" value="<?php echo $line['qty']; ?>" />
                </span>
                <span>₹<?php echo number_format($line['line_total']); ?></span>
                <span>
                  <button class="btn ghost" type="submit" name="remove" value="<?php echo $line['product']['id']; ?>">Remove</button>
                </span>
              </div>
            <?php endforeach; ?>
          </div>

          <div class="cart-actions">
            <button class="btn ghost" type="submit" name="action" value="update">Update Cart</button>
            <button class="btn ghost" type="submit" name="action" value="clear">Clear Cart</button>
          </div>
        </form>

        <div class="cart-summary">
          <div>
            <h3>Order Summary</h3>
            <p>Total items: <?php echo $cartCount; ?></p>
            <p class="product-price">Grand Total: ₹<?php echo number_format($total); ?></p>
          </div>
          <div>
            <a class="btn primary" href="checkout.php">Proceed to Checkout</a>
          </div>
        </div>
      <?php endif; ?>
    </div>
  </section>
</main>

<?php require __DIR__ . '/includes/footer.php'; ?>
</body>
</html>
