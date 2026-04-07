<?php
// contact_action.php
require __DIR__ . '/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: contact.php');
    exit;
}

$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$message = trim($_POST['message'] ?? '');

if ($name === '' || $email === '' || $phone === '' || $message === '') {
    header('Location: contact.php?type=contact&code=required');
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header('Location: contact.php?type=contact&code=invalid_email');
    exit;
}

$phone_clean = preg_replace('/\s+/', '', $phone);
if (!preg_match('/^\+?[0-9]{8,14}$/', $phone_clean)) {
    header('Location: contact.php?type=contact&code=invalid_phone');
    exit;
}

try {
    $conn = db();
    $stmt = $conn->prepare('INSERT INTO contact_messages (name, email, phone, message) VALUES (?, ?, ?, ?)');
    $stmt->bind_param('ssss', $name, $email, $phone, $message);
    $stmt->execute();
    $stmt->close();
} catch (Throwable $e) {
    error_log('Contact form insert failed: ' . $e->getMessage());
    header('Location: contact.php?type=contact&code=failed');
    exit;
}

header('Location: contact.php?type=contact&code=success');
exit;
?>
