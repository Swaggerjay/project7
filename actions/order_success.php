<?php
// actions/order_success.php
require_once __DIR__ . '/../config/session_bootstrap.php';
require_once __DIR__ . '/../core/cart_helpers.php';

$next = '/phpcourse/project7/pages/products.php';
require __DIR__ . '/../core/auth_check.php';

$orderId = (int) ($_GET['order_id'] ?? 0);
if ($orderId < 1) {
    header('Location: /phpcourse/project7/pages/products.php');
    exit;
}

$cartCount = cart_count();

$stmt = db()->prepare('SELECT order_id, full_name, email, phone, address, city, state, total_amount, payment_method, created_at FROM orders WHERE order_id = ? AND user_id = ? LIMIT 1');
$stmt->bind_param('ii', $orderId, $_SESSION['user_id']);
$stmt->execute();
$stmt->bind_result($o_id, $o_name, $o_email, $o_phone, $o_address, $o_city, $o_state, $o_total, $o_pay, $o_created);
$orderFound = $stmt->fetch();
$stmt->close();

if (!$orderFound) {
    header('Location: /phpcourse/project7/pages/products.php');
    exit;
}

$order = [
    'order_id'       => $o_id,
    'full_name'      => $o_name,
    'email'          => $o_email,
    'phone'          => $o_phone,
    'address'        => $o_address,
    'city'           => $o_city,
    'state'          => $o_state,
    'total_amount'   => $o_total,
    'payment_method' => $o_pay,
    'created_at'     => $o_created,
];

$itemsStmt = db()->prepare('SELECT product_name, product_price, quantity, line_total FROM order_items WHERE order_id = ?');
$itemsStmt->bind_param('i', $orderId);
$itemsStmt->execute();
$itemsStmt->bind_result($i_name, $i_price, $i_qty, $i_total);
$items = [];
while ($itemsStmt->fetch()) {
    $items[] = [
        'product_name'  => $i_name,
        'product_price' => $i_price,
        'quantity'      => $i_qty,
        'line_total'    => $i_total,
    ];
}
$itemsStmt->close();
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Order Confirmed | Tiksha Furnishing</title>
  <link rel="stylesheet" href="/phpcourse/project7/css/styles.css" />
</head>
<body>

<?php $showConfirm = (($_GET['confirmed'] ?? '') === '1'); ?>
<?php if ($showConfirm): ?>
  <div class="modal-overlay is-visible" role="dialog" aria-modal="true" aria-labelledby="order-confirm-title">
    <div class="modal-card">
      <div class="modal-icon">✓</div>
      <h3 id="order-confirm-title">Order Confirmed</h3>
      <p>Your order is confirmed. We have saved your shipping details and items.</p>
      <button class="btn primary modal-close" type="button">Continue</button>
    </div>
  </div>
<?php endif; ?>

<?php require __DIR__ . '/../includes/header.php'; ?>

<main>
  <section class="page-hero">
    <div class="container">
      <h1>Order Confirmed</h1>
      <p>Your order has been placed successfully.</p>
    </div>
  </section>

  <section class="section">
    <div class="container grid cards-2">
      <div class="card">
        <h3>Order Details</h3>
        <p>Order ID: #<?php echo $order['order_id']; ?></p>
        <p>Placed On: <?php echo htmlspecialchars($order['created_at']); ?></p>
        <p>Total: ₹<?php echo number_format($order['total_amount']); ?></p>
        <p>Payment: <?php echo htmlspecialchars($order['payment_method']); ?></p>
      </div>

      <div class="card">
        <h3>Shipping To</h3>
        <p><?php echo htmlspecialchars($order['full_name']); ?></p>
        <p><?php echo htmlspecialchars($order['email']); ?></p>
        <p><?php echo htmlspecialchars($order['phone']); ?></p>
        <p><?php echo htmlspecialchars($order['address']); ?></p>
        <p><?php echo htmlspecialchars($order['city']); ?>, <?php echo htmlspecialchars($order['state']); ?></p>
      </div>
    </div>

    <div class="container" style="margin-top:30px;">
      <div class="card">
        <h3>Items</h3>
        <?php foreach ($items as $item): ?>
          <p><?php echo htmlspecialchars($item['product_name']); ?> × <?php echo (int) $item['quantity']; ?> — ₹<?php echo number_format($item['line_total']); ?></p>
        <?php endforeach; ?>
      </div>
      <div style="margin-top:18px;">
        <a class="btn primary" href="/phpcourse/project7/pages/products.php">Continue Shopping</a>
      </div>
    </div>
  </section>
</main>

<?php require __DIR__ . '/../includes/footer.php'; ?>
<?php if ($showConfirm): ?>
<script>
  const overlay = document.querySelector('.modal-overlay');
  const closeBtn = document.querySelector('.modal-close');
  if (overlay && closeBtn) {
    closeBtn.addEventListener('click', () => overlay.classList.remove('is-visible'));
    overlay.addEventListener('click', (e) => {
      if (e.target === overlay) overlay.classList.remove('is-visible');
    });
  }
</script>
<?php endif; ?>

</body>
</html>
