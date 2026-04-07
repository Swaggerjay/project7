<?php
require_once __DIR__ . '/session_bootstrap.php';
require_once __DIR__ . '/products_data.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php?type=login&code=login_required&next=products.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: products.php');
    exit;
}

$productId = (int) ($_POST['product_id'] ?? 0);
$qty = (int) ($_POST['qty'] ?? 1);
$product = product_find($productId);

if (!$product) {
    header('Location: products.php?type=cart&code=invalid_product');
    exit;
}

$qty = max(1, min(10, $qty));

if (!isset($_SESSION['cart']['items'])) {
    $_SESSION['cart']['items'] = [];
}

$_SESSION['cart']['items'][$productId] = ($_SESSION['cart']['items'][$productId] ?? 0) + $qty;

require_once __DIR__ . '/cart_helpers.php';
cart_sync_to_db();

header('Location: products.php?added=1');
exit;
