<?php
// actions/add_to_cart.php
require_once __DIR__ . '/../config/session_bootstrap.php';
require_once __DIR__ . '/../pages/products_data.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: /phpcourse/project7/auth/login.php?type=login&code=login_required&next=/phpcourse/project7/pages/products.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /phpcourse/project7/pages/products.php');
    exit;
}

$productId = (int) ($_POST['product_id'] ?? 0);
$qty = (int) ($_POST['qty'] ?? 1);
$product = product_find($productId);

if (!$product) {
    header('Location: /phpcourse/project7/pages/products.php?type=cart&code=invalid_product');
    exit;
}

$qty = max(1, min(10, $qty));

if (!isset($_SESSION['cart']['items'])) {
    $_SESSION['cart']['items'] = [];
}

$_SESSION['cart']['items'][$productId] = ($_SESSION['cart']['items'][$productId] ?? 0) + $qty;

require_once __DIR__ . '/../core/cart_helpers.php';
cart_sync_to_db();

// Redirect back to where the user came from, or products.php by default
$redirect = $_POST['redirect'] ?? $_SERVER['HTTP_REFERER'] ?? '/phpcourse/project7/pages/products.php';
if (strpos($redirect, 'added=1') === false) {
    $redirect .= (strpos($redirect, '?') === false ? '?' : '&') . 'added=1';
}

header("Location: $redirect");
exit;
