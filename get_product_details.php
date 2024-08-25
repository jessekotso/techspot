<?php
include 'php/db.php'; // Include the database connection

$product_id = $_GET['id'] ?? null;

if ($product_id) {
    $sql = "SELECT name, description, price, image_url FROM products WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$product_id]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($product) {
        echo '<h2>' . htmlspecialchars($product['name']) . '</h2>';
        echo '<p><strong>Description:</strong> ' . htmlspecialchars($product['description']) . '</p>';
        echo '<p><strong>Price:</strong> $' . htmlspecialchars($product['price']) . '</p>';
        if (!empty($product['image_url'])) {
            echo '<img src="' . htmlspecialchars($product['image_url']) . '" alt="' . htmlspecialchars($product['name']) . '">';
        }
    } else {
        echo '<p>Product not found.</p>';
    }
} else {
    echo '<p>No product selected.</p>';
}
?>
