<?php
// register.php
require __DIR__ . '/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: register.php');
    exit;
}

$full_name = trim($_POST['full_name'] ?? '');
$email = trim($_POST['email'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$password = $_POST['password'] ?? '';
$confirm = $_POST['confirm_password'] ?? '';

if ($full_name === '' || $email === '' || $phone === '' || $password === '' || $confirm === '') {
    header('Location: register.php?type=register&code=required');
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header('Location: register.php?type=register&code=invalid_email');
    exit;
}

$phone_clean = preg_replace('/\s+/', '', $phone);
if (!preg_match('/^\+?[0-9]{8,14}$/', $phone_clean)) {
    header('Location: register.php?type=register&code=invalid_phone');
    exit;
}

if (strlen($password) < 6) {
    header('Location: register.php?type=register&code=password_short');
    exit;
}

if ($password !== $confirm) {
    header('Location: register.php?type=register&code=password_mismatch');
    exit;
}

$conn = db();

$stmt = $conn->prepare('SELECT user_id FROM users WHERE email = ? LIMIT 1');
$stmt->bind_param('s', $email);
$stmt->execute();
$stmt->store_result();
if ($stmt->num_rows > 0) {
    $stmt->close();
    header('Location: register.php?type=register&code=email_exists');
    exit;
}
$stmt->close();

$hashed = password_hash($password, PASSWORD_DEFAULT);

$insert = $conn->prepare('INSERT INTO users (full_name, email, phone, password) VALUES (?, ?, ?, ?)');
$insert->bind_param('ssss', $full_name, $email, $phone_clean, $hashed);
$insert->execute();
$insert->close();

header('Location: login.php?type=login&code=registered');
exit;

?>







