<?php
require_once __DIR__ . '/../config/session_bootstrap.php';
?>

<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Register | Tiksha Furnishing</title>
  <meta name="description" content="Create your Tiksha Furnishing account to track orders and save curated curtain designs." />
  <link rel="stylesheet" href="/phpcourse/project7/css/styles.css" />
</head>

<body>

  <!-- ================== PAGE LOADER ================== -->
  <div id="page-loader" class="page-loader" aria-hidden="true">
    <div class="loader-ring"></div>
    <p>Preparing your space...</p>
  </div>

  <!-- ================== HEADER ================== -->
  <header class="site-header" id="top">
    <div class="container header-inner">
      <a class="brand" href="/phpcourse/project7/" aria-label="Tiksha Furnishing Home">
        <span class="brand-mark">TF</span>
        <span class="brand-text">
          <strong>Tiksha Furnishing</strong>
          <small>Premium Curtain Studio</small>
        </span>
      </a>

      <button class="nav-toggle" aria-label="Toggle navigation" aria-expanded="false">
        <span></span><span></span><span></span>
      </button>

      <nav class="site-nav" aria-label="Primary">
        <a href="/phpcourse/project7/">Home</a>
        <a href="/phpcourse/project7/pages/products.php">Products</a>
        <a href="/phpcourse/project7/pages/about.php">About</a>
        <a href="/phpcourse/project7/pages/contact.php">Contact</a>
      </nav>

      <div class="user-nav">
        <!-- LOGIN / USER NAME -->
        <a href="/phpcourse/project7/auth/login.php">
          <?php echo $userName ? "Hi, " . htmlspecialchars($userName) : "Login"; ?>
        </a>

        <?php if ($userName): ?>
          <a href="/phpcourse/project7/auth/logout.php">Logout</a>
        <?php endif; ?>
      </div>
    </div>
  </header>

  <!-- ================== MAIN ================== -->
  <main>

    <!-- HERO -->
    <section class="page-hero">
      <div class="container">
        <p class="eyebrow">Create Account</p>
        <h1>Join Tiksha Furnishing</h1>
        <p>Save your favorite designs, request quotes, and manage future orders with ease.</p>
      </div>
    </section>

    <!-- REGISTER FORM -->
    <section class="form-section">
      <div class="container">
        <div class="form-card">

          <div class="form-success form-message" id="register-message">
            Account created! You can now log in.
          </div>

          <form action="register_action.php" method="post" data-validate novalidate>

            <div class="form-grid">

              <div class="form-field">
                <label for="full-name">Full Name</label>
                <input id="full-name" name="full_name" type="text" placeholder="Your full name" required />
                <span class="error-msg"></span>
              </div>

              <div class="form-field">
                <label for="register-email">Email</label>
                <input id="register-email" name="email" type="email" placeholder="you@example.com" required />
                <span class="error-msg"></span>
              </div>

              <div class="form-field">
                <label for="register-phone">Phone</label>
                <input id="register-phone" name="phone" type="tel" placeholder="+91 84878 37129" required />
                <span class="error-msg"></span>
              </div>

              <div class="form-field">
                <label for="register-password">Password</label>
                <input id="register-password" name="password" type="password" placeholder="Create a password" required />
                <span class="error-msg"></span>
              </div>

              <div class="form-field">
                <label for="confirm-password">Confirm Password</label>
                <input id="confirm-password" name="confirm_password" type="password" placeholder="Confirm your password" required />
                <span class="error-msg"></span>
              </div>

            </div>

            <button class="btn primary" type="submit">Register</button>

            <p style="margin-top:16px;">
              Already have an account?
              <a href="login.php" style="color:var(--gold);font-weight:600;">Login here</a>.
            </p>

          </form>
        </div>
      </div>
    </section>

  </main>

  <!-- ================== FOOTER ================== -->
  <?php require __DIR__ . '/../includes/footer.php'; ?>
</body>
</html>
