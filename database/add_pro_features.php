<?php
require __DIR__ . '/../config/db_connect.php';
$c = db();

try {
    // 1. Wishlist table
    $c->query("CREATE TABLE IF NOT EXISTS `wishlist` (
        `id` INT AUTO_INCREMENT PRIMARY KEY,
        `user_id` INT NOT NULL,
        `product_id` INT NOT NULL,
        `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        UNIQUE KEY `user_product` (`user_id`, `product_id`)
    )");

    // 2. Reviews table
    $c->query("CREATE TABLE IF NOT EXISTS `reviews` (
        `id` INT AUTO_INCREMENT PRIMARY KEY,
        `product_id` INT NOT NULL,
        `user_id` INT NOT NULL,
        `rating` INT NOT NULL,
        `comment` TEXT,
        `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");

    // 3. Gallery Images (JSON)
    $c->query("ALTER TABLE `products` ADD COLUMN `gallery_images` JSON DEFAULT NULL AFTER `image_path`");

    // Add some dummy gallery images to existing products so they look great
    $dummyGallery1 = json_encode(['/phpcourse/project7/images/real-4.jpg', '/phpcourse/project7/images/real-3.jpg', '/phpcourse/project7/images/real-2.jpg']);
    $dummyGallery2 = json_encode(['/phpcourse/project7/images/real-2.jpg', '/phpcourse/project7/images/real-3.jpg', '/phpcourse/project7/images/real-4.jpg']);
    
    $c->query("UPDATE `products` SET `gallery_images` = '$dummyGallery1' WHERE `product_id` % 2 = 0");
    $c->query("UPDATE `products` SET `gallery_images` = '$dummyGallery2' WHERE `product_id` % 2 != 0");

    // Add some dummy reviews
    // We assume user_id 1 exists (or we'll just insert a dummy user if not needed for foreign key, which we don't strictly have)
    $c->query("INSERT IGNORE INTO `reviews` (`product_id`, `user_id`, `rating`, `comment`) VALUES 
        (1, 1, 5, 'Absolutely love these sheer curtains. They let exactly the right amount of light in!'),
        (1, 2, 4, 'Very good quality material.'),
        (2, 1, 5, 'Perfect blackout for my bedroom. Highly recommend.'),
        (3, 3, 5, 'Luxurious velvet. Feels very premium.')
    ");

    echo "Pro features database migration completed successfully!\n";
} catch (Exception $e) {
    if (strpos($e->getMessage(), "Duplicate column name") !== false) {
        echo "Migration already ran (columns exist).\n";
    } else {
        echo "Error: " . $e->getMessage() . "\n";
    }
}
