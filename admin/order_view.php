<?php
require_once __DIR__ . '/admin_session.php';
$next = 'orders.php';
require __DIR__ . '/admin_auth.php';

$orderId = (int) ($_GET['order_id'] ?? 0);
if ($orderId < 1) {
    header('Location: orders.php');
    exit;
}

$stmt = db()->prepare('SELECT order_id, full_name, email, phone, address, city, state, status, total_amount, created_at FROM orders WHERE order_id = ? LIMIT 1');
$stmt->bind_param('i', $orderId);
$stmt->execute();
$stmt->bind_result($o_id, $o_name, $o_email, $o_phone, $o_address, $o_city, $o_state, $o_status, $o_total, $o_created);
$found = $stmt->fetch();
$stmt->close();

if (!$found) {
    header('Location: orders.php');
    exit;
}

$itemsStmt = db()->prepare('SELECT product_name, product_price, quantity, line_total FROM order_items WHERE order_id = ?');
$itemsStmt->bind_param('i', $orderId);
$itemsStmt->execute();
$itemsStmt->bind_result($i_name, $i_price, $i_qty, $i_total);
$items = [];
while ($itemsStmt->fetch()) {
    $items [] = [
        'product_name' => $i_name,
        'product_price' => $i_price,
        'quantity' => $i_qty,
        'line_total' => $i_total,
    ];
}
$itemsStmt->close();

$statusOptions = ['Pending', 'Confirmed', 'Shipped', 'Delivered', 'Cancelled'];
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Order #<?php echo $o_id; ?> | Admin</title>
  <link rel="stylesheet" href="../css/styles.css" />
</head>
<body>

<header class="site-header">
  <div class="container header-inner">
    <a class="brand" href="index.php">
      <span class="brand-mark">TF</span>
      <span class="brand-text"><strong>Admin Panel</strong></span>
    </a>
    <nav class="site-nav">
      <a href="index.php">Dashboard</a>
      <a href="orders.php" class="active">Orders</a>
      <a href="products.php">Products</a>
      <a href="users.php">Users</a>
      <a href="messages.php">Messages</a>
    </nav>
    <div class="user-nav">
      <span>Hi, <?php echo htmlspecialchars($adminName ?? 'Admin'); ?></span>
      <a href="logout.php">Logout</a>
    </div>
  </div>
</header>

<main>
  <section class="page-hero">
    <div class="container">
      <h1>Order #<?php echo $o_id; ?></h1>
      <p>Placed on <?php echo htmlspecialchars($o_created); ?></p>
    </div>
  </section>

  <section class="section">
    <div class="container grid cards-2">
      <div class="card">
        <h3>Customer</h3>
        <p><?php echo htmlspecialchars($o_name); ?></p>
        <p><?php echo htmlspecialchars($o_email); ?></p>
        <p><?php echo htmlspecialchars($o_phone); ?></p>
        <p><?php echo htmlspecialchars($o_address); ?></p>
        <p><?php echo htmlspecialchars($o_city); ?>, <?php echo htmlspecialchars($o_state); ?></p>
      </div>

      <div class="card">
        <h3>Order Summary</h3>
        <?php foreach ($items as $item): ?>
          <p><?php echo htmlspecialchars($item['product_name']); ?> × <?php echo (int) $item['quantity']; ?> — ₹<?php echo number_format($item['line_total']); ?></p>
        <?php endforeach; ?>
        <p class="product-price" style="margin-top:12px;">Total: ₹<?php echo number_format($o_total); ?></p>
      </div>
    </div>

    <div class="container" style="margin-top:24px;">
      <div class="card">
        <h3>Update Status</h3>
        <form action="order_action.php" method="post">
          <input type="hidden" name="order_id" value="<?php echo (int) $o_id; ?>" />
          <input type="hidden" name="action" value="save_status" />
          <div class="form-grid">
            <div class="form-field">
              <label>Status</label>
              <select name="status" class="cart-qty" style="width:200px;">
                <?php foreach ($statusOptions as $status): ?>
                  <option value="<?php echo $status; ?>" <?php echo $status === $o_status ? 'selected' : ''; ?>>
                    <?php echo $status; ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>
          <button class="btn primary" type="submit" style="margin-top:12px;">Save Status</button>
        </form>
      </div>

      <div class="card" style="margin-top:20px; border-top: 1px solid rgba(176, 70, 45, 0.2);">
        <h3 style="color: #b0462d;">Danger Zone</h3>
        <p style="font-size: 0.9rem; margin-bottom: 12px;">Deleting an order is permanent and cannot be undone.</p>
        <form action="order_action.php" method="post">
          <input type="hidden" name="order_id" value="<?php echo (int) $o_id; ?>" />
          <input type="hidden" name="action" value="delete" />
          <button class="btn ghost" type="submit" onclick="return confirm('Permanently delete this order?')" style="border-color: #b0462d; color: #b0462d;">Delete Order</button>
        </form>
      </div>
    </div>
  </section>
</main>

</body>
</html>
