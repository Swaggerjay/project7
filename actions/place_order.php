<?php
// actions/place_order.php
require_once __DIR__ . '/../config/session_bootstrap.php';
require_once __DIR__ . '/../pages/products_data.php';
require_once __DIR__ . '/../core/cart_helpers.php';

$next = '/phpcourse/project7/actions/checkout.php';
require __DIR__ . '/../core/auth_check.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /phpcourse/project7/actions/checkout.php');
    exit;
}

$full_name = trim($_POST['full_name'] ?? '');
$email = trim($_POST['email'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$address = trim($_POST['address'] ?? '');
$city = trim($_POST['city'] ?? '');
$state = trim($_POST['state'] ?? '');
$payment_method = trim($_POST['payment_method'] ?? 'Cash on Delivery');
$status = 'Pending';

if ($full_name === '' || $email === '' || $phone === '' || $address === '' || $city === '' || $state === '') {
    header('Location: /phpcourse/project7/actions/checkout.php?type=checkout&code=required');
    exit;
}

$products = products_all();
$cartData = cart_totals($products);
$lines = $cartData['lines'];
$total = $cartData['total'];

if (empty($lines)) {
    header('Location: /phpcourse/project7/pages/cart.php?type=cart&code=empty');
    exit;
}

$conn = db();
$conn->begin_transaction();

try {
    $orderStmt = $conn->prepare('INSERT INTO orders (user_id, full_name, email, phone, address, city, state, status, total_amount, payment_method) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)');
    $orderStmt->bind_param(
        'isssssssds',
        $_SESSION['user_id'],
        $full_name,
        $email,
        $phone,
        $address,
        $city,
        $state,
        $status,
        $total,
        $payment_method
    );
    $orderStmt->execute();
    $orderId = $conn->insert_id;
    $orderStmt->close();

    $itemStmt = $conn->prepare('INSERT INTO order_items (order_id, product_id, product_name, product_price, quantity, line_total) VALUES (?, ?, ?, ?, ?, ?)');
    $itemsForEmail = [];
    foreach ($lines as $line) {
        $product = $line['product'];
        $qty = $line['qty'];
        $lineTotal = $line['line_total'];
        
        $itemsForEmail[] = [
            'product_name' => $product['name'],
            'quantity'     => $qty,
            'line_total'   => $lineTotal
        ];

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

    // Trigger Automated Email
    require_once __DIR__ . '/../core/email_helper.php';
    $shippingAddress = "{$address}, {$city}, {$state}";
    sendOrderConfirmationEmail($orderId, $email, $full_name, $total, $itemsForEmail, $shippingAddress);

} catch (Throwable $e) {
    $conn->rollback();
    error_log('Order insert failed: ' . $e->getMessage());
    header('Location: /phpcourse/project7/actions/checkout.php?type=checkout&code=failed');
    exit;
}

$_SESSION['cart']['items'] = [];
cart_sync_to_db(); // cart_helpers.php is already required at top

header('Location: /phpcourse/project7/actions/order_success.php?order_id=' . urlencode((string) $orderId) . '&confirmed=1');
exit;
