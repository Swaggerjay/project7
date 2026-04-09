<?php
// actions/buy_now.php
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
$product = product_find($productId);

if (!$product) {
    header('Location: /phpcourse/project7/pages/products.php?type=cart&code=invalid_product');
    exit;
}

$_SESSION['cart']['items'] = [
    $productId => 1,
];

require_once __DIR__ . '/../core/cart_helpers.php';
cart_sync_to_db();

header('Location: /phpcourse/project7/actions/checkout.php');
exit;
