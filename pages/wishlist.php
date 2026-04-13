<?php
// pages/wishlist.php
require_once __DIR__ . '/../config/session_bootstrap.php';
require_once __DIR__ . '/../config/db_connect.php';
require_once __DIR__ . '/products_data.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: /phpcourse/project7/auth/login.php?type=login&code=login_required');
    exit;
}

$userId = $_SESSION['user_id'];
$c = db();
$stmt = $c->prepare("SELECT product_id FROM wishlist WHERE user_id = ? ORDER BY created_at DESC");
$stmt->bind_param('i', $userId);
$stmt->execute();
$result = $stmt->get_result();

$wishlistIds = [];
while ($row = $result->fetch_assoc()) {
    $wishlistIds[] = (int)$row['product_id'];
}
$stmt->close();

$allProducts = products_all();
$wishlistProducts = [];
foreach ($wishlistIds as $pid) {
    if (isset($allProducts[$pid])) {
        $wishlistProducts[] = $allProducts[$pid];
    }
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>My Wishlist | Tiksha Furnishing</title>
  <link rel="stylesheet" href="/phpcourse/project7/css/styles.css" />
</head>
<body>

<?php require __DIR__ . '/../includes/header.php'; ?>

<main>
  <section class="page-hero">
    <div class="container text-center">
      <h1>My Wishlist</h1>
      <p>Items you've saved for later.</p>
    </div>
  </section>

  <section class="section">
    <div class="container">
      <?php if (empty($wishlistProducts)): ?>
        <div class="card text-center" style="padding: 60px 20px;">
          <h3>Your wishlist is empty</h3>
          <p style="margin: 15px 0;">Find something you love and save it for later.</p>
          <a href="/phpcourse/project7/pages/products.php" class="btn primary">Discover Products</a>
        </div>
      <?php else: ?>
        <div class="grid cards-3">
          <?php foreach ($wishlistProducts as $product): ?>
            <div class="product-card">
              <form action="/phpcourse/project7/actions/wishlist_action.php" method="POST">
                <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                <button class="wishlist-btn active" title="Remove from wishlist" style="top: 15px; right: 15px;">
                  <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor" stroke="currentColor" stroke-width="2">
                    <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
                  </svg>
                </button>
              </form>
              <a href="product_details.php?id=<?php echo $product['id']; ?>" class="product-card-link">
                <img src="<?php echo htmlspecialchars($product['image']); ?>" alt="">
                <h3><?php echo htmlspecialchars($product['name']); ?></h3>
              </a>
              <p class="product-price">₹<?php echo number_format($product['price']); ?></p>
              <div class="product-actions" style="margin-top: auto;">
                <form action="/phpcourse/project7/actions/add_to_cart.php" method="post">
                  <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>" />
                  <input type="hidden" name="qty" value="1" />
                  <button class="btn primary" type="submit" style="width: 100%;">Add to Cart</button>
                </form>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
    </div>
  </section>
</main>

<?php require __DIR__ . '/../includes/footer.php'; ?>
</body>
</html>
