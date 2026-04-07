<?php
require_once __DIR__ . '/admin_session.php';
$next = 'orders.php';
require __DIR__ . '/admin_auth.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: orders.php');
    exit;
}

$action = $_POST['action'] ?? '';
$orderId = (int) ($_POST['order_id'] ?? 0);

if ($orderId < 1) {
    header('Location: orders.php');
    exit;
}

if ($action === 'delete') {
    // Delete items first for consistency
    $stmtItems = db()->prepare('DELETE FROM order_items WHERE order_id = ?');
    $stmtItems->bind_param('i', $orderId);
    $stmtItems->execute();
    $stmtItems->close();

    $stmt = db()->prepare('DELETE FROM orders WHERE order_id = ?');
    $stmt->bind_param('i', $orderId);
    $stmt->execute();
    $stmt->close();
    header('Location: orders.php?deleted=1');
    exit;
}

if ($action === 'save_status') {
    $status = $_POST['status'] ?? 'Pending';
    $stmt = db()->prepare('UPDATE orders SET status = ? WHERE order_id = ?');
    $stmt->bind_param('si', $status, $orderId);
    $stmt->execute();
    $stmt->close();
    header('Location: order_view.php?order_id=' . $orderId . '&updated=1');
    exit;
}

header('Location: orders.php');
exit;
