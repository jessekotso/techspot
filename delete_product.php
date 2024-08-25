<?php
include 'php/db.php'; // Include the database connection
include 'templates/header.php';

// Get product ID from URL
$product_id = $_GET['id'] ?? null;
if (!$product_id) {
    echo "Invalid product ID";
    exit;
}

// Delete the product from the database
try {
    $stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
    $stmt->execute([$product_id]);
    echo "Product deleted successfully.";
} catch (PDOException $e) {
    echo "Error deleting product: " . $e->getMessage();
}
?>

<div class="container">
    <a href="admin_dashboard.php">Back to Dashboard</a>
</div>

<?php include 'templates/footer.php'; ?>
