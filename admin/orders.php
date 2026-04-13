<?php
require_once __DIR__ . '/admin_session.php';
$next = 'orders.php';
require __DIR__ . '/admin_auth.php';

$stmt = db()->prepare('SELECT order_id, full_name, email, phone, city, state, status, total_amount, created_at FROM orders ORDER BY created_at DESC');
$stmt->execute();
$stmt->bind_result($o_id, $o_name, $o_email, $o_phone, $o_city, $o_state, $o_status, $o_total, $o_created);
$orders = [];
while ($stmt->fetch()) {
    $orders[] = [
        'order_id' => $o_id,
        'full_name' => $o_name,
        'email' => $o_email,
        'phone' => $o_phone,
        'city' => $o_city,
        'state' => $o_state,
        'status' => $o_status,
        'total' => $o_total,
        'created_at' => $o_created,
    ];
}
$stmt->close();
$pageTitle = 'Orders Management';
require __DIR__ . '/includes/admin_header.php';
?>

<div class="admin-table-container">
    <table class="admin-table">
        <thead>
            <tr>
                <th>Order #</th>
                <th>Customer</th>
                <th>Status</th>
                <th>Total</th>
                <th>Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($orders as $order): ?>
                <?php 
                    $statusClass = 'status-pending';
                    if ($order['status'] === 'Processing') $statusClass = 'status-processing';
                    if ($order['status'] === 'Shipped') $statusClass = 'status-shipped';
                    if ($order['status'] === 'Delivered') $statusClass = 'status-delivered';
                ?>
                <tr>
                    <td><strong>#<?php echo (int) $order['order_id']; ?></strong></td>
                    <td><?php echo htmlspecialchars($order['full_name']); ?></td>
                    <td><span class="status-badge <?php echo $statusClass; ?>"><?php echo htmlspecialchars($order['status']); ?></span></td>
                    <td>₹<?php echo number_format($order['total']); ?></td>
                    <td><?php echo htmlspecialchars($order['created_at']); ?></td>
                    <td>
                        <a href="order_view.php?order_id=<?php echo (int) $order['order_id']; ?>" style="color: var(--gold); text-decoration: none; font-weight: 500;">View Details &rarr;</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php require __DIR__ . '/includes/admin_footer.php'; ?>
