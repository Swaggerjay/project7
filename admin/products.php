<?php
require_once __DIR__ . '/admin_session.php';
$next = 'products.php';
require __DIR__ . '/admin_auth.php';

$stmt = db()->prepare('SELECT product_id, name, price, image_path, created_at FROM products ORDER BY created_at DESC');
$stmt->execute();
$stmt->bind_result($p_id, $p_name, $p_price, $p_image, $p_created);
$products = [];
while ($stmt->fetch()) {
    $products[] = [
        'product_id' => $p_id,
        'name' => $p_name,
        'price' => $p_price,
        'image' => $p_image,
        'created_at' => $p_created,
    ];
}
$stmt->close();
$pageTitle = 'Products Catalog';
require __DIR__ . '/includes/admin_header.php';
?>

<div style="margin-bottom:20px; display: flex; justify-content: flex-end;">
    <a class="btn primary" href="product_form.php">Add New Product</a>
</div>

<div class="admin-table-container">
    <table class="admin-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Image</th>
                <th>Name</th>
                <th>Price</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($products as $product): ?>
                <tr>
                    <td><strong>#<?php echo (int) $product['product_id']; ?></strong></td>
                    <td>
                        <img src="<?php echo htmlspecialchars(strpos($product['image'], 'http') === 0 || strpos($product['image'], '/') === 0 ? $product['image'] : '../' . $product['image']); ?>" style="width:50px; height:50px; object-fit:cover; border-radius:5px;">
                    </td>
                    <td><?php echo htmlspecialchars($product['name']); ?></td>
                    <td>₹<?php echo number_format($product['price']); ?></td>
                    <td style="display:flex; gap: 10px;">
                        <a class="btn ghost" href="product_form.php?product_id=<?php echo (int) $product['product_id']; ?>" style="padding: 6px 12px; font-size: 0.85rem;">Edit</a>
                        <form action="product_action.php" method="post" onsubmit="return confirm('Delete this product?');">
                            <input type="hidden" name="product_id" value="<?php echo (int) $product['product_id']; ?>" />
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
