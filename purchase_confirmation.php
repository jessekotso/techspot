<?php
session_start();
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

    // Assuming a successful purchase, save the purchase details into the orders table (if available)
    if (isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id'];
        $order_stmt = $pdo->prepare("INSERT INTO orders (user_id, product_id, quantity, total_price, order_date) VALUES (?, ?, ?, ?, NOW())");
        $order_stmt->execute([$user_id, $product_id, 1, $product['price']]); // Assuming 1 quantity for simplicity
        $order_id = $pdo->lastInsertId();
    } else {
        $order_id = null;
    }
} catch (PDOException $e) {
    echo "Error fetching product: " . $e->getMessage();
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Purchase Confirmation | Tech Spot</title>
    <link rel="stylesheet" href="css/styles.css"> <!-- Ensure this points to your main stylesheet -->
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f4f4;
            color: #333;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .confirmation-container {
            background-color: #fff;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0px 4px 20px rgba(0, 0, 0, 0.1);
            max-width: 500px;
            width: 100%;
            text-align: center;
        }

        .confirmation-container h2 {
            margin-bottom: 30px;
            font-size: 2rem;
            color: #333;
        }

        .confirmation-container img {
            max-width: 100%;
            height: auto;
            margin-bottom: 20px;
            border-radius: 8px;
        }

        .confirmation-container p {
            font-size: 1.2rem;
            margin-bottom: 15px;
            color: #555;
        }

        .confirmation-container .price {
            font-size: 1.5rem;
            color: #e67e22;
            margin-bottom: 25px;
        }

        .confirmation-container .btn {
            background-color: #1abc9c;
            color: white;
            padding: 12px 20px;
            text-decoration: none;
            font-size: 1.2rem;
            border-radius: 5px;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }

        .confirmation-container .btn:hover {
            background-color: #16a085;
            transform: scale(1.05);
        }

        .confirmation-container .order-id {
            font-size: 1rem;
            color: #777;
            margin-top: 20px;
        }
    </style>
</head>
<body>

<div class="confirmation-container">
    <h2>Thank you for your purchase!</h2>
    <img src="img/<?= htmlspecialchars($product['image']) ?>" alt="<?= htmlspecialchars($product['name']) ?>">
    <p><strong>Product:</strong> <?= htmlspecialchars($product['name']) ?></p>
    <p><strong>Description:</strong> <?= htmlspecialchars($product['description']) ?></p>
    <p class="price"><strong>Total Price:</strong> $<?= htmlspecialchars(number_format($product['price'], 2)) ?></p>

    <a href="index.php" class="btn">Return to Home</a>

    <?php if ($order_id): ?>
        <p class="order-id">Your Order ID: <?= htmlspecialchars($order_id) ?></p>
    <?php else: ?>
        <p class="order-id">Please note: You were not logged in, so your order was not recorded in our system.</p>
    <?php endif; ?>
</div>

</body>
</html>

<?php include 'templates/footer.php'; ?>
