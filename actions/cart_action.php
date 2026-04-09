<?php
// actions/cart_action.php
require_once __DIR__ . '/../config/session_bootstrap.php';
require_once __DIR__ . '/../pages/products_data.php';

$next = '/phpcourse/project7/pages/cart.php';
require __DIR__ . '/../core/auth_check.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /phpcourse/project7/pages/cart.php');
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

require_once __DIR__ . '/../core/cart_helpers.php';
cart_sync_to_db();

header('Location: /phpcourse/project7/pages/cart.php');
exit;
