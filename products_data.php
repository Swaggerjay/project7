<?php
// products_data.php
require_once __DIR__ . '/db_connect.php';

function products_all(): array
{
    $fallback = [
        1 => ['id' => 1, 'name' => 'Pvory Sheer Weave', 'price' => 3200, 'image' => 'images/real-2.jpg'],
        2 => ['id' => 2, 'name' => 'Mocha Blackout', 'price' => 5600, 'image' => 'images/real-3.jpg'],
        3 => ['id' => 3, 'name' => 'Champagne Velvet', 'price' => 7200, 'image' => 'images/real-4.jpg'],
        4 => ['id' => 4, 'name' => 'Sapphire Velvet', 'price' => 7500, 'image' => 'images/real-2.jpg'],
        5 => ['id' => 5, 'name' => 'Emerald Sheer', 'price' => 4100, 'image' => 'images/real-3.jpg'],
        6 => ['id' => 6, 'name' => 'Azure Weave', 'price' => 3800, 'image' => 'images/real-4.jpg'],
        7 => ['id' => 7, 'name' => 'Ruby Silk Drapes', 'price' => 8900, 'image' => 'images/real-2.jpg'],
        8 => ['id' => 8, 'name' => 'Onyx Blackout', 'price' => 6100, 'image' => 'images/real-3.jpg'],
        9 => ['id' => 9, 'name' => 'Pearl Linen', 'price' => 4500, 'image' => 'images/real-4.jpg'],
        10 => ['id' => 10, 'name' => 'Golden Brocade', 'price' => 9400, 'image' => 'images/real-2.jpg'],
    ];

    try {
        $stmt = db()->prepare('SELECT product_id, name, price, image_path, category, description, specifications FROM products ORDER BY product_id ASC');
        $stmt->execute();
        $stmt->bind_result($id, $name, $price, $imagePath, $category, $description, $specifications);
        $products = [];
        while ($stmt->fetch()) {
            $products[(int) $id] = [
                'id' => (int) $id,
                'name' => $name,
                'price' => (float) $price,
                'image' => $imagePath,
                'category' => $category,
                'description' => $description,
                'specifications' => json_decode($specifications, true) ?: [],
            ];
        }
        $stmt->close();

        if (!empty($products)) {
            return $products;
        }
    } catch (Throwable $e) {
        // Fallback to static data when products table is missing.
    }

    return $fallback;
}

function product_find(int $productId): ?array
{
    $products = products_all();
    return $products[$productId] ?? null;
}
