<?php
session_start();
include 'php/db.php'; // Include the database connection

$error = ''; // Initialize the error variable

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize and validate input
    $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
    $description = filter_var($_POST['description'], FILTER_SANITIZE_STRING);
    $image = $_FILES['image'];

    // Check if the file was uploaded without errors
    if ($image['error'] === UPLOAD_ERR_OK) {
        $fileName = basename($image['name']);
        $fileTmpPath = $image['tmp_name'];
        $fileType = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        $allowedFileTypes = ['jpg', 'jpeg', 'png', 'gif'];

        // Validate file type
        if (in_array($fileType, $allowedFileTypes)) {
            $uploadDir = 'service_images/';
            $filePath = $uploadDir . $fileName;

            // Move the file to the upload directory
            if (move_uploaded_file($fileTmpPath, $filePath)) {
                try {
                    // Insert the service data into the database
                    $stmt = $pdo->prepare("INSERT INTO services (name, description, image) VALUES (:name, :description, :image)");
                    $stmt->bindParam(':name', $name);
                    $stmt->bindParam(':description', $description);
                    $stmt->bindParam(':image', $filePath);
                    $stmt->execute();

                    // Redirect or show success message
                    $_SESSION['success_message'] = 'Service added successfully!';
                    header('Location: manage_services.php');
                    exit();
                } catch (PDOException $e) {
                    $error = 'Error saving service: ' . $e->getMessage();
                }
            } else {
                $error = 'There was an error uploading the file.';
            }
        } else {
            $error = 'Invalid file type. Only JPG, JPEG, PNG, and GIF files are allowed.';
        }
    } else {
        $error = 'File upload error. Please try again.';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Service | Tech Spot</title>
    <link rel="stylesheet" href="css/admin.css">
    <style>
        .container {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0px 4px 20px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #2c3e50;
        }

        .form-group {
            margin-bottom: 15px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #34495e;
        }

        input[type="text"], 
        textarea, 
        input[type="file"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 1rem;
            box-sizing: border-box;
        }

        textarea {
            resize: vertical;
        }

        input[type="submit"] {
            width: 100%;
            padding: 15px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1rem;
            transition: background-color 0.3s;
            margin-top: 10px;
        }

        input[type="submit"]:hover {
            background-color: #0056b3;
        }

        .error-message {
            color: #e74c3c;
            font-size: 0.9rem;
            margin-bottom: 15px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Add New Service</h2>
        <?php if (!empty($error)): ?>
            <div class="error-message"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        <form action="" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="name">Service Name</label>
                <input type="text" id="name" name="name" required>
            </div>

            <div class="form-group">
                <label for="description">Description</label>
                <textarea id="description" name="description" required></textarea>
            </div>

            <div class="form-group">
                <label for="image">Service Image</label>
                <input type="file" id="image" name="image" accept="image/*" required>
            </div>

            <input type="submit" value="Add Service">
        </form>
    </div>
</body>
</html>
