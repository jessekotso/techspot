<?php
include 'php/db.php'; // Include the database connection

$course_id = $_GET['id'] ?? null;

if ($course_id) {
    $sql = "SELECT name, description FROM courses WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$course_id]);
    $course = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($course) {
        echo '<h2>' . htmlspecialchars($course['name']) . '</h2>';
        echo '<p><strong>Description:</strong> ' . htmlspecialchars($course['description']) . '</p>';
    } else {
        echo '<p>Course not found.</p>';
    }
} else {
    echo '<p>No course selected.</p>';
}
?>
