<?php
// ==============================
// HEADER FILE (includes/header.php)
// ==============================

// Include cart helper file (for cart functions)
require_once __DIR__ . '/../core/cart_helpers.php';

// Get total number of items in cart
$cartCount = cart_count();

// Get current page name (used for active menu highlight)
$currentPage = basename($_SERVER['PHP_SELF']);

// Get logged-in user's name from session (if available)
$userName = $_SESSION['user_name'] ?? null;

// Base URL (used to avoid repeating long paths)
$BASE = '/phpcourse/project7';
?>

<!-- ==============================
     HEADER START
============================== -->
<header class="site-header">
  <div class="container header-inner">

    <!-- ===== LOGO / BRAND ===== -->
    <a class="brand" href="<?php echo $BASE; ?>/">
      <span class="brand-mark">TF</span>
      <span class="brand-text">
        <strong>Tiksha Furnishing</strong>
        <small>Premium Curtain Studio</small>
      </span>
    </a>

    <!-- ===== MOBILE MENU BUTTON ===== -->
    <button class="nav-toggle" aria-expanded="false" aria-label="Menu">
      <span></span><span></span><span></span>
    </button>

    <!-- ===== NAVIGATION MENU ===== -->
    <nav class="site-nav">
      <a href="<?php echo $BASE; ?>/" <?php echo $currentPage === 'index.php' ? 'class="active"' : ''; ?>>Home</a>
      <a href="<?php echo $BASE; ?>/pages/products.php" <?php echo $currentPage === 'products.php' ? 'class="active"' : ''; ?>>Products</a>
      <a href="<?php echo $BASE; ?>/pages/about.php" <?php echo $currentPage === 'about.php' ? 'class="active"' : ''; ?>>About</a>
      <a href="<?php echo $BASE; ?>/pages/contact.php" <?php echo $currentPage === 'contact.php' ? 'class="active"' : ''; ?>>Contact</a>
    </nav>

    <!-- ===== USER SECTION (Search + Wishlist + Cart + Login) ===== -->
    <div class="user-nav">
      
      <!-- ===== SEARCH BAR ===== -->
      <form action="<?php echo $BASE; ?>/pages/products.php" method="GET" class="header-search">
        <input type="text" name="search" placeholder="Search products..." value="<?php echo htmlspecialchars($_GET['search'] ?? ''); ?>">
        <button type="submit" aria-label="Search">
          <svg viewBox="0 0 24 24" width="18" height="18" stroke="currentColor" stroke-width="2" fill="none">
            <circle cx="11" cy="11" r="8"></circle>
            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
          </svg>
        </button>
      </form>

      <!-- ===== WISHLIST LINK ===== -->
      <?php if ($userName): ?>
      <a href="<?php echo $BASE; ?>/pages/wishlist.php" title="My Wishlist" style="display: inline-flex; align-items: center; color: var(--gold);">
        <svg viewBox="0 0 24 24" width="22" height="22" stroke="currentColor" stroke-width="2" fill="none">
          <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
        </svg>
      </a>
      <?php endif; ?>

      <!-- ===== CART LINK ===== -->
      <a class="cart-link" href="<?php echo $BASE; ?>/pages/cart.php" style="display: inline-flex; align-items: center; gap: 6px;">
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

      <!-- ===== LOGIN / USER SECTION ===== -->
      <?php if ($userName): ?>
        <a href="<?php echo $BASE; ?>/pages/profile.php">My Profile</a>
        <a href="<?php echo $BASE; ?>/auth/logout.php">Logout</a>
      <?php else: ?>
        <a href="<?php echo $BASE; ?>/auth/login.php">Login</a>
      <?php endif; ?>
    </div>

  </div>
</header>
<!-- ==============================
     HEADER END
============================== -->