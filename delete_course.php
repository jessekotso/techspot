<?php
include 'php/db.php'; // Include the database connection
include 'templates/header.php';

// Get course ID from URL
$course_id = $_GET['id'] ?? null;
if (!$course_id) {
    echo "Invalid course ID";
    exit;
}

// Delete the course from the database
try {
    $stmt = $pdo->prepare("DELETE FROM courses WHERE id = ?");
    $stmt->execute([$course_id]);
    echo "Course deleted successfully.";
} catch (PDOException $e) {
    echo "Error deleting course: " . $e->getMessage();
}
?>

<div class="container">
    <a href="admin_dashboard.php">Back to Dashboard</a>
</div>

<?php include 'templates/footer.php'; ?>
