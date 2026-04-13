<?php
// admin/includes/admin_header.php
$adminName = $adminName ?? 'Admin';
$currentPage = basename($_SERVER['PHP_SELF']);
$pageTitle = $pageTitle ?? 'Admin Dashboard';
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title><?php echo htmlspecialchars($pageTitle); ?> | Tiksha Furnishing</title>
  <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="../css/styles.css" />
  <link rel="stylesheet" href="css/admin_styles.css" />
</head>
<body>

<!-- SIDEBAR -->
<aside class="admin-sidebar">
    <div class="sidebar-header">
        <a href="index.php" style="text-decoration: none;">
            <span class="brand-mark">TF</span>
            <span class="brand-text">Admin Panel</span>
        </a>
    </div>
    
    <nav class="sidebar-nav">
        <a href="index.php" <?php echo $currentPage === 'index.php' ? 'class="active"' : ''; ?>>
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect></svg>
            Dashboard
        </a>
        <a href="orders.php" <?php echo $currentPage === 'orders.php' ? 'class="active"' : ''; ?>>
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="9" cy="21" r="1"></circle><circle cx="20" cy="21" r="1"></circle><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path></svg>
            Orders
        </a>
        <a href="products.php" <?php echo $currentPage === 'products.php' ? 'class="active"' : ''; ?>>
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"></path><line x1="7" y1="7" x2="7.01" y2="7"></line></svg>
            Products
        </a>
        <a href="users.php" <?php echo $currentPage === 'users.php' ? 'class="active"' : ''; ?>>
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
            Customers
        </a>
        <a href="messages.php" <?php echo $currentPage === 'messages.php' ? 'class="active"' : ''; ?>>
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
            Messages
        </a>
    </nav>
    
    <div class="sidebar-footer">
        <a href="../index.php" target="_blank">View Live Store &rarr;</a>
    </div>
</aside>

<!-- MAIN CONTENT -->
<main class="admin-main">
    <header class="admin-topbar">
        <div class="topbar-title"><?php echo htmlspecialchars($pageTitle); ?></div>
        <div class="topbar-user">
            <span>Hi, <strong><?php echo htmlspecialchars($adminName); ?></strong></span>
            <a href="logout.php" class="btn-logout">Logout</a>
        </div>
    </header>
    <div class="admin-content">
