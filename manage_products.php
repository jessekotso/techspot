<?php
session_start();
include 'php/db.php'; // Include the database connection

try {
    // Fetch all products from the database
    $stmt = $pdo->prepare("SELECT * FROM products");
    $stmt->execute();
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error fetching data: " . $e->getMessage();
    exit();
}

// Handle the feature toggle
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['toggle_feature'])) {
    $productId = $_POST['product_id'];
    $newFeatureStatus = $_POST['current_feature_status'] == 1 ? 0 : 1;

    try {
        // Update the 'featured' status in the database
        $stmt = $pdo->prepare("UPDATE products SET featured = ? WHERE id = ?");
        $stmt->execute([$newFeatureStatus, $productId]);

        $success_message = "Product feature status updated successfully.";
    } catch (PDOException $e) {
        $error_message = "Error updating feature status: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Products</title>
    <link rel="stylesheet" href="css/admin.css">
    <style>
        /* Styling for the product images */
        .product-image {
            max-width: 100px;
            height: auto;
            border-radius: 5px;
        }

        .action-buttons button {
            margin-right: 10px;
            padding: 8px 12px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
        }

        .edit-button {
            background-color: #3498db;
            color: #fff;
        }

        .delete-button {
            background-color: #e74c3c;
            color: #fff;
        }

        .feature-button {
            background-color: #f39c12;
            color: #fff;
        }

        .add-button {
            background-color: #2ecc71;
            color: #fff;
            padding: 10px 20px;
            font-size: 16px;
            margin-bottom: 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .success-message {
            color: green;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .error-message {
            color: red;
            font-weight: bold;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <h1>Admin Dashboard</h1>
        <nav>
            <ul>
                <li><a href="manage_users.php">Manage Users</a></li>
                <li><a href="manage_products.php" class="active">Manage Products</a></li>
                <li><a href="manage_services.php">Manage Services</a></li>
                <li><a href="manage_courses.php">Manage Courses</a></li>
                <li><a href="manage_requested_services.php">Manage Requested Services</a></li>
                <li><a href="manage_slideshow.php">Manage Slideshow</a></li>
            </ul>
        </nav>
    </div>
    <div class="content">
        <h2>Manage Products</h2>
        <?php if (isset($success_message)): ?>
            <div class="success-message"><?= htmlspecialchars($success_message) ?></div>
        <?php endif; ?>
        <?php if (isset($error_message)): ?>
            <div class="error-message"><?= htmlspecialchars($error_message) ?></div>
        <?php endif; ?>
        <button class="add-button" onclick="location.href='add_product.php'">Add New Product</button>
        <?php if (!empty($products)): ?>
            <table class="styled-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Image</th>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Price</th>
                        <th>Category</th>
                        <th>Featured</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($products as $product): ?>
                        <tr>
                            <td><?= htmlspecialchars($product['id']) ?></td>
                            <td>
                                <img src="<?= htmlspecialchars($product['product_image']) ?>" alt="<?= htmlspecialchars($product['name']) ?>" class="product-image">
                            </td>
                            <td><?= htmlspecialchars($product['name']) ?></td>
                            <td><?= htmlspecialchars($product['description']) ?></td>
                            <td>$<?= htmlspecialchars(number_format($product['price'], 2)) ?></td>
                            <td><?= htmlspecialchars($product['category']) ?></td>
                            <td><?= htmlspecialchars($product['featured']) ? 'Yes' : 'No' ?></td>
                            <td>
                                <div class="action-buttons">
                                    <button class="edit-button" onclick="location.href='edit_product.php?id=<?= $product['id'] ?>'">Edit</button>
                                    <button class="delete-button" onclick="location.href='delete_product.php?id=<?= $product['id'] ?>'">Delete</button>
                                    <form action="manage_products.php" method="post" style="display:inline;">
                                        <input type="hidden" name="product_id" value="<?= htmlspecialchars($product['id']) ?>">
                                        <input type="hidden" name="current_feature_status" value="<?= htmlspecialchars($product['featured']) ?>">
                                        <button type="submit" name="toggle_feature" class="feature-button">
                                            <?= $product['featured'] ? 'Unfeature' : 'Feature' ?>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="no-data">No products available.</div>
        <?php endif; ?>
    </div>
</body>
</html>
