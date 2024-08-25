<?php
session_start();
include 'php/db.php'; // Include the database connection

$success_message = '';
$error_message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $image = '';

    if (!empty($_FILES['image']['name'])) {
        $target_dir = "uploads/slides/";
        $target_file = $target_dir . basename($_FILES["image"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Check if image file is a valid image type
        $check = getimagesize($_FILES["image"]["tmp_name"]);
        if ($check !== false) {
            // Upload the file
            if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                $image = $target_file;

                // Insert into the database
                $stmt = $pdo->prepare("INSERT INTO slideshow (title, description, image) VALUES (?, ?, ?)");
                if ($stmt->execute([$title, $description, $image])) {
                    $success_message = "Slide added successfully!";
                } else {
                    $error_message = "Error adding slide.";
                }
            } else {
                $error_message = "Sorry, there was an error uploading your file.";
            }
        } else {
            $error_message = "File is not an image.";
        }
    } else {
        $error_message = "Please select an image to upload.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Slide</title>
    <link rel="stylesheet" href="css/admin.css">
</head>
<body>
    <div class="sidebar">
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
        <h2>Add New Slide</h2>
        <?php if ($success_message): ?>
            <div class="message success"><?= htmlspecialchars($success_message) ?></div>
        <?php endif; ?>
        <?php if ($error_message): ?>
            <div class="message error"><?= htmlspecialchars($error_message) ?></div>
        <?php endif; ?>
        <form action="add_slide.php" method="POST" enctype="multipart/form-data">
            <label for="title">Title:</label>
            <input type="text" name="title" id="title" required>
            <label for="description">Description:</label>
            <textarea name="description" id="description" required></textarea>
            <label for="image">Upload Image:</label>
            <input type="file" name="image" id="image" accept="image/*" required>
            <button type="submit">Add Slide</button>
        </form>
    </div>
</body>
</html>
