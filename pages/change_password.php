<?php
// pages/change_password.php
require_once __DIR__ . '/../config/db_connect.php';

session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: /phpcourse/project7/auth/login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$conn = db();

$success_msg = '';
$error_msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $old_password = $_POST['old_password'] ?? '';
    $new_password = $_POST['new_password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    if ($old_password === '' || $new_password === '' || $confirm_password === '') {
        $error_msg = "All fields are required.";
    } elseif ($new_password !== $confirm_password) {
        $error_msg = "New password and Confirm password do not match.";
    } elseif (strlen($new_password) < 6) {
        $error_msg = "New password must be at least 6 characters long.";
    } else {
        // Fetch current password hash from DB
        $stmt = $conn->prepare('SELECT password FROM users WHERE user_id = ? LIMIT 1');
        $stmt->bind_param('i', $user_id);
        $stmt->execute();
        $stmt->bind_result($hash);
        $found = $stmt->fetch();
        $stmt->close();

        if ($found && password_verify($old_password, $hash)) {
            // Hash the new password
            $new_hash = password_hash($new_password, PASSWORD_DEFAULT);
            
            // Update the password in database
            $update_stmt = $conn->prepare('UPDATE users SET password = ? WHERE user_id = ?');
            $update_stmt->bind_param('si', $new_hash, $user_id);
            if ($update_stmt->execute()) {
                $success_msg = "Your password has been changed successfully!";
            } else {
                $error_msg = "Failed to update password. Please try again.";
            }
            $update_stmt->close();
        } else {
            $error_msg = "Incorrect old password.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password - Tiksha Furnishing</title>
    <!-- Include Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Include existing styles -->
    <link rel="stylesheet" href="/phpcourse/project7/css/styles.css">
    <style>
        .profile-card {
            max-width: 500px;
            margin: 40px auto;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
            border: none;
        }
        .profile-header {
            background-color: #fafafa;
            border-bottom: 1px solid #ebebeb;
            border-radius: 12px 12px 0 0;
            padding: 20px 30px;
        }
    </style>
</head>
<body>

<?php require_once __DIR__ . '/../includes/header.php'; ?>

<main class="container py-5" style="margin-top: 80px;">
    <div class="card profile-card">
        <div class="profile-header">
            <h4 class="mb-0">Change Password</h4>
        </div>
        <div class="card-body p-4">
            
            <?php if ($error_msg !== ''): ?>
                <div class="alert alert-danger" role="alert">
                    <?= htmlspecialchars($error_msg) ?>
                </div>
            <?php endif; ?>

            <?php if ($success_msg !== ''): ?>
                <div class="alert alert-success" role="alert">
                    <?= htmlspecialchars($success_msg) ?>
                </div>
            <?php endif; ?>

            <form action="change_password.php" method="POST">
                <div class="mb-3">
                    <label for="old_password" class="form-label text-muted">Old Password <span class="text-danger">*</span></label>
                    <input type="password" class="form-control" name="old_password" id="old_password" required>
                </div>

                <div class="mb-3">
                    <label for="new_password" class="form-label text-muted">New Password <span class="text-danger">*</span></label>
                    <input type="password" class="form-control" name="new_password" id="new_password" required minlength="6">
                    <div class="form-text">Must be at least 6 characters long.</div>
                </div>

                <div class="mb-4">
                    <label for="confirm_password" class="form-label text-muted">Confirm New Password <span class="text-danger">*</span></label>
                    <input type="password" class="form-control" name="confirm_password" id="confirm_password" required minlength="6">
                </div>

                <div class="d-flex justify-content-between align-items-center border-top pt-4">
                    <a href="profile.php" class="text-decoration-none text-secondary">Back to Profile</a>
                    <button type="submit" class="btn btn-primary px-4">Update Password</button>
                </div>
            </form>

        </div>
    </div>
</main>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
