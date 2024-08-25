<?php
include 'php/db.php'; // Include the database connection
include 'templates/header.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve form data
    $name = $_POST['name'] ?? '';
    $description = $_POST['description'] ?? '';
    $instructor_id = $_POST['instructor_id'] ?? '';
    $duration = $_POST['duration'] ?? '';
    $image_url = '';

    // Handle file upload for course image
    if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
        $image_url = handleFileUpload($_FILES['image'], 'course_images/');
    }

    // Insert new course into the database
    try {
        $sql = "INSERT INTO courses (name, description, instructor_id, duration, image_url) VALUES (?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$name, $description, $instructor_id, $duration, $image_url]);
        $success = "Course added successfully!";
    } catch (PDOException $e) {
        $error = "Error adding course: " . $e->getMessage();
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
    <h2>Add New Course</h2>
    <?php if (isset($success)): ?>
        <div class="message success"><?= htmlspecialchars($success) ?></div>
    <?php elseif (isset($error)): ?>
        <div class="message error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">
        <label for="name">Course Name:</label>
        <input type="text" id="name" name="name" required>

        <label for="description">Description:</label>
        <textarea id="description" name="description" required></textarea>

        <label for="duration">Duration:</label>
        <input type="text" id="duration" name="duration" required>

        <label for="image">Course Image:</label>
        <input type="file" id="image" name="image">

        <input type="submit" value="Add Course">
    </form>

    <button onclick="history.back()">Back</button>
</div>

<?php include 'templates/footer.php'; ?>
