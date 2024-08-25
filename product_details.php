<?php
session_start();
ob_start(); // Start output buffering
include 'php/db.php'; // Include the database connection
include 'templates/header.php';

// Check if the product ID is provided via GET
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "No product selected!";
    exit();
}

$product_id = intval($_GET['id']);

// Fetch the product details from the database
try {
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->execute([$product_id]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$product) {
        echo "Product not found!";
        exit();
    }

    // Fetch related products from the same category
    $related_stmt = $pdo->prepare("SELECT * FROM products WHERE category = ? AND id != ? ORDER BY RAND() LIMIT 4");
    $related_stmt->execute([$product['category'], $product_id]);
    $related_products = $related_stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error fetching product: " . $e->getMessage();
    exit();
}

// Handle adding the product to the cart (or direct purchase)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['buy'])) {
    if (isset($_SESSION['user_id'])) {
        header("Location: purchase_confirmation.php?id=$product_id");
    } else {
        header("Location: login.php?redirect=purchase_confirmation.php?id=$product_id");
    }
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($product['name']) ?> | Tech Spot</title>
    <link rel="stylesheet" href="css/styles.css"> <!-- Ensure this points to your main stylesheet -->
    <style>
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        .product-details {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }

        .product-image {
            flex: 1 1 40%;
            max-width: 400px;
            margin: auto;
        }

        .product-image img {
            width: 100%;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .product-info {
            flex: 1 1 60%;
        }

        .product-info h1 {
            font-size: 2.5rem;
            margin-bottom: 20px;
            color: #2c3e50;
        }

        .product-info p {
            font-size: 1.2rem;
            margin-bottom: 20px;
            color: #555;
        }

        .product-info .price {
            font-size: 2rem;
            color: #e67e22;
            margin-bottom: 20px;
        }

        .product-info button {
            background-color: #1abc9c;
            color: white;
            padding: 15px 30px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1.2rem;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }

        .product-info button:hover {
            background-color: #16a085;
            transform: scale(1.05);
        }

        .related-products {
            margin-top: 50px;
        }

        .related-products h2 {
            font-size: 2rem;
            margin-bottom: 20px;
            color: #34495e;
            text-align: center;
        }

        .related-products .grid-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
        }

        .related-products .card {
            background: white;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            text-align: center;
        }

        .related-products .card img {
            max-width: 100%;
            border-radius: 8px;
            margin-bottom: 15px;
        }

        .related-products .card h3 {
            margin: 15px 0;
            color: #2c3e50;
        }

        .related-products .card p {
            font-size: 1rem;
            color: #555;
        }

        .related-products .card .price {
            font-size: 1.2rem;
            color: #e67e22;
            margin: 15px 0;
        }

        .related-products .card button {
            background-color: #1abc9c;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            margin-top: 10px;
        }

        .related-products .card button:hover {
            background-color: #16a085;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="product-details">
        <div class="product-image">
            <img src="img/<?= htmlspecialchars($product['image']) ?>" alt="<?= htmlspecialchars($product['name']) ?>">
        </div>
        <div class="product-info">
            <h1><?= htmlspecialchars($product['name']) ?></h1>
            <p><?= htmlspecialchars($product['description']) ?></p>
            <div class="price">$<?= htmlspecialchars(number_format($product['price'], 2)) ?></div>
            <form method="POST">
                <button type="submit" name="buy">Buy Now</button>
            </form>
        </div>
    </div>

    <div class="related-products">
        <h2>Related Products</h2>
        <div class="grid-container">
            <?php foreach ($related_products as $related): ?>
                <div class="card">
                    <img src="img/<?= htmlspecialchars($related['image']) ?>" alt="<?= htmlspecialchars($related['name']) ?>">
                    <h3><?= htmlspecialchars($related['name']) ?></h3>
                    <p><?= htmlspecialchars($related['description']) ?></p>
                    <div class="price">$<?= htmlspecialchars(number_format($related['price'], 2)) ?></div>
                    <button onclick="location.href='product_details.php?id=<?= htmlspecialchars($related['id']) ?>'">View Details</button>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

</body>
</html>

<?php
ob_end_flush(); // Flush the output buffer
include 'templates/footer.php'; 
?>
