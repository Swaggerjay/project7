<?php
// config/session_bootstrap.php
require_once __DIR__ . '/db_connect.php';

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

$userName = $_SESSION['user_name'] ?? null;

if (!$userName && isset($_SESSION['user_id'])) {
    $stmt = db()->prepare('SELECT full_name FROM users WHERE user_id = ? LIMIT 1');
    $stmt->bind_param('i', $_SESSION['user_id']);
    $stmt->execute();
    $stmt->bind_result($full_name);
    if ($stmt->fetch()) {
        $userName = $full_name;
        $_SESSION['user_name'] = $full_name;
    }
    $stmt->close();
}
