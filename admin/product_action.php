<?php
require_once __DIR__ . '/admin_session.php';
$next = 'products.php';
require __DIR__ . '/admin_auth.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: products.php');
    exit;
}

$action = $_POST['action'] ?? '';

if ($action === 'delete') {
    $productId = (int) ($_POST['product_id'] ?? 0);
    if ($productId > 0) {
        $stmt = db()->prepare('DELETE FROM products WHERE product_id = ?');
        $stmt->bind_param('i', $productId);
        $stmt->execute();
        $stmt->close();
    }
    header('Location: products.php');
    exit;
}

$name = trim($_POST['name'] ?? '');
$price = (float) ($_POST['price'] ?? 0);
$imagePath = trim($_POST['image_path'] ?? '');
$productId = (int) ($_POST['product_id'] ?? 0);

if ($name === '' || $price <= 0 || $imagePath === '') {
    $redirect = 'product_form.php';
    if ($productId > 0) {
        $redirect .= '?product_id=' . urlencode((string) $productId);
    }
    header('Location: ' . $redirect);
    exit;
}

if ($action === 'create') {
    $stmt = db()->prepare('INSERT INTO products (name, price, image_path) VALUES (?, ?, ?)');
    $stmt->bind_param('sds', $name, $price, $imagePath);
    $stmt->execute();
    $stmt->close();
} elseif ($action === 'update') {
    $stmt = db()->prepare('UPDATE products SET name = ?, price = ?, image_path = ? WHERE product_id = ?');
    $stmt->bind_param('sdsi', $name, $price, $imagePath, $productId);
    $stmt->execute();
    $stmt->close();
}

header('Location: products.php');
exit;
