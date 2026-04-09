<?php
// auth/login_action.php
require __DIR__ . '/../config/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /phpcourse/project7/auth/login.php');
    exit;
}

$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';
$next = trim($_POST['next'] ?? '');

if ($email === '' || $password === '') {
    header('Location: /phpcourse/project7/auth/login.php?type=login&code=required');
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header('Location: /phpcourse/project7/auth/login.php?type=login&code=invalid');
    exit;
}

$conn = db();

// --- START UNIVERSAL LOGIN: Check admin_users first ---
$stmtAdmin = $conn->prepare('SELECT admin_id, full_name, password FROM admin_users WHERE email = ? LIMIT 1');
$stmtAdmin->bind_param('s', $email);
$stmtAdmin->execute();
$stmtAdmin->bind_result($admin_id, $admin_full_name, $admin_hash);
$adminFound = $stmtAdmin->fetch();
$stmtAdmin->close();

if ($adminFound && password_verify($password, $admin_hash)) {
    session_start();
    session_regenerate_id(true);
    $_SESSION['admin_id'] = $admin_id;
    $_SESSION['admin_name'] = $admin_full_name;
    
    // Clear any existing customer session to prevent conflicts
    unset($_SESSION['user_id'], $_SESSION['user_name'], $_SESSION['cart']);
    
    header('Location: /phpcourse/project7/admin/index.php');
    exit;
}
// --- END UNIVERSAL LOGIN ---

$stmt = $conn->prepare('SELECT user_id, full_name, password, cart_data FROM users WHERE email = ? LIMIT 1');
$stmt->bind_param('s', $email);
$stmt->execute();
$stmt->bind_result($user_id, $full_name, $hash, $cartDataJson);
$userFound = $stmt->fetch();
$stmt->close();

if (!$userFound || !$hash || !password_verify($password, $hash)) {
    header('Location: /phpcourse/project7/auth/login.php?type=login&code=invalid');
    exit;
}

session_start();
session_regenerate_id(true);
$_SESSION['user_id'] = $user_id;
$_SESSION['user_name'] = $full_name;

// Clear any existing admin session to prevent conflicts
unset($_SESSION['admin_id'], $_SESSION['admin_name']);

if (!empty($cartDataJson)) {
    $decoded = json_decode($cartDataJson, true);
    if (is_array($decoded)) {
        $_SESSION['cart']['items'] = $decoded;
    }
}

// Debug: Check if session is set
error_log("Login successful: user_id=" . $user_id . ", user_name=" . $full_name);

$safeNext = '';
if ($next !== '' && preg_match('/^[a-z0-9_\\/.-]+\\.php(\\?.*)?$/i', $next)) {
    $safeNext = $next;
}

header('Location: ' . ($safeNext !== '' ? $safeNext : '/phpcourse/project7/'));
exit;
