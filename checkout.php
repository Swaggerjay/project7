<?php
require_once __DIR__ . '/session_bootstrap.php';
require_once __DIR__ . '/products_data.php';
require_once __DIR__ . '/cart_helpers.php';

$next = 'checkout.php';
require __DIR__ . '/auth_check.php';

$products = products_all();
$cartCount = cart_count();
$cartData = cart_totals($products);
$lines = $cartData['lines'];
$total = $cartData['total'];

if (empty($lines)) {
    header('Location: cart.php?type=cart&code=empty');
    exit;
}

$stmt = db()->prepare('SELECT full_name, email, phone FROM users WHERE user_id = ? LIMIT 1');
$stmt->bind_param('i', $_SESSION['user_id']);
$stmt->execute();
$stmt->bind_result($full_name, $email, $phone);
$stmt->fetch();
$stmt->close();
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Checkout | Tiksha Furnishing</title>
  <link rel="stylesheet" href="css/styles.css" />
</head>
<body>

<?php require __DIR__ . '/includes/header.php'; ?>

<main>
  <section class="page-hero">
    <div class="container">
      <h1>Checkout</h1>
      <p>Confirm your details and delivery address.</p>
      
      <?php if (($_GET['code'] ?? '') === 'failed'): ?>
      <div class="form-message error" style="margin-top:20px; padding: 15px; border-radius: 8px;">
        Sorry, we couldn't place your order due to a system error. Please try again.
      </div>
      <?php endif; ?>
    </div>
  </section>

  <section class="section">
    <div class="container checkout-grid">
      <div class="form-card">
        <h3 style="margin-bottom:16px;">Shipping Details</h3>
        <form action="place_order.php" method="post">
          <div class="form-grid">
            <div class="form-field">
              <label>Full Name</label>
              <input type="text" name="full_name" value="<?php echo htmlspecialchars($full_name ?? ''); ?>" required />
            </div>
            <div class="form-field">
              <label>Email</label>
              <input type="email" name="email" value="<?php echo htmlspecialchars($email ?? ''); ?>" required />
            </div>
            <div class="form-field">
              <label>Contact Number</label>
              <input type="tel" name="phone" value="<?php echo htmlspecialchars($phone ?? ''); ?>" required />
            </div>
            <div class="form-field">
              <label>Address</label>
              <input type="text" name="address" placeholder="Street / Apartment" required />
            </div>
            <div class="form-field">
              <label>City</label>
              <input type="text" name="city" required />
            </div>
            <div class="form-field">
              <label>State</label>
              <input type="text" name="state" required />
            </div>
          </div>

          <div style="margin-top:24px;">
            <h3 style="margin-bottom:12px;">Payment Method</h3>
            <div class="payment-methods">
              <label class="payment-option selected">
                <input type="radio" name="payment_method" value="Cash on Delivery" checked required />
                <div class="option-info">
                  <span class="option-title">Cash on Delivery</span>
                  <span class="option-desc">Pay with cash upon delivery of your items.</span>
                </div>
              </label>

              <label class="payment-option">
                <input type="radio" name="payment_method" value="UPI / QR Code" required />
                <div class="option-info">
                  <span class="option-title">UPI / QR Code</span>
                  <span class="option-desc">Pay using Google Pay, PhonePe, or any UPI app.</span>
                </div>
              </label>

              <label class="payment-option">
                <input type="radio" name="payment_method" value="Credit / Debit Card" required />
                <div class="option-info">
                  <span class="option-title">Credit / Debit Card</span>
                  <span class="option-desc">Secure payment via all major cards.</span>
                </div>
              </label>
            </div>
          </div>

          <button class="btn primary" type="submit" style="margin-top:24px; width: 100%;">Place Order</button>
        </form>
      </div>

      <div class="card">
        <h3 style="margin-bottom:12px;">Order Summary</h3>
        <?php foreach ($lines as $line): ?>
          <p><?php echo htmlspecialchars($line['product']['name']); ?> × <?php echo $line['qty']; ?> — ₹<?php echo number_format($line['line_total']); ?></p>
        <?php endforeach; ?>
        <p class="product-price" style="margin-top:12px;">Total: ₹<?php echo number_format($total); ?></p>
      </div>
    </div>
  </section>
</main>

<?php require __DIR__ . '/includes/footer.php'; ?>
<script>
  document.querySelectorAll('input[name="payment_method"]').forEach(radio => {
    radio.addEventListener('change', (e) => {
      document.querySelectorAll('.payment-option').forEach(el => el.classList.remove('selected'));
      e.target.closest('.payment-option').classList.add('selected');
    });
  });
</script>
</body>
</html>
