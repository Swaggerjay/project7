<?php
require_once __DIR__ . '/admin_session.php';
$next = 'orders.php';
require __DIR__ . '/admin_auth.php';

$stmt = db()->prepare('SELECT order_id, full_name, email, phone, city, state, status, total_amount, created_at FROM orders ORDER BY created_at DESC');
$stmt->execute();
$stmt->bind_result($o_id, $o_name, $o_email, $o_phone, $o_city, $o_state, $o_status, $o_total, $o_created);
$orders = [];
while ($stmt->fetch()) {
    $orders[] = [
        'order_id' => $o_id,
        'full_name' => $o_name,
        'email' => $o_email,
        'phone' => $o_phone,
        'city' => $o_city,
        'state' => $o_state,
        'status' => $o_status,
        'total' => $o_total,
        'created_at' => $o_created,
    ];
}
$stmt->close();
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Orders | Admin</title>
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
      <h1>Orders</h1>
      <p>Review and manage customer orders.</p>
    </div>
  </section>

  <section class="section">
    <div class="container">
      <div class="cart-table">
        <div class="cart-row cart-head">
          <span>Order</span>
          <span>Customer</span>
          <span>Status</span>
          <span>Total</span>
          <span>Date</span>
        </div>
        <?php foreach ($orders as $order): ?>
          <div class="cart-row">
            <span><a href="order_view.php?order_id=<?php echo (int) $order['order_id']; ?>">#<?php echo (int) $order['order_id']; ?></a></span>
            <span><?php echo htmlspecialchars($order['full_name']); ?></span>
            <span><?php echo htmlspecialchars($order['status']); ?></span>
            <span>₹<?php echo number_format($order['total']); ?></span>
            <span><?php echo htmlspecialchars($order['created_at']); ?></span>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </section>
</main>

</body>
</html>
