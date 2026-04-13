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

$productId = (int) ($_POST['product_id'] ?? 0);
$name = trim($_POST['name'] ?? '');
$price = (float) ($_POST['price'] ?? 0);
$category = trim($_POST['category'] ?? '');
$description = trim($_POST['description'] ?? '');

if ($name === '' || $price <= 0) {
    header('Location: product_form.php' . ($productId ? "?product_id=$productId" : ''));
    exit;
}

$uploadDir = __DIR__ . '/../images/';
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

// Handle Primary Image Upload
$imagePathToSave = null;
if (isset($_FILES['primary_image']) && $_FILES['primary_image']['error'] === UPLOAD_ERR_OK) {
    $tmpName = $_FILES['primary_image']['tmp_name'];
    $fileName = time() . '_' . basename($_FILES['primary_image']['name']);
    if (move_uploaded_file($tmpName, $uploadDir . $fileName)) {
        $imagePathToSave = 'images/' . $fileName;
    }
}

// Handle Gallery Uploads
$galleryPaths = [];
if (isset($_FILES['gallery_images']) && !empty($_FILES['gallery_images']['name'][0])) {
    $count = count($_FILES['gallery_images']['name']);
    for ($i = 0; $i < min($count, 5); $i++) {
        if ($_FILES['gallery_images']['error'][$i] === UPLOAD_ERR_OK) {
            $tmpName = $_FILES['gallery_images']['tmp_name'][$i];
            $fileName = time() . '_gal_' . $i . '_' . basename($_FILES['gallery_images']['name'][$i]);
            if (move_uploaded_file($tmpName, $uploadDir . $fileName)) {
                $galleryPaths[] = 'images/' . $fileName;
            }
        }
    }
}

$galleryJson = !empty($galleryPaths) ? json_encode($galleryPaths) : null;
$conn = db();

if ($action === 'create') {
    $finalImagePath = $imagePathToSave ?: 'images/placeholder.jpg';
    $galleryValue = $galleryJson ?: '[]';
    
    $stmt = $conn->prepare('INSERT INTO products (name, price, category, description, image_path, gallery_images) VALUES (?, ?, ?, ?, ?, ?)');
    $stmt->bind_param('sdssss', $name, $price, $category, $description, $finalImagePath, $galleryValue);
    $stmt->execute();
    $stmt->close();

} elseif ($action === 'update') {
    // If we have a newly uploaded primary image, update it. Otherwise keep old.
    if ($imagePathToSave && $galleryJson) {
        $stmt = $conn->prepare('UPDATE products SET name=?, price=?, category=?, description=?, image_path=?, gallery_images=? WHERE product_id=?');
        $stmt->bind_param('sdssssi', $name, $price, $category, $description, $imagePathToSave, $galleryJson, $productId);
    } elseif ($imagePathToSave) {
        $stmt = $conn->prepare('UPDATE products SET name=?, price=?, category=?, description=?, image_path=? WHERE product_id=?');
        $stmt->bind_param('sdsssi', $name, $price, $category, $description, $imagePathToSave, $productId);
    } elseif ($galleryJson) {
        $stmt = $conn->prepare('UPDATE products SET name=?, price=?, category=?, description=?, gallery_images=? WHERE product_id=?');
        $stmt->bind_param('sdsssi', $name, $price, $category, $description, $galleryJson, $productId);
    } else {
        $stmt = $conn->prepare('UPDATE products SET name=?, price=?, category=?, description=? WHERE product_id=?');
        $stmt->bind_param('sdssi', $name, $price, $category, $description, $productId);
    }
    
    $stmt->execute();
    $stmt->close();
}

header('Location: products.php');
exit;
