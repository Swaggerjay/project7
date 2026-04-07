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

$conn = db();

// --- START UNIVERSAL LOGIN: Check admin_users first ---
$stmtAdmin = $conn->prepare('SELECT admin_id, full_name, password FROM admin_users WHERE email = ? LIMIT 1');
$stmtAdmin->bind_param('s', $email);
$stmtAdmin->execute();
$stmtAdmin->bind_result($admin_id, $admin_full_name, $admin_hash);
$adminFound = $stmtAdmin->fetch();
$stmtAdmin->close();

if ($adminFound && password_verify($password, $admin_hash)) {
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }
    session_regenerate_id(true);
    $_SESSION['admin_id'] = $admin_id;
    $_SESSION['admin_name'] = $admin_full_name;
    
    // Clear any existing customer session to prevent conflicts
    unset($_SESSION['user_id'], $_SESSION['user_name'], $_SESSION['cart']);
    
    $safeNext = '';
    if ($next !== '' && preg_match('/^[a-z0-9_\\/.-]+\\.php(\\?.*)?$/i', $next)) {
        $safeNext = $next;
    }
    
    header('Location: ' . ($safeNext !== '' ? $safeNext : 'index.php'));
    exit;
}
// --- END UNIVERSAL LOGIN ---

// If not an admin, check regular users
$stmtUser = $conn->prepare('SELECT user_id, full_name, password, cart_data FROM users WHERE email = ? LIMIT 1');
$stmtUser->bind_param('s', $email);
$stmtUser->execute();
$stmtUser->bind_result($user_id, $user_full_name, $user_hash, $cartDataJson);
$userFound = $stmtUser->fetch();
$stmtUser->close();

if ($userFound && password_verify($password, $user_hash)) {
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }
    session_regenerate_id(true);
    $_SESSION['user_id'] = $user_id;
    $_SESSION['user_name'] = $user_full_name;
    
    // Clear any existing admin session to prevent conflicts
    unset($_SESSION['admin_id'], $_SESSION['admin_name']);
    
    if (!empty($cartDataJson)) {
        $decoded = json_decode($cartDataJson, true);
        if (is_array($decoded)) {
            $_SESSION['cart']['items'] = $decoded;
        }
    }
    
    // Redirect to front-end index
    header('Location: ../index.php');
    exit;
}

// If no user found in either table
header('Location: login.php?code=invalid');
exit;
