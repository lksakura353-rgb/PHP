<?php
require_once 'db_connect.php';

$newItem = [
    'title' => 'GIGABYTE GeForce RTX™ 5070 Ti WINDFORCE OC 16GB GDDR7',
    'price' => 1299.00,
    'image' => 'image/5070ti.png', // User should provide this image or I can use a placeholder
    'link' => 'link/graphic.php',
    'category' => 'gpu',
    'in_stock' => 1
];

// Check if it already exists
$stmt = $pdo->prepare("SELECT id FROM products WHERE title = ?");
$stmt->execute([$newItem['title']]);
if (!$stmt->fetch()) {
    $stmt = $pdo->prepare("INSERT INTO products (title, price, image, link, category, in_stock) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([
        $newItem['title'],
        $newItem['price'],
        $newItem['image'],
        $newItem['link'],
        $newItem['category'],
        $newItem['in_stock']
    ]);
    echo "Inserted 5070 Ti successfully!\n";
} else {
    echo "5070 Ti already exists.\n";
}

// Update other items categories to be consistent
$pdo->exec("UPDATE products SET category = 'gpu' WHERE category = 'gpu' OR category = 'graphics'");
$pdo->exec("UPDATE products SET category = 'phone' WHERE category = 'mobile' OR category = 'phone'");
$pdo->exec("UPDATE products SET category = 'processor' WHERE category = 'cpu' OR category = 'processor'");
$pdo->exec("UPDATE products SET category = 'motherboard' WHERE category = 'motherboard' OR category = 'mobo'");

echo "Categories updated.\n";
?>
