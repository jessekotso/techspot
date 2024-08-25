<?php
session_start();
include 'php/db.php'; // Include the database connection

// Fetch all services from the database
try {
    $stmt = $pdo->prepare("SELECT * FROM services");
    $stmt->execute();
    $services = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error fetching data: " . $e->getMessage();
    exit();
}

// Handle feature toggle
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['toggle_feature'])) {
    $serviceId = $_POST['service_id'];
    $newFeatureStatus = $_POST['current_feature_status'] == 1 ? 0 : 1;

    try {
        // Update the 'featured' status in the database
        $stmt = $pdo->prepare("UPDATE services SET featured = ? WHERE id = ?");
        $stmt->execute([$newFeatureStatus, $serviceId]);

        // Refresh the page to reflect the changes
        header("Location: manage_services.php");
        exit();
    } catch (PDOException $e) {
        echo "Error updating feature status: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Services</title>
    <link rel="stylesheet" href="css/admin.css">
    <style>
        /* Additional styles */
        .service-image {
            max-width: 100px;
            border-radius: 8px;
        }

        .action-buttons button {
            margin-right: 10px;
            padding: 5px 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .edit-button {
            background-color: #3498db;
            color: white;
        }

        .delete-button {
            background-color: #e74c3c;
            color: white;
        }

        .feature-button {
            background-color: #f39c12;
            color: white;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <h1>Admin Dashboard</h1>
        <nav>
            <ul>
                <li><a href="manage_users.php">Manage Users</a></li>
                <li><a href="manage_products.php">Manage Products</a></li>
                <li><a href="manage_services.php" class="active">Manage Services</a></li>
                <li><a href="manage_courses.php">Manage Courses</a></li>
                <li><a href="manage_requested_services.php">Manage Requested Services</a></li>
                <li><a href="manage_slideshow.php">Manage Slideshow</a></li>
            </ul>
        </nav>
    </div>
    <div class="content">
        <h2>Manage Services</h2>
        <button class="add-button" onclick="location.href='add_service.php'">Add New Service</button>
        <?php if (!empty($services)): ?>
            <table class="styled-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Image</th>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Featured</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($services as $service): ?>
                        <tr>
                            <td><?= htmlspecialchars($service['id']) ?></td>
                            <td><img src="<?= htmlspecialchars($service['image']) ?>" alt="<?= htmlspecialchars($service['name']) ?>" class="service-image"></td>
                            <td><?= htmlspecialchars($service['name']) ?></td>
                            <td><?= htmlspecialchars($service['description']) ?></td>
                            <td><?= $service['featured'] ? 'Yes' : 'No' ?></td>
                            <td>
                                <div class="action-buttons">
                                    <button class="edit-button" onclick="location.href='edit_service.php?id=<?= $service['id'] ?>'">Edit</button>
                                    <button class="delete-button" onclick="location.href='delete_service.php?id=<?= $service['id'] ?>'">Delete</button>
                                    <form action="manage_services.php" method="post" style="display:inline;">
                                        <input type="hidden" name="service_id" value="<?= htmlspecialchars($service['id']) ?>">
                                        <input type="hidden" name="current_feature_status" value="<?= htmlspecialchars($service['featured']) ?>">
                                        <button type="submit" name="toggle_feature" class="feature-button">
                                            <?= $service['featured'] ? 'Unfeature' : 'Feature' ?>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="no-data">No services available.</div>
        <?php endif; ?>
    </div>
</body>
</html>
