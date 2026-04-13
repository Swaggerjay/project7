<?php
// pages/edit_profile.php
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

// Handle POST request to update profile
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = trim($_POST['full_name'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $address = trim($_POST['address'] ?? '');
    $username = trim($_POST['username'] ?? '');

    // Basic validation
    if ($full_name === '') {
        $error_msg = "Full Name is required.";
    } else {
        // Update DB
        $update_stmt = $conn->prepare('UPDATE users SET full_name = ?, phone = ?, address = ?, username = ? WHERE user_id = ?');
        $update_stmt->bind_param('ssssi', $full_name, $phone, $address, $username, $user_id);
        
        if ($update_stmt->execute()) {
            $success_msg = "Profile updated successfully!";
            // Update session name if changed
            $_SESSION['user_name'] = $full_name;
        } else {
            $error_msg = "Error updating profile. Please try again.";
        }
        $update_stmt->close();
    }
}

// Fetch current user details
$stmt = $conn->prepare('SELECT full_name, email, phone, address, username FROM users WHERE user_id = ? LIMIT 1');
$stmt->bind_param('i', $user_id);
$stmt->execute();
$stmt->bind_result($current_full_name, $current_email, $current_phone, $current_address, $current_username);
$found = $stmt->fetch();
$stmt->close();

if (!$found) {
    header("Location: /phpcourse/project7/auth/login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile - Tiksha Furnishing</title>
    <!-- Include Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Include existing styles -->
    <link rel="stylesheet" href="/phpcourse/project7/css/styles.css">
    <style>
        .profile-card {
            max-width: 600px;
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
            <h4 class="mb-0">Edit Profile</h4>
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

            <form action="edit_profile.php" method="POST">
                <div class="mb-3">
                    <label for="email" class="form-label text-muted">Email (Cannot be changed)</label>
                    <input type="email" class="form-control bg-light" id="email" value="<?= htmlspecialchars($current_email) ?>" readonly disabled>
                </div>

                <div class="mb-3">
                    <label for="username" class="form-label text-muted">Username</label>
                    <input type="text" class="form-control" name="username" id="username" value="<?= htmlspecialchars($current_username ?? '') ?>" placeholder="Choose a username">
                </div>

                <div class="mb-3">
                    <label for="full_name" class="form-label text-muted">Full Name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="full_name" id="full_name" value="<?= htmlspecialchars($current_full_name) ?>" required>
                </div>

                <div class="mb-3">
                    <label for="phone" class="form-label text-muted">Contact Number</label>
                    <input type="text" class="form-control" name="phone" id="phone" value="<?= htmlspecialchars($current_phone ?? '') ?>" placeholder="e.g. +1234567890">
                </div>

                <div class="mb-4">
                    <label for="address" class="form-label text-muted">Address</label>
                    <textarea class="form-control" name="address" id="address" rows="3" placeholder="Enter your full address"><?= htmlspecialchars($current_address ?? '') ?></textarea>
                </div>

                <div class="d-flex justify-content-between align-items-center border-top pt-4">
                    <a href="profile.php" class="text-decoration-none text-secondary">Back to Profile</a>
                    <button type="submit" class="btn btn-primary px-4">Save Changes</button>
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
