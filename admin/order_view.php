<?php
require_once __DIR__ . '/admin_session.php';
$next = 'orders.php';
require __DIR__ . '/admin_auth.php';

$orderId = (int) ($_GET['order_id'] ?? 0);
if ($orderId < 1) {
    header('Location: orders.php');
    exit;
}

$stmt = db()->prepare('SELECT order_id, full_name, email, phone, address, city, state, status, total_amount, created_at FROM orders WHERE order_id = ? LIMIT 1');
$stmt->bind_param('i', $orderId);
$stmt->execute();
$stmt->bind_result($o_id, $o_name, $o_email, $o_phone, $o_address, $o_city, $o_state, $o_status, $o_total, $o_created);
$found = $stmt->fetch();
$stmt->close();

if (!$found) {
    header('Location: orders.php');
    exit;
}

$itemsStmt = db()->prepare('SELECT product_name, product_price, quantity, line_total FROM order_items WHERE order_id = ?');
$itemsStmt->bind_param('i', $orderId);
$itemsStmt->execute();
$itemsStmt->bind_result($i_name, $i_price, $i_qty, $i_total);
$items = [];
while ($itemsStmt->fetch()) {
    $items [] = [
        'product_name' => $i_name,
        'product_price' => $i_price,
        'quantity' => $i_qty,
        'line_total' => $i_total,
    ];
}
$itemsStmt->close();

$statusOptions = ['Pending', 'Processing', 'Shipped', 'Delivered', 'Cancelled'];

$pageTitle = 'Order #' . $o_id;
require __DIR__ . '/includes/admin_header.php';
?>

<div style="margin-bottom: 20px;"><a href="orders.php" style="color: var(--gold);">&larr; Back to Orders</a></div>

<div class="metrics-grid" style="grid-template-columns: 1fr 1fr;">
    <div class="card" style="box-shadow: 0 4px 15px rgba(0,0,0,0.03); padding: 25px;">
        <h3 style="border-bottom: 1px solid #eee; padding-bottom: 10px;">Customer Profile</h3>
        <p><strong>Name:</strong> <?php echo htmlspecialchars($o_name); ?></p>
        <p><strong>Email:</strong> <a href="mailto:<?php echo htmlspecialchars($o_email); ?>" style="color: var(--gold);"><?php echo htmlspecialchars($o_email); ?></a></p>
        <p><strong>Phone:</strong> <?php echo htmlspecialchars($o_phone); ?></p>
        <hr style="border: 0; border-top: 1px solid #eee; margin: 15px 0;">
        <p><strong>Delivery Address:</strong><br>
        <?php echo htmlspecialchars($o_address); ?><br>
        <?php echo htmlspecialchars($o_city); ?>, <?php echo htmlspecialchars($o_state); ?></p>
    </div>

    <div class="card" style="box-shadow: 0 4px 15px rgba(0,0,0,0.03); padding: 25px;">
        <h3 style="border-bottom: 1px solid #eee; padding-bottom: 10px;">Order Summary</h3>
        <p style="color: #888;">Placed on <?php echo htmlspecialchars($o_created); ?></p>
        
        <div style="margin: 20px 0; max-height: 200px; overflow-y: auto;">
            <?php foreach ($items as $item): ?>
              <div style="display: flex; justify-content: space-between; border-bottom: 1px dashed #eee; padding: 8px 0;">
                  <span><?php echo htmlspecialchars($item['product_name']); ?> <small style="color: #888;">x <?php echo (int) $item['quantity']; ?></small></span>
                  <span>₹<?php echo number_format($item['line_total']); ?></span>
              </div>
            <?php endforeach; ?>
        </div>
        <h2 style="color: var(--espresso); text-align: right;">Total: ₹<?php echo number_format($o_total); ?></h2>
    </div>
</div>

<div class="card" style="box-shadow: 0 4px 15px rgba(0,0,0,0.03); padding: 25px; margin-top: 30px;">
    <h3>Set Live Shipping Status</h3>
    <p style="font-size: 0.9rem; color: #666;">Updating this will move the visual tracking bar on the customer's profile.</p>
    
    <form action="order_action.php" method="post" style="display: flex; align-items: center; gap: 15px; margin-top: 15px; background: #fafbfe; padding: 20px; border-radius: 8px;">
        <input type="hidden" name="order_id" value="<?php echo (int) $o_id; ?>" />
        <input type="hidden" name="action" value="save_status" />
        
        <select name="status" style="padding: 10px; border-radius: 5px; border: 1px solid #ccc; width: 200px; font-weight: bold;">
            <?php foreach ($statusOptions as $status): ?>
                <option value="<?php echo $status; ?>" <?php echo $status === $o_status ? 'selected' : ''; ?>>
                <?php echo $status; ?>
                </option>
            <?php endforeach; ?>
        </select>
        <button class="btn primary" type="submit">Publish Update</button>
    </form>
</div>

<div class="card" style="box-shadow: 0 4px 15px rgba(0,0,0,0.03); padding: 25px; border-left: 4px solid #dc3545; margin-top: 30px;">
    <div style="display: flex; justify-content: space-between; align-items: center;">
        <div>
            <h3 style="color: #dc3545; margin: 0;">Danger Zone</h3>
            <p style="margin: 5px 0 0 0; color: #666;">Deleting an order is permanent and cannot be undone.</p>
        </div>
        <form action="order_action.php" method="post">
            <input type="hidden" name="order_id" value="<?php echo (int) $o_id; ?>" />
            <input type="hidden" name="action" value="delete" />
            <button class="btn ghost" type="submit" onclick="return confirm('Permanently delete this order?')" style="border-color: #dc3545; color: #dc3545;">Delete Order</button>
        </form>
    </div>
</div>

<?php require __DIR__ . '/includes/admin_footer.php'; ?>
