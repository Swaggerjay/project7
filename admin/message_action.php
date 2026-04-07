<?php
require_once __DIR__ . '/admin_session.php';
$next = 'messages.php';
require __DIR__ . '/admin_auth.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: messages.php');
    exit;
}

$action = $_POST['action'] ?? '';
$messageId = (int) ($_POST['message_id'] ?? 0);

if ($messageId < 1) {
    header('Location: messages.php');
    exit;
}

if ($action === 'delete') {
    $stmt = db()->prepare('DELETE FROM contact_messages WHERE id = ?');
    $stmt->bind_param('i', $messageId);
    $stmt->execute();
    $stmt->close();
    header('Location: messages.php?deleted=1');
    exit;
}

header('Location: messages.php');
exit;
