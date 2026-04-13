<?php
require_once __DIR__ . '/admin_session.php';
$next = 'products.php';
require __DIR__ . '/admin_auth.php';

$productId = (int) ($_GET['product_id'] ?? 0);
$product = [
    'product_id' => 0,
    'name' => '',
    'price' => '',
    'category' => '',
    'description' => '',
    'image_path' => '',
];

if ($productId > 0) {
    $stmt = db()->prepare('SELECT product_id, name, price, category, description, image_path FROM products WHERE product_id = ? LIMIT 1');
    $stmt->bind_param('i', $productId);
    $stmt->execute();
    $stmt->bind_result($p_id, $p_name, $p_price, $p_cat, $p_desc, $p_image);
    if ($stmt->fetch()) {
        $product = [
            'product_id' => $p_id,
            'name' => $p_name,
            'price' => $p_price,
            'category' => $p_cat,
            'description' => $p_desc,
            'image_path' => $p_image,
        ];
    }
    $stmt->close();
}

$pageTitle = $product['product_id'] ? 'Edit Product' : 'Add New Product';
require __DIR__ . '/includes/admin_header.php';
?>

<div style="margin-bottom: 20px;"><a href="products.php" style="color: var(--gold);">&larr; Back to Products</a></div>

<div class="card" style="box-shadow: 0 4px 15px rgba(0,0,0,0.03); max-width: 900px; padding: 30px;">
    <form action="product_action.php" method="post" enctype="multipart/form-data">
        <input type="hidden" name="product_id" value="<?php echo (int) $product['product_id']; ?>" />
        <input type="hidden" name="action" value="<?php echo $product['product_id'] ? 'update' : 'create'; ?>" />
        
        <div class="form-grid" style="grid-template-columns: 1fr 1fr;">
            <div class="form-field">
                <label style="font-weight: bold; margin-bottom: 8px; display: block;">Product Name</label>
                <input type="text" name="name" value="<?php echo htmlspecialchars($product['name']); ?>" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;" />
            </div>
            <div class="form-field">
                <label style="font-weight: bold; margin-bottom: 8px; display: block;">Category</label>
                <input type="text" name="category" value="<?php echo htmlspecialchars($product['category'] ?? ''); ?>" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;" placeholder="e.g. Premium Drapes" />
            </div>
            <div class="form-field">
                <label style="font-weight: bold; margin-bottom: 8px; display: block;">Price (₹)</label>
                <input type="number" step="0.01" name="price" value="<?php echo htmlspecialchars((string) $product['price']); ?>" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;" />
            </div>
        </div>

        <div class="form-field" style="margin-top: 25px;">
            <label style="font-weight: bold; margin-bottom: 8px; display: block;">Rich Description</label>
            <textarea id="richEditor" name="description" rows="8"><?php echo htmlspecialchars($product['description'] ?? ''); ?></textarea>
        </div>

        <h3 style="margin-top: 30px; border-bottom: 1px solid #eee; padding-bottom: 10px;">Media Management</h3>
        
        <div class="form-grid" style="grid-template-columns: 1fr 1fr; gap: 30px; margin-top: 20px;">
            <div style="background: #fafbfe; padding: 20px; border-radius: 8px; border: 1px dashed #ccc;">
                <label style="font-weight: bold; display: block; margin-bottom: 10px;">Primary Display Image</label>
                <?php if ($product['image_path']): ?>
                    <img src="<?php echo htmlspecialchars(strpos($product['image_path'], 'http') === 0 || strpos($product['image_path'], '/') === 0 ? $product['image_path'] : '../' . $product['image_path']); ?>" style="width: 100px; height: 100px; object-fit: cover; border-radius: 8px; margin-bottom: 15px; display: block;">
                <?php endif; ?>
                <input type="file" name="primary_image" accept="image/*" />
                <p style="font-size: 0.8rem; color: #888; margin-top: 10px;">Leave blank to keep existing image.</p>
            </div>
            
            <div style="background: #fafbfe; padding: 20px; border-radius: 8px; border: 1px dashed #ccc;">
                <label style="font-weight: bold; display: block; margin-bottom: 10px;">Detail Gallery Images (Up to 5)</label>
                <input type="file" name="gallery_images[]" multiple accept="image/*" />
                <p style="font-size: 0.8rem; color: #888; margin-top: 10px;">Upload multiple angles. Will overwrite existing gallery.</p>
            </div>
        </div>

        <div style="margin-top: 30px; border-top: 1px solid #eee; padding-top: 20px; text-align: right;">
            <button class="btn primary large" type="submit" style="padding: 12px 30px; font-size: 1.1rem;">Save Product</button>
        </div>
    </form>
</div>

<!-- TinyMCE CDN -->
<script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
<script>
  tinymce.init({
    selector: '#richEditor',
    menubar: false,
    plugins: 'lists link',
    toolbar: 'undo redo | formatselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link',
    height: 300,
    branding: false
  });
</script>

<?php require __DIR__ . '/includes/admin_footer.php'; ?>
