<?php
require_once __DIR__ . '/admin_session.php';

$next = $next ?? ($_GET['next'] ?? null);

if (!isset($_SESSION['admin_id'])) {
    $target = 'login.php?code=login_required';
    if ($next) {
        $target .= '&next=' . urlencode($next);
    }
    header('Location: ' . $target);
    exit;
}
