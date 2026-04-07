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
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Messages | Admin</title>
  <link rel="stylesheet" href="../css/styles.css" />
</head>
<body>

<header class="site-header">
  <div class="container header-inner">
    <a class="brand" href="index.php">
      <span class="brand-mark">TF</span>
      <span class="brand-text"><strong>Admin Panel</strong></span>
    </a>
    <nav class="site-nav">
      <a href="index.php">Dashboard</a>
      <a href="orders.php">Orders</a>
      <a href="products.php">Products</a>
      <a href="users.php">Users</a>
      <a href="messages.php" class="active">Messages</a>
    </nav>
    <div class="user-nav">
      <span>Hi, <?php echo htmlspecialchars($adminName ?? 'Admin'); ?></span>
      <a href="logout.php">Logout</a>
    </div>
  </div>
</header>

<main>
  <section class="page-hero">
    <div class="container">
      <h1>Inquiries</h1>
      <p>Review contact messages from customers.</p>
    </div>
  </section>

  <section class="section">
    <div class="container">
      <div style="display: flex; flex-direction: column; gap: 20px;">
        <?php if (empty($messages)): ?>
          <p>No messages received yet.</p>
        <?php else: ?>
          <?php foreach ($messages as $msg): ?>
            <div class="card" style="padding: 20px;">
              <div style="display: flex; justify-content: space-between; border-bottom: 1px solid rgba(163,116,62,0.2); padding-bottom: 10px; margin-bottom: 10px;">
                <strong><?php echo htmlspecialchars($msg['name']); ?></strong>
                <small><?php echo htmlspecialchars($msg['created_at']); ?></small>
              </div>
              <p style="margin-bottom: 5px;"><strong>Email:</strong> <a href="mailto:<?php echo htmlspecialchars($msg['email']); ?>"><?php echo htmlspecialchars($msg['email']); ?></a></p>
              <p style="margin-bottom: 15px;"><strong>Phone:</strong> <?php echo htmlspecialchars($msg['phone']); ?></p>
              <div style="background: rgba(0,0,0,0.03); padding: 15px; border-radius: 8px; margin-bottom: 12px;">
                <?php echo nl2br(htmlspecialchars($msg['message'])); ?>
              </div>
              <form action="message_action.php" method="post" style="display: flex; justify-content: flex-end;">
                <input type="hidden" name="message_id" value="<?php echo (int) $msg['id']; ?>" />
                <input type="hidden" name="action" value="delete" />
                <button class="btn ghost" type="submit" onclick="return confirm('Delete this inquiry?')" style="font-size: 0.85rem; border-color: #b0462d; color: #b0462d;">Delete Inquiry</button>
              </form>
            </div>
          <?php endforeach; ?>
        <?php endif; ?>
      </div>
    </div>
  </section>
</main>

</body>
</html>
