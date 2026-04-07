<?php
require_once __DIR__ . '/session_bootstrap.php';
require_once __DIR__ . '/products_data.php';

$next = 'cart.php';
require __DIR__ . '/auth_check.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: cart.php');
    exit;
}

$items = $_SESSION['cart']['items'] ?? [];

if (isset($_POST['remove'])) {
    $removeId = (int) $_POST['remove'];
    unset($items[$removeId]);
} else {
    $action = $_POST['action'] ?? '';
    if ($action === 'clear') {
        $items = [];
    } elseif ($action === 'update') {
        $qtys = $_POST['qty'] ?? [];
        foreach ($qtys as $productId => $qty) {
            $productId = (int) $productId;
            $qty = (int) $qty;
            if ($qty < 1) {
                unset($items[$productId]);
                continue;
            }
            $items[$productId] = min(10, $qty);
        }
    }
}

$_SESSION['cart']['items'] = $items;

require_once __DIR__ . '/cart_helpers.php';
cart_sync_to_db();

header('Location: cart.php');
exit;
