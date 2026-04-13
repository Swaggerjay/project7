<?php
require_once __DIR__ . '/admin_session.php';
$next = 'index.php';
require __DIR__ . '/admin_auth.php';

$conn = db();

$orderCount = $conn->query('SELECT COUNT(*) AS total FROM orders')->fetch_assoc()['total'] ?? 0;
$pendingCount = $conn->query("SELECT COUNT(*) AS total FROM orders WHERE status = 'Pending'")->fetch_assoc()['total'] ?? 0;
$userCount = $conn->query('SELECT COUNT(*) AS total FROM users')->fetch_assoc()['total'] ?? 0;
$productCount = $conn->query('SELECT COUNT(*) AS total FROM products')->fetch_assoc()['total'] ?? 0;
$messageCount = $conn->query('SELECT COUNT(*) AS total FROM contact_messages')->fetch_assoc()['total'] ?? 0;
$revenue = $conn->query('SELECT SUM(total_amount) AS rev FROM orders')->fetch_assoc()['rev'] ?? 0;

// Fetch last 7 days revenue for chart
$chartDataQuery = $conn->query("SELECT DATE(created_at) as order_date, SUM(total_amount) as daily_revenue FROM orders WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL 7 DAY) GROUP BY order_date ORDER BY order_date ASC");
$dates = [];
$revenues = [];
while ($row = $chartDataQuery->fetch_assoc()) {
    $dates[] = $row['order_date'];
    $revenues[] = $row['daily_revenue'];
}

$pageTitle = 'Dashboard Overview';
require __DIR__ . '/includes/admin_header.php';
?>

<div class="metrics-grid">
    <div class="metric-card">
        <p>Total Revenue</p>
        <h3>₹<?php echo number_format($revenue, 2); ?></h3>
    </div>
    <div class="metric-card">
        <p>Total Orders</p>
        <h3><?php echo (int) $orderCount; ?></h3>
    </div>
    <div class="metric-card" style="border-left-color: #ffc107;">
        <p>Pending Orders</p>
        <h3><?php echo (int) $pendingCount; ?></h3>
    </div>
    <div class="metric-card" style="border-left-color: #17a2b8;">
        <p>Registered Users</p>
        <h3><?php echo (int) $userCount; ?></h3>
    </div>
</div>

<div class="chart-container">
    <h3 style="margin-top:0;">Revenue (Last 7 Days)</h3>
    <?php if (empty($revenues)): ?>
        <p style="color: #888;">No revenue data for the past 7 days.</p>
    <?php else: ?>
        <canvas id="revenueChart" height="80"></canvas>
    <?php endif; ?>
</div>

<div class="metrics-grid">
    <div class="metric-card" style="border-left-color: #6f42c1;">
        <p>Products in Catalog</p>
        <h3><?php echo (int) $productCount; ?></h3>
    </div>
    <div class="metric-card" style="border-left-color: #fd7e14;">
        <p>Inbox Messages</p>
        <h3><?php echo (int) $messageCount; ?></h3>
    </div>
</div>

<?php if (!empty($revenues)): ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('revenueChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: <?php echo json_encode($dates); ?>,
            datasets: [{
                label: 'Daily Revenue (₹)',
                data: <?php echo json_encode($revenues); ?>,
                borderColor: '#c9a36a',
                backgroundColor: 'rgba(201, 163, 106, 0.1)',
                borderWidth: 2,
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: { beginAtZero: true }
            }
        }
    });
</script>
<?php endif; ?>

<?php require __DIR__ . '/includes/admin_footer.php'; ?>
