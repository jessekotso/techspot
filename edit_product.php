<?php
include 'php/db.php'; // Include the database connection
include 'templates/header.php';

// Get product ID from URL
$product_id = $_GET['id'] ?? null;
if (!$product_id) {
    echo "Invalid product ID";
    exit;
}

// Fetch product data
try {
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->execute([$product_id]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$product) {
        echo "Product not found.";
        exit;
    }
} catch (PDOException $e) {
    echo "Error fetching product: " . $e->getMessage();
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Update product data
    $name = $_POST['name'] ?? '';
    $description = $_POST['description'] ?? '';
    $price = $_POST['price'] ?? '';
    $category = $_POST['category'] ?? '';
    $featured = isset($_POST['featured']) ? 1 : 0;
    $is_featured = isset($_POST['is_featured']) ? 1 : 0;
    $product_image = $product['product_image'];

    // Handle file upload for product image
    if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
        $product_image = handleFileUpload($_FILES['image'], 'product_images/');
    }

    try {
        $sql = "UPDATE products SET name = ?, description = ?, price = ?, category = ?, featured = ?, is_featured = ?, product_image= ? WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$name, $description, $price, $category, $featured, $is_featured, $product_image, $product_id]);
        $success = "Product updated successfully!";
    } catch (PDOException $e) {
        $error = "Error updating product: " . $e->getMessage();
    }
}

// Function to handle file uploads
function handleFileUpload($file, $uploadDir) {
    if (isset($file) && $file['error'] == UPLOAD_ERR_OK) {
        $tmp_name = $file['tmp_name'];
        $name = $file['name'];
        $uploadFile = $uploadDir . basename($name);

        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        if (move_uploaded_file($tmp_name, $uploadFile)) {
            return $uploadFile;
        }
    }
    return null;
}
?>

<div class="container">
    <h2>Edit Product</h2>
    <?php if (isset($success)): ?>
        <div class="message success"><?= htmlspecialchars($success) ?></div>
    <?php elseif (isset($error)): ?>
        <div class="message error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">
        <label for="name">Product Name:</label>
        <input type="text" id="name" name="name" value="<?= htmlspecialchars($product['name']) ?>" required>

        <label for="description">Description:</label>
        <textarea id="description" name="description" required><?= htmlspecialchars($product['description']) ?></textarea>

        <label for="price">Price:</label>
        <input type="text" id="price" name="price" value="<?= htmlspecialchars($product['price']) ?>" required>

        <label for="category">Category:</label>
        <input type="text" id="category" name="category" value="<?= htmlspecialchars($product['category']) ?>" required>

        <label for="featured">Featured:</label>
        <input type="checkbox" id="featured" name="featured" <?= $product['featured'] ? 'checked' : '' ?>>

        <label for="is_featured">Is Featured:</label>
        <input type="checkbox" id="is_featured" name="is_featured" <?= $product['is_featured'] ? 'checked' : '' ?>>

        <label for="image">Product Image:</label>
        <input type="file" id="image" name="image">
        <?php if ($product['product_image']): ?>
            <img src="<?= htmlspecialchars($product['product_image']) ?>" alt="Product Image" width="100">
        <?php endif; ?>

        <input type="submit" value="Update Product">
    </form>

    <button onclick="history.back()">Back</button>
</div>

<?php include 'templates/footer.php'; ?>
