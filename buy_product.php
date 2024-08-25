<?php
include 'php/db.php'; // Include the database connection

// Get the product ID from the URL
$product_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

try {
    // Fetch product details from the database
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->execute([$product_id]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$product) {
        echo "Product not found!";
        exit();
    }

    // Redirect to the purchase confirmation page
    header("Location: purchase_confirmation.php?id=" . $product_id);
    exit(); // Ensure the script stops executing after the redirect

} catch (PDOException $e) {
    echo "Error fetching product: " . $e->getMessage();
    exit();
}
?>
