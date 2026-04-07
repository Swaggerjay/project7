<?php
// auth_check.php
require_once __DIR__ . '/session_bootstrap.php';

$next = $next ?? ($_GET['next'] ?? null);

if (!isset($_SESSION['user_id'])) {
    $target = 'login.php?type=login&code=login_required';
    if ($next) {
        $target .= '&next=' . urlencode($next);
    }
    header('Location: ' . $target);
    exit;
}

