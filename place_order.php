<?php
require_once __DIR__ . '/session_bootstrap.php';
require_once __DIR__ . '/products_data.php';
require_once __DIR__ . '/cart_helpers.php';

$next = 'checkout.php';
require __DIR__ . '/auth_check.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: checkout.php');
    exit;
}

$full_name = trim($_POST['full_name'] ?? '');
$email = trim($_POST['email'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$address = trim($_POST['address'] ?? '');
$city = trim($_POST['city'] ?? '');
$state = trim($_POST['state'] ?? '');
$status = 'Pending';

if ($full_name === '' || $email === '' || $phone === '' || $address === '' || $city === '' || $state === '') {
    header('Location: checkout.php?type=checkout&code=required');
    exit;
}

$products = products_all();
$cartData = cart_totals($products);
$lines = $cartData['lines'];
$total = $cartData['total'];

if (empty($lines)) {
    header('Location: cart.php?type=cart&code=empty');
    exit;
}

$conn = db();
$conn->begin_transaction();

try {
    $orderStmt = $conn->prepare('INSERT INTO orders (user_id, full_name, email, phone, address, city, state, status, total_amount) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)');
    $orderStmt->bind_param(
        'isssssssd',
        $_SESSION['user_id'],
        $full_name,
        $email,
        $phone,
        $address,
        $city,
        $state,
        $status,
        $total
    );
    $orderStmt->execute();
    $orderId = $conn->insert_id;
    $orderStmt->close();

    $itemStmt = $conn->prepare('INSERT INTO order_items (order_id, product_id, product_name, product_price, quantity, line_total) VALUES (?, ?, ?, ?, ?, ?)');
    foreach ($lines as $line) {
        $product = $line['product'];
        $qty = $line['qty'];
        $lineTotal = $line['line_total'];
        $itemStmt->bind_param(
            'iisdid',
            $orderId,
            $product['id'],
            $product['name'],
            $product['price'],
            $qty,
            $lineTotal
        );
        $itemStmt->execute();
    }
    $itemStmt->close();

    $conn->commit();
} catch (Throwable $e) {
    $conn->rollback();
    error_log('Order insert failed: ' . $e->getMessage());
    header('Location: checkout.php?type=checkout&code=failed');
    exit;
}

$_SESSION['cart']['items'] = [];
cart_sync_to_db(); // cart_helpers.php is already required at top

header('Location: order_success.php?order_id=' . urlencode((string) $orderId) . '&confirmed=1');
exit;
