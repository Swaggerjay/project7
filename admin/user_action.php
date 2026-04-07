<?php
require_once __DIR__ . '/admin_session.php';
$next = 'users.php';
require __DIR__ . '/admin_auth.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: users.php');
    exit;
}

$action = $_POST['action'] ?? '';
$userId = (int) ($_POST['user_id'] ?? 0);

if ($userId < 1) {
    header('Location: users.php');
    exit;
}

if ($action === 'delete') {
    $stmt = db()->prepare('DELETE FROM users WHERE user_id = ?');
    $stmt->bind_param('i', $userId);
    $stmt->execute();
    $stmt->close();
    header('Location: users.php?deleted=1');
    exit;
}

if ($action === 'update') {
    $fullName = trim($_POST['full_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');

    if ($fullName === '' || $email === '' || $phone === '') {
        header('Location: user_form.php?user_id=' . $userId . '&error=required');
        exit;
    }

    $stmt = db()->prepare('UPDATE users SET full_name = ?, email = ?, phone = ? WHERE user_id = ?');
    $stmt->bind_param('sssi', $fullName, $email, $phone, $userId);
    $stmt->execute();
    $stmt->close();
    header('Location: users.php?updated=1');
    exit;
}

header('Location: users.php');
exit;
