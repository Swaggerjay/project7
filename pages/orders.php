<?php
// pages/orders.php
require_once __DIR__ . '/../config/session_bootstrap.php';
require_once __DIR__ . '/../config/db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: /phpcourse/project7/auth/login.php?type=login&code=login_required');
    exit;
}

$userId = $_SESSION['user_id'];
$c = db();

$stmt = $c->prepare("SELECT order_id, total_amount, status, created_at FROM orders WHERE user_id = ? ORDER BY created_at DESC");
$stmt->bind_param('i', $userId);
$stmt->execute();
$res = $stmt->get_result();
$orders = [];
while ($row = $res->fetch_assoc()) {
    $orders[] = $row;
}
$stmt->close();
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>My Orders | Tiksha Furnishing</title>
  <link rel="stylesheet" href="/phpcourse/project7/css/styles.css" />
</head>
<body>

<?php require __DIR__ . '/../includes/header.php'; ?>

<main>
  <section class="page-hero">
    <div class="container text-center">
      <h1>My Orders</h1>
      <p>View your past purchases and track current shipments.</p>
    </div>
  </section>

  <section class="section">
    <div class="container" style="max-width: 800px;">
        <div style="margin-bottom: 20px;"><a href="profile.php" style="color: var(--gold);">&larr; Back to Profile</a></div>
        <?php if (empty($orders)): ?>
            <div class="card text-center" style="padding: 40px;">
                <h3>No orders yet.</h3>
                <p>You haven't placed any orders yet.</p>
                <a href="products.php" class="btn primary" style="margin-top: 15px;">Shop Now</a>
            </div>
        <?php else: ?>
            <div style="display: flex; flex-direction: column; gap: 15px;">
                <?php foreach ($orders as $o): ?>
                    <div class="card" style="display: flex; justify-content: space-between; align-items: center; padding: 20px;">
                        <div>
                            <h4 style="margin: 0 0 5px 0;">Order #<?php echo $o['order_id']; ?></h4>
                            <p style="margin:0; font-size: 0.9rem; color: #888;">Date: <?php echo date('M d, Y', strtotime($o['created_at'])); ?></p>
                            <p style="margin: 5px 0 0 0; font-weight: bold;">Status: <span style="color: var(--gold);"><?php echo htmlspecialchars($o['status']); ?></span></p>
                        </div>
                        <div style="text-align: right;">
                            <p style="margin: 0 0 10px 0; font-weight: bold; font-size: 1.1rem;">₹<?php echo number_format($o['total_amount']); ?></p>
                            <a href="order_details.php?id=<?php echo $o['order_id']; ?>" class="btn ghost" style="padding: 8px 15px; font-size: 0.9rem;">View & Track</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
  </section>
</main>

<?php require __DIR__ . '/../includes/footer.php'; ?>
</body>
</html>
