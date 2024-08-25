<?php
session_start();
include 'php/db.php'; // Include the database connection

try {
    $stmt = $pdo->prepare("SELECT * FROM slideshow");
    $stmt->execute();
    $slides = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error fetching data: " . $e->getMessage();
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Slideshow</title>
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
        <h2>Manage Slideshow</h2>
        <button class="add-button" onclick="location.href='add_slide.php'">Add New Slide</button>
        <?php if (!empty($slides)): ?>
            <table class="styled-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Image</th>
                        <th>Title</th>
                        <th>Description</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($slides as $slide): ?>
                        <tr>
                            <td><?= htmlspecialchars($slide['id']) ?></td>
                            <td><img src="<?= htmlspecialchars($slide['image']) ?>" alt="<?= htmlspecialchars($slide['title']) ?>" style="width: 100px;"></td>
                            <td><?= htmlspecialchars($slide['title']) ?></td>
                            <td><?= htmlspecialchars($slide['description']) ?></td>
                            <td>
                                <div class="action-buttons">
                                    <button class="edit-button" onclick="location.href='edit_slide.php?id=<?= $slide['id'] ?>'">Edit</button>
                                    <button class="delete-button" onclick="location.href='delete_slide.php?id=<?= $slide['id'] ?>'">Delete</button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="no-data">No slides available.</div>
        <?php endif; ?>
    </div>
</body>
</html>
