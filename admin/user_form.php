<?php
require_once __DIR__ . '/admin_session.php';
$next = 'users.php';
require __DIR__ . '/admin_auth.php';

$userId = (int) ($_GET['user_id'] ?? 0);
if ($userId < 1) {
    header('Location: users.php');
    exit;
}

$stmt = db()->prepare('SELECT full_name, email, phone FROM users WHERE user_id = ? LIMIT 1');
$stmt->bind_param('i', $userId);
$stmt->execute();
$stmt->bind_result($u_name, $u_email, $u_phone);
$found = $stmt->fetch();
$stmt->close();

if (!$found) {
    header('Location: users.php');
    exit;
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Edit User | Admin</title>
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
      <a href="users.php" class="active">Users</a>
      <a href="messages.php">Messages</a>
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
      <h1>Edit User #<?php echo $userId; ?></h1>
      <p>Modify customer profile details.</p>
    </div>
  </section>

  <section class="form-section">
    <div class="container">
      <div class="form-card">
        <form action="user_action.php" method="post">
          <input type="hidden" name="user_id" value="<?php echo $userId; ?>" />
          <input type="hidden" name="action" value="update" />
          <div class="form-grid">
            <div class="form-field">
              <label>Full Name</label>
              <input name="full_name" type="text" value="<?php echo htmlspecialchars($u_name); ?>" required />
            </div>
            <div class="form-field">
              <label>Email</label>
              <input name="email" type="email" value="<?php echo htmlspecialchars($u_email); ?>" required />
            </div>
            <div class="form-field">
              <label>Phone Number</label>
              <input name="phone" type="tel" value="<?php echo htmlspecialchars($u_phone); ?>" required />
            </div>
          </div>
          <div style="margin-top:20px; display:flex; gap:12px;">
            <button class="btn primary" type="submit">Save Changes</button>
            <a class="btn ghost" href="users.php">Cancel</a>
          </div>
        </form>
      </div>
    </div>
  </section>
</main>

</body>
</html>
