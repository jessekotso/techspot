<?php
include 'php/db.php'; // Include the database connection
include 'templates/header.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve form data
    $name = $_POST['name'] ?? '';
    $description = $_POST['description'] ?? '';
    $price = $_POST['price'] ?? '';
    $category = $_POST['category'] ?? '';
    $featured = isset($_POST['featured']) ? 1 : 0;
    $is_featured = isset($_POST['is_featured']) ? 1 : 0;
    $product_image = '';

    // Handle file upload for product image
    if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
        $product_image = handleFileUpload($_FILES['image'], 'product_images/');
    }

    // Insert new product into the database
    try {
        $sql = "INSERT INTO products (name, description, price, category, featured, is_featured, product_image) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$name, $description, $price, $category, $featured, $is_featured, $product_image]);
        $success = "Product added successfully!";
    } catch (PDOException $e) {
        $error = "Error adding product: " . $e->getMessage();
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
    <h2>Add New Product</h2>
    <?php if (isset($success)): ?>
        <div class="message success"><?= htmlspecialchars($success) ?></div>
    <?php elseif (isset($error)): ?>
        <div class="message error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">
        <label for="name">Product Name:</label>
        <input type="text" id="name" name="name" required>

        <label for="description">Description:</label>
        <textarea id="description" name="description" required></textarea>

        <label for="price">Price:</label>
        <input type="text" id="price" name="price" required>

        <label for="category">Category:</label>
        <input type="text" id="category" name="category" required>

        <label for="featured">Featured:</label>
        <input type="checkbox" id="featured" name="featured">

        <label for="is_featured">Is Featured:</label>
        <input type="checkbox" id="is_featured" name="is_featured">

        <label for="image">Product Image:</label>
        <input type="file" id="image" name="image">

        <input type="submit" value="Add Product">
    </form>

    <button onclick="history.back()">Back</button>
</div>

<?php include 'templates/footer.php'; ?>
