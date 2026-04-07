<?php
require_once __DIR__ . '/admin_session.php';
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Admin Login | Tiksha Furnishing</title>
  <link rel="stylesheet" href="../css/styles.css" />
</head>
<body>

<main>
  <section class="page-hero">
    <div class="container">
      <p class="eyebrow">Admin Access</p>
      <h1>Login to Admin Panel</h1>
      <p>Manage orders, products, and customer accounts.</p>
    </div>
  </section>

  <section class="form-section">
    <div class="container">
      <div class="form-card">
        <?php $code = $_GET['code'] ?? ''; ?>
        <?php if ($code === 'invalid'): ?>
          <div class="form-message error" style="margin-bottom: 20px; padding: 15px; border-radius: 8px; color: white;">Invalid admin email or password.</div>
        <?php elseif ($code === 'required'): ?>
          <div class="form-message error" style="margin-bottom: 20px; padding: 15px; border-radius: 8px; color: white;">Please fill in all fields.</div>
        <?php endif; ?>
        
        <form action="login_action.php" method="post">
          <input type="hidden" name="next" value="<?php echo htmlspecialchars($_GET['next'] ?? ''); ?>" />
          <div class="form-grid">
            <div class="form-field">
              <label>Email</label>
              <input name="email" type="email" required />
            </div>
            <div class="form-field">
              <label>Password</label>
              <input name="password" type="password" required />
            </div>
          </div>
          <button class="btn primary" type="submit">Login</button>
        </form>
      </div>
    </div>
  </section>
</main>

</body>
</html>
