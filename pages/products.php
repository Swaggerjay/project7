<?php
require_once __DIR__ . '/../config/session_bootstrap.php';
require_once __DIR__ . '/../config/db_connect.php';
require_once __DIR__ . '/products_data.php';
require_once __DIR__ . '/../core/cart_helpers.php';

$all_products = products_all();
$cartCount = cart_count();

// Search logic
$search = trim($_GET['search'] ?? '');
if ($search !== '') {
    $all_products = array_filter($all_products, function($p) use ($search) {
        return stripos($p['name'], $search) !== false || stripos($p['category'] ?? '', $search) !== false;
    });
}

// Pagination logic
$page = max(1, (int)($_GET['page'] ?? 1));
$perPage = 6;
$total = count($all_products);
$totalPages = ceil($total / $perPage);
$page = min($page, max(1, $totalPages));
$offset = ($page - 1) * $perPage;
$products = array_slice($all_products, $offset, $perPage);

// Wishlist logic
$wishlistIds = [];
if (isset($_SESSION['user_id'])) {
    $c = db();
    $stmt = $c->prepare("SELECT product_id FROM wishlist WHERE user_id = ?");
    $stmt->bind_param('i', $_SESSION['user_id']);
    $stmt->execute();
    $res = $stmt->get_result();
    while ($r = $res->fetch_assoc()) {
        $wishlistIds[] = (int)$r['product_id'];
    }
    $stmt->close();
}
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

  <section class="section">
    <div class="container">
      <?php if ($search !== ''): ?>
        <p style="margin-bottom: 20px;">Showing results for: <strong><?php echo htmlspecialchars($search); ?></strong></p>
      <?php endif; ?>

      <div class="grid cards-3">
        <?php foreach ($products as $product): ?>
          <div class="product-card">
            
            <form action="/phpcourse/project7/actions/wishlist_action.php" method="POST">
              <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
              <?php $inWishlist = in_array($product['id'], $wishlistIds); ?>
              <button class="wishlist-btn <?php echo $inWishlist ? 'active' : ''; ?>" title="<?php echo $inWishlist ? 'Remove from wishlist' : 'Add to wishlist'; ?>">
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
      
      <?php if(empty($products)): ?>
          <div class="text-center" style="padding: 40px 0;">
             <h3>No products found.</h3>
             <a href="products.php" class="btn primary">Clear Search</a>
          </div>
      <?php endif; ?>

      <!-- Pagination UI -->
      <?php if ($totalPages > 1): ?>
      <div class="pagination">
        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
          <?php 
            $queryParams = $_GET;
            $queryParams['page'] = $i;
            $link = '?' . http_build_query($queryParams);
          ?>
          <a href="<?php echo htmlspecialchars($link); ?>" class="page-link <?php echo $i === $page ? 'active' : ''; ?>"><?php echo $i; ?></a>
        <?php endfor; ?>
      </div>
      <?php endif; ?>

    </div>
  </section>
</main>

<?php require __DIR__ . '/../includes/footer.php'; ?>
</body>
</html>
