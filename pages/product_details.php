<?php
require_once __DIR__ . '/../config/session_bootstrap.php';
require_once __DIR__ . '/../config/db_connect.php';
require_once __DIR__ . '/products_data.php';

$productId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$product = product_find($productId);

if (!$product) {
    header('Location: /phpcourse/project7/pages/products.php');
    exit;
}

$specs = $product['specifications'] ?? [];
$gallery = $product['gallery_images'] ?? [];

$c = db();
// Wishlist state
$inWishlist = false;
if (isset($_SESSION['user_id'])) {
    $stmtW = $c->prepare("SELECT id FROM wishlist WHERE user_id = ? AND product_id = ?");
    $stmtW->bind_param('ii', $_SESSION['user_id'], $productId);
    $stmtW->execute();
    $stmtW->store_result();
    $inWishlist = $stmtW->num_rows > 0;
    $stmtW->close();
}

// Fetch reviews
$stmtRev = $c->prepare("SELECT r.rating, r.comment, r.created_at, u.full_name FROM reviews r JOIN users u ON r.user_id = u.user_id WHERE r.product_id = ? ORDER BY r.created_at DESC");
$stmtRev->bind_param('i', $productId);
$stmtRev->execute();
$revRes = $stmtRev->get_result();
$reviewsList = [];
$totalRating = 0;
while ($row = $revRes->fetch_assoc()) {
    $reviewsList[] = $row;
    $totalRating += $row['rating'];
}
$stmtRev->close();
$revCount = count($reviewsList);
$avgRating = $revCount > 0 ? round($totalRating / $revCount, 1) : 0;
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title><?php echo htmlspecialchars($product['name']); ?> | Tiksha Furnishing</title>
  <link rel="stylesheet" href="/phpcourse/project7/css/styles.css" />
</head>
<body class="product-detail-page">

  <div id="page-loader" class="page-loader">
    <div class="loader-ring"></div>
  </div>

  <?php require __DIR__ . '/../includes/header.php'; ?>

  <main class="container detail-container">
    
    <nav class="breadcrumbs reveal-up">
        <a href="/phpcourse/project7/">Home</a> / 
        <a href="/phpcourse/project7/pages/products.php">Products</a> / 
        <span><?php echo htmlspecialchars($product['category'] ?? 'General'); ?></span>
    </nav>

    <div class="detail-grid">
        
        <!-- LEFT: IMAGE GALLERY -->
        <div class="gallery-section reveal-left">
            <div class="main-image-container glass" style="position: relative;">
                <form action="/phpcourse/project7/actions/wishlist_action.php" method="POST">
                  <input type="hidden" name="product_id" value="<?php echo $productId; ?>">
                  <button class="wishlist-btn <?php echo $inWishlist ? 'active' : ''; ?>" title="Wishlist">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor" stroke="currentColor" stroke-width="2"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path></svg>
                  </button>
                </form>
                <img id="main-image" src="<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
                <div class="image-zoom-hint">Hover to Zoom</div>
            </div>
            <div class="gallery-thumbs">
                <img class="gallery-thumb active" src="<?php echo htmlspecialchars($product['image']); ?>" onclick="document.getElementById('main-image').src=this.src; document.querySelectorAll('.gallery-thumb').forEach(e=>e.classList.remove('active')); this.classList.add('active');">
                <?php foreach($gallery as $galImg): ?>
                    <img class="gallery-thumb" src="<?php echo htmlspecialchars($galImg); ?>" onclick="document.getElementById('main-image').src=this.src; document.querySelectorAll('.gallery-thumb').forEach(e=>e.classList.remove('active')); this.classList.add('active');">
                <?php endforeach; ?>
            </div>
        </div>

        <!-- RIGHT: PRODUCT INFO -->
        <div class="info-section reveal-right">
            <p class="eyebrow"><?php echo htmlspecialchars($product['category'] ?? 'Premium Collection'); ?></p>
            <h1><?php echo htmlspecialchars($product['name']); ?></h1>
            
            <div class="rating-strip">
                <span class="stars"><?php echo str_repeat('★', round($avgRating)) . str_repeat('☆', 5 - round($avgRating)); ?></span>
                <span class="review-count">(<?php echo $revCount; ?> Reviews)</span>
            </div>

            <div class="price-strip">
                <span class="current-price">₹<?php echo number_format($product['price'], 2); ?></span>
            </div>

            <div class="short-desc">
                <p><?php echo nl2br(htmlspecialchars($product['description'] ?? 'No description available.')); ?></p>
            </div>

            <div class="action-buttons desktop-only" style="margin-top: 30px;">
                <form action="/phpcourse/project7/actions/add_to_cart.php" method="post" style="flex: 1;">
                    <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                    <input type="hidden" name="qty" value="1">
                    <button type="submit" class="btn primary large" style="width: 100%;">ADD TO CART</button>
                </form>
            </div>

            <!-- SPECS TABLE -->
            <div class="specs-section reveal-up" style="margin-top:40px;">
                <h3>Technical Specifications</h3>
                <table class="specs-table glass">
                    <?php if (!empty($specs)): ?>
                        <?php foreach ($specs as $key => $value): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($key); ?></td>
                                <td><?php echo htmlspecialchars($value); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="2">Standard Curtains Specification</td></tr>
                    <?php endif; ?>
                </table>
            </div>
        </div>
    </div>

    <!-- REVIEWS SECTION -->
    <section class="section reveal-up text-left" style="max-width: 800px; margin: 0 auto; padding-top: 40px;">
        <h2 style="font-family: 'Playfair Display', serif; border-bottom: 2px solid var(--gold); padding-bottom: 10px; margin-bottom: 30px;">Customer Reviews</h2>
        
        <?php if ($revCount > 0): ?>
            <div class="review-list" style="margin-bottom: 40px;">
                <?php foreach ($reviewsList as $rev): ?>
                    <div class="review-item" style="border-bottom: 1px solid #ccc; padding: 15px 0;">
                        <div style="display:flex; justify-content:space-between; align-items:center;">
                            <strong><?php echo htmlspecialchars($rev['full_name']); ?></strong>
                            <span class="star-rating"><?php echo str_repeat('★', $rev['rating']) . str_repeat('☆', 5 - $rev['rating']); ?></span>
                        </div>
                        <p style="margin-top: 8px; font-size: 0.95rem;"><?php echo nl2br(htmlspecialchars($rev['comment'])); ?></p>
                        <small style="color: #888;"><?php echo date('M d, Y', strtotime($rev['created_at'])); ?></small>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p>No reviews yet. Be the first to review this product!</p>
        <?php endif; ?>

        <!-- Leave a Review Form -->
        <div class="card" style="box-shadow: none; border: 1px solid rgba(0,0,0,0.1); margin-top: 40px;">
            <h3>Write a Review</h3>
            <?php if (isset($_SESSION['user_id'])): ?>
                <form action="/phpcourse/project7/actions/review_action.php" method="POST" style="margin-top: 20px;">
                    <input type="hidden" name="product_id" value="<?php echo $productId; ?>">
                    <div style="margin-bottom: 15px;">
                        <label>Rating</label><br>
                        <select name="rating" style="padding: 8px; width: 100px; border-radius: 5px;">
                            <option value="5">5 Stars</option>
                            <option value="4">4 Stars</option>
                            <option value="3">3 Stars</option>
                            <option value="2">2 Stars</option>
                            <option value="1">1 Star</option>
                        </select>
                    </div>
                    <div style="margin-bottom: 15px;">
                        <label>Comment</label><br>
                        <textarea name="comment" rows="4" style="width: 100%; padding: 10px; border-radius: 5px; border: 1px solid #ccc;" required placeholder="Share your experience..."></textarea>
                    </div>
                    <button type="submit" class="btn primary">Submit Review</button>
                </form>
            <?php else: ?>
                <p>Please <a href="/phpcourse/project7/auth/login.php" style="color: var(--gold); text-decoration:underline;">login</a> to leave a review.</p>
            <?php endif; ?>
        </div>
    </section>

  </main>

  <div class="sticky-actions reveal mobile-only">
    <form action="/phpcourse/project7/actions/add_to_cart.php" method="post" style="flex: 1;">
        <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
        <input type="hidden" name="qty" value="1">
        <button type="submit" class="btn primary" style="width: 100%;">ADD TO CART</button>
    </form>
  </div>

  <?php require __DIR__ . '/../includes/footer.php'; ?>
  <script src="/phpcourse/project7/js/main.js"></script>
</body>
</html>
