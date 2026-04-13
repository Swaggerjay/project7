<?php
// pages/order_details.php
require_once __DIR__ . '/../config/session_bootstrap.php';
require_once __DIR__ . '/../config/db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: /phpcourse/project7/auth/login.php');
    exit;
}

$orderId = (int)($_GET['id'] ?? 0);
$userId = $_SESSION['user_id'];
$c = db();

$stmt = $c->prepare("SELECT order_id, total_amount, status, created_at, full_name, address, city, state, payment_method FROM orders WHERE user_id = ? AND order_id = ?");
$stmt->bind_param('ii', $userId, $orderId);
$stmt->execute();
$res = $stmt->get_result();
$order = $res->fetch_assoc();
$stmt->close();

if (!$order) {
    header("Location: orders.php");
    exit;
}

// Fetch items
$itemsStmt = $c->prepare("SELECT product_name, quantity, line_total FROM order_items WHERE order_id = ?");
$itemsStmt->bind_param('i', $orderId);
$itemsStmt->execute();
$iRes = $itemsStmt->get_result();
$items = [];
while ($row = $iRes->fetch_assoc()) {
    $items[] = $row;
}
$itemsStmt->close();

$statusMap = [
    'Pending' => 1,
    'Processing' => 2,
    'Shipped' => 3,
    'Delivered' => 4
];
$currentStep = $statusMap[$order['status']] ?? 1;

?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Order #<?php echo $order['order_id']; ?> | Tiksha Furnishing</title>
  <link rel="stylesheet" href="/phpcourse/project7/css/styles.css" />
</head>
<body>

<?php require __DIR__ . '/../includes/header.php'; ?>

<main>
  <section class="page-hero">
    <div class="container text-center">
        <h1>Order Details Track</h1>
        <p>Order #<?php echo $order['order_id']; ?> placed on <?php echo date('M d, Y', strtotime($order['created_at'])); ?></p>
    </div>
  </section>

  <section class="section">
    <div class="container" style="max-width: 800px;">
        <div style="margin-bottom: 20px;"><a href="orders.php" style="color: var(--gold);">&larr; Back to All Orders</a></div>
        
        <!-- TRACKER -->
        <div class="card" style="margin-bottom: 30px; padding-bottom: 40px;">
            <h3 style="text-align: center; margin-bottom: 20px;">Shipping Status: <span style="color: var(--gold);"><?php echo htmlspecialchars($order['status']); ?></span></h3>
            <div class="order-tracker">
                <div class="tracker-step <?php echo $currentStep >= 1 ? ($currentStep > 1 ? 'completed' : 'active') : ''; ?>">
                    <div class="step-dot">✓</div>
                    <div class="step-label">Pending</div>
                </div>
                <div class="tracker-step <?php echo $currentStep >= 2 ? ($currentStep > 2 ? 'completed' : 'active') : ''; ?>">
                    <div class="step-dot">⚙</div>
                    <div class="step-label">Processing</div>
                </div>
                <div class="tracker-step <?php echo $currentStep >= 3 ? ($currentStep > 3 ? 'completed' : 'active') : ''; ?>">
                    <div class="step-dot">🚚</div>
                    <div class="step-label">Shipped</div>
                </div>
                <div class="tracker-step <?php echo $currentStep >= 4 ? 'completed' : ''; ?>">
                    <div class="step-dot">📦</div>
                    <div class="step-label">Delivered</div>
                </div>
            </div>
        </div>

        <div class="grid" style="grid-template-columns: 1fr 1fr; gap: 20px;">
            <div class="card">
                <h3>Delivery Address</h3>
                <p><?php echo htmlspecialchars($order['full_name']); ?><br>
                <?php echo htmlspecialchars($order['address']); ?><br>
                <?php echo htmlspecialchars($order['city']); ?>, <?php echo htmlspecialchars($order['state']); ?></p>
            </div>
            
            <div class="card">
                <h3>Payment Details</h3>
                <p>Method: <?php echo htmlspecialchars($order['payment_method']); ?></p>
                <p><strong>Total Amount: ₹<?php echo number_format($order['total_amount']); ?></strong></p>
            </div>
        </div>

        <div class="card" style="margin-top: 20px;">
            <h3>Items in this Order</h3>
            <hr style="border: 0; border-top:1px solid #eee; margin: 15px 0;">
            <?php foreach ($items as $item): ?>
                <div style="display:flex; justify-content:space-between; margin-bottom: 10px;">
                    <div><?php echo htmlspecialchars($item['product_name']); ?> <span style="color:#888;">x <?php echo $item['quantity']; ?></span></div>
                    <div>₹<?php echo number_format($item['line_total']); ?></div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
  </section>
</main>

<?php require __DIR__ . '/../includes/footer.php'; ?>
</body>
</html>
