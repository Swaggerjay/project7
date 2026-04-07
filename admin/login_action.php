<?php
require_once __DIR__ . '/../db_connect.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: login.php');
    exit;
}

$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';
$next = trim($_POST['next'] ?? '');

error_log("ADMIN LOGIN ATTEMPT: Email: '$email', Password: '$password'");

if ($email === '' || $password === '') {
    header('Location: login.php?code=required');
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header('Location: login.php?code=invalid');
    exit;
}

$stmt = db()->prepare('SELECT admin_id, full_name, password FROM admin_users WHERE email = ? LIMIT 1');
$stmt->bind_param('s', $email);
$stmt->execute();
$stmt->bind_result($admin_id, $full_name, $hash);
$found = $stmt->fetch();
$stmt->close();

if (!$found || !$hash || !password_verify($password, $hash)) {
    header('Location: login.php?code=invalid');
    exit;
}

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}
session_regenerate_id(true);
$_SESSION['admin_id'] = $admin_id;
$_SESSION['admin_name'] = $full_name;

$safeNext = '';
if ($next !== '' && preg_match('/^[a-z0-9_\\/.-]+\\.php(\\?.*)?$/i', $next)) {
    $safeNext = $next;
}

header('Location: ' . ($safeNext !== '' ? $safeNext : 'index.php'));
exit;
