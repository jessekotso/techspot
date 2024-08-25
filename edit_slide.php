<?php
session_start();
include 'php/db.php'; // Include the database connection

$success_message = '';
$error_message = '';
$slide_id = intval($_GET['id']);

// Fetch existing slide data
$stmt = $pdo->prepare("SELECT * FROM slideshow WHERE id = ?");
$stmt->execute([$slide_id]);
$slide = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$slide) {
    echo "Slide not found.";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $image_url = $slide['image_url'];

    if (!empty($_FILES['image']['name'])) {
        $target_dir = "uploads/slides/";
        $target_file = $target_dir . basename($_FILES["image"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Check if image file is a valid image type
        $check = getimagesize($_FILES["image"]["tmp_name"]);
        if ($check !== false) {
            // Upload the file
            if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                $image_url = $target_file;
            } else {
                $error_message = "Sorry, there was an error uploading your file.";
            }
        } else {
            $error_message = "File is not an image.";
        }
    }

    if (!$error_message) {
        $stmt = $pdo->prepare("UPDATE slideshow SET title = ?, description = ?, image_url = ? WHERE id = ?");
        if ($stmt->execute([$title, $description, $image_url, $slide_id])) {
            $success_message = "Slide updated successfully!";
        } else {
            $error_message = "Error updating slide.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Slide</title>
    <link rel="stylesheet" href="css/admin.css">
</head>
<body>
<   div class="sidebar">
        <h1>Admin Dashboard</h1>
        <nav>
            <ul>
                <li><a href="manage_users.php" class="active">Manage Users</a></li>
                <li><a href="manage_products.php">Manage Products</a></li>
                <li><a href="manage_services.php">Manage Services</a></li>
                <li><a href="manage_courses.php">Manage Courses</a></li>
                <li><a href="manage_requested_services.php">Manage Requested Services</a></li>
                <li><a href="manage_slideshow.php">Manage Slideshow</a></li>
            </ul>
        </nav>
    </div>
    <div class="content">
        <h2>Edit Slide</h2>
        <?php if ($success_message): ?>
            <div class="message success"><?= htmlspecialchars($success_message) ?></div>
        <?php endif; ?>
        <?php if ($error_message): ?>
            <div class="message error"><?= htmlspecialchars($error_message) ?></div>
        <?php endif; ?>
        <form action="edit_slide.php?id=<?= htmlspecialchars($slide_id) ?>" method="POST" enctype="multipart/form-data">
            <label for="title">Title:</label>
            <input type="text" name="title" id="title" value="<?= htmlspecialchars($slide['title']) ?>" required>
            <label for="description">Description:</label>
            <textarea name="description" id="description" required><?= htmlspecialchars($slide['description']) ?></textarea>
            <label for="image">Upload New Image (optional):</label>
            <input type="file" name="image" id="image" accept="image/*">
            <img src="<?= htmlspecialchars($slide['image_url']) ?>" alt="Current Slide" style="width: 200px; margin-top: 10px;">
            <button type="submit">Update Slide</button>
        </form>
    </div>
</body>
</html>
