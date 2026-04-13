<?php
// pages/profile.php
require_once __DIR__ . '/../config/db_connect.php';

session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: /phpcourse/project7/auth/login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$conn = db();

// Fetch user details
$stmt = $conn->prepare('SELECT full_name, email, phone, address, username FROM users WHERE user_id = ? LIMIT 1');
$stmt->bind_param('i', $user_id);
$stmt->execute();
$stmt->bind_result($full_name, $email, $phone, $address, $username);
$found = $stmt->fetch();
$stmt->close();

if (!$found) {
    // Failsafe if user deleted from DB but session exists
    unset($_SESSION['user_id'], $_SESSION['user_name']);
    header("Location: /phpcourse/project7/auth/login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile - Tiksha Furnishing</title>
    <!-- Include basic bootstrap for a simple, clean UI -->
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
        .profile-img-placeholder {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            background-color: #f0f0f0;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 36px;
            color: #ccc;
            margin: 0 auto 20px;
        }
        .profile-header {
            background-color: #fafafa;
            border-bottom: 1px solid #ebebeb;
            border-radius: 12px 12px 0 0;
            padding: 30px;
            text-align: center;
        }
    </style>
</head>
<body>

<?php require_once __DIR__ . '/../includes/header.php'; ?>

<main class="container py-5" style="margin-top: 80px;">
    <div class="card profile-card">
        <div class="profile-header">
            <div class="profile-img-placeholder">
                <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                    <circle cx="12" cy="7" r="4"></circle>
                </svg>
            </div>
            <h3 class="mb-0"><?= htmlspecialchars($full_name) ?></h3>
            <p class="text-muted mt-1"><?= htmlspecialchars($email) ?></p>
        </div>
        <div class="card-body p-4">
            <h5 class="mb-4 border-bottom pb-2">Profile Details</h5>
            
            <div class="row mb-3">
                <div class="col-sm-4 text-muted">Full Name</div>
                <div class="col-sm-8 fw-semibold"><?= htmlspecialchars($full_name) ?></div>
            </div>
            
            <div class="row mb-3">
                <div class="col-sm-4 text-muted">Email</div>
                <div class="col-sm-8 fw-semibold"><?= htmlspecialchars($email) ?></div>
            </div>

            <div class="row mb-3">
                <div class="col-sm-4 text-muted">Username</div>
                <div class="col-sm-8 fw-semibold"><?= $username ? htmlspecialchars($username) : '<em>Not set</em>' ?></div>
            </div>

            <div class="row mb-3">
                <div class="col-sm-4 text-muted">Contact Number</div>
                <div class="col-sm-8 fw-semibold"><?= $phone ? htmlspecialchars($phone) : '<em>Not set</em>' ?></div>
            </div>

            <div class="row mb-4">
                <div class="col-sm-4 text-muted">Address</div>
                <div class="col-sm-8 fw-semibold"><?= $address ? nl2br(htmlspecialchars($address)) : '<em>Not set</em>' ?></div>
            </div>

            <div class="d-flex gap-3 justify-content-center border-top pt-4">
                <a href="edit_profile.php" class="btn btn-outline-primary px-4">Edit Profile</a>
                <a href="change_password.php" class="btn btn-outline-secondary px-4">Change Password</a>
            </div>
        </div>
    </div>
</main>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>

<!-- Bootstrap JS needed for offcanvas/navbar interactions if needed -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
