<?php
require_once __DIR__ . '/admin_session.php';
$next = 'messages.php';
require __DIR__ . '/admin_auth.php';

$stmt = db()->prepare('SELECT id, name, email, phone, message, created_at FROM contact_messages ORDER BY created_at DESC');
$stmt->execute();
$stmt->bind_result($m_id, $m_name, $m_email, $m_phone, $m_msg, $m_created);
$messages = [];
while ($stmt->fetch()) {
    $messages[] = [
        'id' => $m_id,
        'name' => $m_name,
        'email' => $m_email,
        'phone' => $m_phone,
        'message' => $m_msg,
        'created_at' => $m_created,
    ];
}
$stmt->close();
$pageTitle = 'Customer Inquiries';
require __DIR__ . '/includes/admin_header.php';
?>

<div style="display: flex; flex-direction: column; gap: 20px;">
<?php if (empty($messages)): ?>
  <p>No messages received yet.</p>
<?php else: ?>
  <?php foreach ($messages as $msg): ?>
    <div class="card" style="padding: 20px; box-shadow: 0 4px 15px rgba(0,0,0,0.03); border: none;">
      <div style="display: flex; justify-content: space-between; border-bottom: 1px solid rgba(0,0,0,0.05); padding-bottom: 10px; margin-bottom: 12px;">
        <strong style="font-size: 1.1rem; color: var(--espresso);"><?php echo htmlspecialchars($msg['name']); ?></strong>
        <small style="color: #888;"><?php echo htmlspecialchars($msg['created_at']); ?></small>
      </div>
      <p style="margin-bottom: 5px;"><strong>Email:</strong> <a href="mailto:<?php echo htmlspecialchars($msg['email']); ?>" style="color: var(--gold);"><?php echo htmlspecialchars($msg['email']); ?></a></p>
      <p style="margin-bottom: 15px;"><strong>Phone:</strong> <?php echo htmlspecialchars($msg['phone']); ?></p>
      <div style="background: rgba(0,0,0,0.02); padding: 15px; border-radius: 8px; margin-bottom: 15px; border-left: 3px solid var(--gold);">
        <?php echo nl2br(htmlspecialchars($msg['message'])); ?>
      </div>
      <form action="message_action.php" method="post" style="display: flex; justify-content: flex-end;">
        <input type="hidden" name="message_id" value="<?php echo (int) $msg['id']; ?>" />
        <input type="hidden" name="action" value="delete" />
        <button class="btn ghost" type="submit" onclick="return confirm('Delete this inquiry?')" style="font-size: 0.85rem; border-color: #dc3545; color: #dc3545;">Delete Inquiry</button>
      </form>
    </div>
  <?php endforeach; ?>
<?php endif; ?>
</div>

<?php require __DIR__ . '/includes/admin_footer.php'; ?>
