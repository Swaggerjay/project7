<?php
// cart_helpers.php

function cart_items(): array
{
    return $_SESSION['cart']['items'] ?? [];
}

function cart_count(): int
{
    $count = 0;
    foreach (cart_items() as $qty) {
        $count += (int) $qty;
    }
    return $count;
}

function cart_totals(array $products): array
{
    $items = cart_items();
    $lines = [];
    $total = 0;

    foreach ($items as $productId => $qty) {
        if (!isset($products[$productId])) {
            continue;
        }
        $product = $products[$productId];
        $quantity = max(1, (int) $qty);
        $lineTotal = $product['price'] * $quantity;
        $lines[] = [
            'product' => $product,
            'qty' => $quantity,
            'line_total' => $lineTotal,
        ];
        $total += $lineTotal;
    }

    return ['lines' => $lines, 'total' => $total];
}

function cart_sync_to_db(): void
{
    if (!isset($_SESSION['user_id'])) {
        return;
    }
    
    // We assume db_connect.php is loaded natively by session_bootstrap or individually
    $json = json_encode($_SESSION['cart']['items'] ?? []);
    
    // Fallback error-silencing check in case db connection fails, so user experience isn't totally crashed
    try {
        $stmt = db()->prepare('UPDATE users SET cart_data = ? WHERE user_id = ?');
        $stmt->bind_param('si', $json, $_SESSION['user_id']);
        $stmt->execute();
        $stmt->close();
    } catch (Throwable $e) {
        error_log('Cart sync failed: ' . $e->getMessage());
    }
}
