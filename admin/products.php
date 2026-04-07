<?php
require_once __DIR__ . '/admin_session.php';
$next = 'products.php';
require __DIR__ . '/admin_auth.php';

$stmt = db()->prepare('SELECT product_id, name, price, image_path, created_at FROM products ORDER BY created_at DESC');
$stmt->execute();
$stmt->bind_result($p_id, $p_name, $p_price, $p_image, $p_created);
$products = [];
while ($stmt->fetch()) {
    $products[] = [
        'product_id' => $p_id,
        'name' => $p_name,
        'price' => $p_price,
        'image' => $p_image,
        'created_at' => $p_created,
    ];
}
$stmt->close();
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Products | Admin</title>
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
      <a href="orders.php">Orders</a>
      <a href="products.php" class="active">Products</a>
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
      <h1>Products</h1>
      <p>Manage your catalog.</p>
    </div>
  </section>

  <section class="section">
    <div class="container">
      <div style="margin-bottom:16px;">
        <a class="btn primary" href="product_form.php">Add Product</a>
      </div>
      <div class="cart-table">
        <div class="cart-row cart-head">
          <span>ID</span>
          <span>Name</span>
          <span>Price</span>
          <span>Image</span>
          <span>Action</span>
        </div>
        <?php foreach ($products as $product): ?>
          <div class="cart-row">
            <span><?php echo (int) $product['product_id']; ?></span>
            <span><?php echo htmlspecialchars($product['name']); ?></span>
            <span>₹<?php echo number_format($product['price']); ?></span>
            <span><?php echo htmlspecialchars($product['image']); ?></span>
            <span>
              <a class="btn ghost" href="product_form.php?product_id=<?php echo (int) $product['product_id']; ?>">Edit</a>
              <form action="product_action.php" method="post" style="display:inline-flex;">
                <input type="hidden" name="product_id" value="<?php echo (int) $product['product_id']; ?>" />
                <input type="hidden" name="action" value="delete" />
                <button class="btn ghost" type="submit">Delete</button>
              </form>
            </span>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </section>
</main>

</body>
</html>
