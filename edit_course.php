<?php
include 'php/db.php'; // Include the database connection
include 'templates/header.php';

// Get course ID from URL
$course_id = $_GET['id'] ?? null;
if (!$course_id) {
    echo "Invalid course ID";
    exit;
}

// Fetch course data
try {
    $stmt = $pdo->prepare("SELECT * FROM courses WHERE id = ?");
    $stmt->execute([$course_id]);
    $course = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$course) {
        echo "Course not found.";
        exit;
    }
} catch (PDOException $e) {
    echo "Error fetching course: " . $e->getMessage();
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Update course data
    $name = $_POST['name'] ?? '';
    $description = $_POST['description'] ?? '';
    $duration = $_POST['duration'] ?? '';
    $course_image = $course['course_image'];

    // Handle file upload for course image
    if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
        $course_image = handleFileUpload($_FILES['image'], 'course_images/');
    }

    try {
        $sql = "UPDATE courses SET name = ?, description = ?, duration = ?, course_image = ? WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$name, $description, $duration, $course_image, $course_id]);
        $success = "Course updated successfully!";
    } catch (PDOException $e) {
        $error = "Error updating course: " . $e->getMessage();
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
    <h2>Edit Course</h2>
    <?php if (isset($success)): ?>
        <div class="message success"><?= htmlspecialchars($success) ?></div>
    <?php elseif (isset($error)): ?>
        <div class="message error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">
        <label for="name">Course Name:</label>
        <input type="text" id="name" name="name" value="<?= htmlspecialchars($course['name']) ?>" required>

        <label for="description">Description:</label>
        <textarea id="description" name="description" required><?= htmlspecialchars($course['description']) ?></textarea>

        <label for="duration">Duration:</label>
        <input type="text" id="duration" name="duration" value="<?= htmlspecialchars($course['duration']) ?>" required>

        <label for="image">Course Image:</label>
        <input type="file" id="image" name="image">
        <?php if ($course['course_image']): ?>
            <img src="<?= htmlspecialchars($course['course_image']) ?>" alt="Course Image" width="100">
        <?php endif; ?>

        <input type="submit" value="Update Course">
    </form>

    <button onclick="history.back()">Back</button>
</div>

<?php include 'templates/footer.php'; ?>
