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
$pageTitle = 'Registered Users';
require __DIR__ . '/includes/admin_header.php';
?>

<div class="admin-table-container">
    <table class="admin-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Joined</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $user): ?>
                <tr>
                    <td><strong>#<?php echo (int) $user['user_id']; ?></strong></td>
                    <td><?php echo htmlspecialchars($user['full_name']); ?></td>
                    <td><?php echo htmlspecialchars($user['email']); ?></td>
                    <td><?php echo htmlspecialchars($user['phone']); ?></td>
                    <td><?php echo htmlspecialchars($user['created_at']); ?></td>
                    <td style="display:flex; gap: 10px;">
                        <a class="btn ghost" href="user_form.php?user_id=<?php echo (int) $user['user_id']; ?>" style="padding: 6px 12px; font-size: 0.85rem;">Edit</a>
                        <form action="user_action.php" method="post" style="display:inline-flex;" onsubmit="return confirm('Delete this user? All their orders will also be removed.')">
                            <input type="hidden" name="user_id" value="<?php echo (int) $user['user_id']; ?>" />
                            <input type="hidden" name="action" value="delete" />
                            <button class="btn ghost" type="submit" style="padding: 6px 12px; font-size: 0.85rem; color: #dc3545; border-color: #dc3545;">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php require __DIR__ . '/includes/admin_footer.php'; ?>
