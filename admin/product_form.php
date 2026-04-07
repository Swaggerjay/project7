<?php
require_once __DIR__ . '/admin_session.php';
$next = 'products.php';
require __DIR__ . '/admin_auth.php';

$productId = (int) ($_GET['product_id'] ?? 0);
$product = [
    'product_id' => 0,
    'name' => '',
    'price' => '',
    'image_path' => '',
];

if ($productId > 0) {
    $stmt = db()->prepare('SELECT product_id, name, price, image_path FROM products WHERE product_id = ? LIMIT 1');
    $stmt->bind_param('i', $productId);
    $stmt->execute();
    $stmt->bind_result($p_id, $p_name, $p_price, $p_image);
    if ($stmt->fetch()) {
        $product = [
            'product_id' => $p_id,
            'name' => $p_name,
            'price' => $p_price,
            'image_path' => $p_image,
        ];
    }
    $stmt->close();
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title><?php echo $product['product_id'] ? 'Edit' : 'Add'; ?> Product | Admin</title>
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
      <h1><?php echo $product['product_id'] ? 'Edit Product' : 'Add Product'; ?></h1>
      <p>Update your product catalog.</p>
    </div>
  </section>

  <section class="section">
    <div class="container">
      <div class="form-card">
        <form action="product_action.php" method="post">
          <input type="hidden" name="product_id" value="<?php echo (int) $product['product_id']; ?>" />
          <input type="hidden" name="action" value="<?php echo $product['product_id'] ? 'update' : 'create'; ?>" />
          <div class="form-grid">
            <div class="form-field">
              <label>Name</label>
              <input type="text" name="name" value="<?php echo htmlspecialchars($product['name']); ?>" required />
            </div>
            <div class="form-field">
              <label>Price</label>
              <input type="number" step="0.01" name="price" value="<?php echo htmlspecialchars((string) $product['price']); ?>" required />
            </div>
            <div class="form-field">
              <label>Image Path</label>
              <input type="text" name="image_path" value="<?php echo htmlspecialchars($product['image_path']); ?>" placeholder="images/real-2.jpg" required />
            </div>
          </div>
          <button class="btn primary" type="submit" style="margin-top:16px;">Save Product</button>
        </form>
      </div>
    </div>
  </section>
</main>

</body>
</html>
