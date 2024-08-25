<?php
include 'php/db.php'; // Include the database connection
include 'templates/header.php';

// Get the category from the URL
$category = isset($_GET['category']) ? $_GET['category'] : '';

try {
    // Fetch products for the specified category
    $stmt = $pdo->prepare("SELECT * FROM products WHERE category = ?");
    $stmt->execute([$category]);
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (empty($products)) {
        echo "No products found in this category.";
        exit();
    }
} catch (PDOException $e) {
    echo "Error fetching products: " . $e->getMessage();
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($category) ?> Products</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
<div class="container">
    <h1><?= htmlspecialchars($category) ?> Products</h1>
    <div class="product-grid">
        <?php foreach ($products as $product): ?>
            <div class="product-card">
                <img src="img/<?= htmlspecialchars($product['image']) ?>" alt="<?= htmlspecialchars($product['name']) ?>">
                <h3><?= htmlspecialchars($product['name']) ?></h3>
                <p><?= htmlspecialchars($product['description']) ?></p>
                <p><strong>Price:</strong> $<?= htmlspecialchars(number_format($product['price'], 2)) ?></p>
                <a href="product_details.php?id=<?= htmlspecialchars($product['id']) ?>" class="btn">View Details</a>
                <a href="buy_product.php?id=<?= htmlspecialchars($product['id']) ?>" class="btn buy-btn">Buy</a>
            </div>
        <?php endforeach; ?>
    </div>
</div>
</body>
</html>

<?php include 'templates/footer.php'; ?>
