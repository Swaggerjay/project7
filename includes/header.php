<?php
// includes/header.php
require_once __DIR__ . '/../cart_helpers.php';

$cartCount = cart_count();
$currentPage = basename($_SERVER['PHP_SELF']);
$userName = $_SESSION['user_name'] ?? null;
?>
<header class="site-header">
  <div class="container header-inner">
    <a class="brand" href="index.php">
      <span class="brand-mark">TF</span>
      <span class="brand-text">
        <strong>Tiksha Furnishing</strong>
        <small>Premium Curtain Studio</small>
      </span>
    </a>

    <button class="nav-toggle" aria-expanded="false" aria-label="Menu">
      <span></span><span></span><span></span>
    </button>

    <nav class="site-nav">
      <a href="index.php" <?php echo $currentPage === 'index.php' ? 'class="active"' : ''; ?>>Home</a>
      <a href="products.php" <?php echo $currentPage === 'products.php' ? 'class="active"' : ''; ?>>Products</a>
      <a href="about.php" <?php echo $currentPage === 'about.php' ? 'class="active"' : ''; ?>>About</a>
      <a href="contact.php" <?php echo $currentPage === 'contact.php' ? 'class="active"' : ''; ?>>Contact</a>
    </nav>

    <div class="user-nav">
      <a class="cart-link" href="cart.php" style="display: inline-flex; align-items: center; gap: 6px;">
        <svg viewBox="0 0 24 24" width="20" height="20" stroke="currentColor" stroke-width="2" fill="none">
          <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path>
          <line x1="3" y1="6" x2="21" y2="6"></line>
          <path d="M16 10a4 4 0 0 1-8 0"></path>
        </svg>
        <span>Cart</span>
        <?php if ($cartCount > 0): ?>
          <span class="cart-badge"><?php echo $cartCount; ?></span>
        <?php endif; ?>
      </a>
      <a href="login.php">
        <?php echo $userName ? "Hi, " . htmlspecialchars($userName) : "Login"; ?>
      </a>
      <?php if ($userName): ?>
        <a href="logout.php">Logout</a>
      <?php endif; ?>
    </div>
  </div>
</header>
