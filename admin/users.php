<?php
require_once __DIR__ . '/admin_session.php';
$next = 'users.php';
require __DIR__ . '/admin_auth.php';

$stmt = db()->prepare('SELECT user_id, full_name, email, phone, created_at FROM users ORDER BY created_at DESC');
$stmt->execute();
$stmt->bind_result($u_id, $u_name, $u_email, $u_phone, $u_created);
$users = [];
while ($stmt->fetch()) {
    $users[] = [
        'user_id' => $u_id,
        'full_name' => $u_name,
        'email' => $u_email,
        'phone' => $u_phone,
        'created_at' => $u_created,
    ];
}
$stmt->close();
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Users | Admin</title>
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
      <h1>Users</h1>
      <p>Registered customers.</p>
    </div>
  </section>

  <section class="section">
    <div class="container">
      <div class="cart-table">
        <div class="cart-row cart-head">
          <span>ID</span>
          <span>Name</span>
          <span>Email</span>
          <span>Phone</span>
          <span>Joined</span>
          <span>Action</span>
        </div>
        <?php foreach ($users as $user): ?>
          <div class="cart-row">
            <span><?php echo (int) $user['user_id']; ?></span>
            <span><?php echo htmlspecialchars($user['full_name']); ?></span>
            <span><?php echo htmlspecialchars($user['email']); ?></span>
            <span><?php echo htmlspecialchars($user['phone']); ?></span>
            <span><?php echo htmlspecialchars($user['created_at']); ?></span>
            <span>
              <a class="btn ghost" href="user_form.php?user_id=<?php echo (int) $user['user_id']; ?>">Edit</a>
              <form action="user_action.php" method="post" style="display:inline-flex;">
                <input type="hidden" name="user_id" value="<?php echo (int) $user['user_id']; ?>" />
                <input type="hidden" name="action" value="delete" />
                <button class="btn ghost" type="submit" onclick="return confirm('Delete this user? All their orders will also be removed.')">Delete</button>
              </form>
            </span>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </section>
</main>

</body>
</html>
