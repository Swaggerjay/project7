<?php
// actions/wishlist_action.php
require_once __DIR__ . '/../config/session_bootstrap.php';
require_once __DIR__ . '/../config/db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: /phpcourse/project7/auth/login.php?type=login&code=login_required');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /phpcourse/project7/pages/products.php');
    exit;
}

$productId = (int) ($_POST['product_id'] ?? 0);
$userId = $_SESSION['user_id'];
$c = db();

// Check if already in wishlist
$stmt = $c->prepare("SELECT id FROM wishlist WHERE user_id = ? AND product_id = ?");
$stmt->bind_param('ii', $userId, $productId);
$stmt->execute();
$stmt->store_result();
$exists = $stmt->num_rows > 0;
$stmt->close();

if ($exists) {
    // Remove from wishlist
    $del = $c->prepare("DELETE FROM wishlist WHERE user_id = ? AND product_id = ?");
    $del->bind_param('ii', $userId, $productId);
    $del->execute();
    $_SESSION['toast'] = "Removed from your wishlist";
} else {
    // Add to wishlist
    $ins = $c->prepare("INSERT INTO wishlist (user_id, product_id) VALUES (?, ?)");
    $ins->bind_param('ii', $userId, $productId);
    $ins->execute();
    $_SESSION['toast'] = "Added to your wishlist!";
}

$redirect = $_SERVER['HTTP_REFERER'] ?? '/phpcourse/project7/pages/products.php';
header("Location: $redirect");
exit;
