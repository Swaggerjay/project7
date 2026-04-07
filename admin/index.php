<?php
require_once __DIR__ . '/admin_session.php';
$next = 'index.php';
require __DIR__ . '/admin_auth.php';

$conn = db();

$orderCount = $conn->query('SELECT COUNT(*) AS total FROM orders')->fetch_assoc()['total'] ?? 0;
$pendingCount = $conn->query("SELECT COUNT(*) AS total FROM orders WHERE status = 'Pending'")->fetch_assoc()['total'] ?? 0;
$userCount = $conn->query('SELECT COUNT(*) AS total FROM users')->fetch_assoc()['total'] ?? 0;
$productCount = $conn->query('SELECT COUNT(*) AS total FROM products')->fetch_assoc()['total'] ?? 0;
$messageCount = $conn->query('SELECT COUNT(*) AS total FROM contact_messages')->fetch_assoc()['total'] ?? 0;
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Admin Dashboard | Tiksha Furnishing</title>
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
      <a href="index.php" class="active">Dashboard</a>
      <a href="orders.php">Orders</a>
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
      <p class="eyebrow">Overview</p>
      <h1>Admin Dashboard</h1>
      <p>Track orders, inventory, and customer activity.</p>
    </div>
  </section>

  <section class="section">
    <div class="container grid cards-2">
      <div class="card">
        <h3>Total Orders</h3>
        <p class="product-price"><?php echo (int) $orderCount; ?></p>
      </div>
      <div class="card">
        <h3>Pending Orders</h3>
        <p class="product-price"><?php echo (int) $pendingCount; ?></p>
      </div>
      <div class="card">
        <h3>Registered Users</h3>
        <p class="product-price"><?php echo (int) $userCount; ?></p>
      </div>
      <div class="card">
        <h3>Products</h3>
        <p class="product-price"><?php echo (int) $productCount; ?></p>
      </div>
      <div class="card">
        <h3>Inbox Messages</h3>
        <p class="product-price"><?php echo (int) $messageCount; ?></p>
      </div>
    </div>
  </section>
</main>

</body>
</html>
