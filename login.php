<?php
require_once __DIR__ . '/session_bootstrap.php';
?>

<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Login | Tiksha Furnishing</title>
  <meta name="description" content="Login to your Tiksha Furnishing account." />
  <link rel="stylesheet" href="css/styles.css" />
</head>

<body>

  <!-- PAGE LOADER -->
  <div id="page-loader" class="page-loader" aria-hidden="true">
    <div class="loader-ring"></div>
    <p>Preparing your space...</p>
  </div>

  <!-- HEADER -->
  <header class="site-header" id="top">
    <div class="container header-inner">
      <a class="brand" href="index.php">
        <span class="brand-mark">TF</span>
        <span class="brand-text">
          <strong>Tiksha Furnishing</strong>
          <small>Premium Curtain Studio</small>
        </span>
      </a>

      <button class="nav-toggle"><span></span><span></span><span></span></button>

      <nav class="site-nav">
        <a href="index.php">Home</a>
        <a href="products.php">Products</a>
        <a href="about.php">About</a>
        <a href="contact.php">Contact</a>
      </nav>

      <div class="user-nav">
        <a href="login.php" class="active">
          <?php echo $userName ? "Hi, " . htmlspecialchars($userName) : "Login"; ?>
        </a>

        <?php if ($userName): ?>
          <a href="logout.php">Logout</a>
        <?php endif; ?>
      </div>
    </div>
  </header>

  <!-- MAIN -->
  <main>
    <section class="page-hero">
      <div class="container">
        <p class="eyebrow">Welcome Back</p>
        <h1>Login to Your Account</h1>
        <p>Access saved designs, quotes, and order updates.</p>
      </div>
    </section>

    <section class="form-section">
      <div class="container">
        <div class="form-card">

          <div class="form-success form-message" id="login-message">
            Login successful! Redirecting...
          </div>

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

            <p style="margin-top:16px;">
              New here? 
              <a href="register.php" style="color:var(--gold);font-weight:600;">Create an account</a>.
            </p>

          </form>
        </div>
      </div>
    </section>
  </main>

  <!-- FOOTER -->
  <?php require __DIR__ . '/includes/footer.php'; ?>
</body>
</html>
