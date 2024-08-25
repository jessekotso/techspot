<?php
include 'php/db.php'; // Include the database connection
include 'templates/header.php';

// Get user ID from URL
$user_id = $_GET['id'] ?? null;
if (!$user_id) {
    echo "Invalid user ID";
    exit;
}

// Delete the user from the database
try {
    $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
    echo "User deleted successfully.";
} catch (PDOException $e) {
    echo "Error deleting user: " . $e->getMessage();
}
?>

<div class="container">
    <a href="admin_dashboard.php">Back to Dashboard</a>
</div>

<?php include 'templates/footer.php'; ?>
