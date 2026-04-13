<?php
// actions/review_action.php
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
$rating = (int) ($_POST['rating'] ?? 5);
$comment = trim($_POST['comment'] ?? '');
$userId = $_SESSION['user_id'];

if ($productId > 0 && $rating >= 1 && $rating <= 5) {
    try {
        $c = db();
        $stmt = $c->prepare("INSERT INTO reviews (product_id, user_id, rating, comment) VALUES (?, ?, ?, ?)");
        $stmt->bind_param('iiis', $productId, $userId, $rating, $comment);
        $stmt->execute();
        $stmt->close();
        $_SESSION['toast'] = "Thank you! Your review was submitted.";
    } catch (Exception $e) {
        $_SESSION['toast'] = "Error submitting review.";
    }
}

$redirect = $_SERVER['HTTP_REFERER'] ?? '/phpcourse/project7/pages/product_details.php?id=' . $productId;
header("Location: $redirect");
exit;
