<?php
session_start();
include 'php/db.php'; // Include the database connection

try {
    // Fetch all courses
    $stmt = $pdo->prepare("SELECT id, name, description, duration, course_image, featured FROM courses");
    $stmt->execute();
    $courses = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Fetch all staff users
    $stmt = $pdo->prepare("SELECT id, first_name, last_name FROM users WHERE user_type = 'staff'");
    $stmt->execute();
    $staffUsers = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    echo "Error fetching data: " . $e->getMessage();
    exit();
}

// Handle the course assignment
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['assign_course'])) {
    $courseId = $_POST['course_id'];
    $staffId = $_POST['staff_id'];

    try {
        // Update the course to assign it to the selected staff member
        $stmt = $pdo->prepare("UPDATE courses SET assigned_to = ? WHERE id = ?");
        $stmt->execute([$staffId, $courseId]);

        $success_message = "Course assigned successfully.";
    } catch (PDOException $e) {
        $error_message = "Error assigning course: " . $e->getMessage();
    }
}

// Handle the feature toggle
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['toggle_feature'])) {
    $courseId = $_POST['course_id'];
    $newFeatureStatus = $_POST['current_feature_status'] == 1 ? 0 : 1;

    try {
        // Update the 'featured' status in the database
        $stmt = $pdo->prepare("UPDATE courses SET featured = ? WHERE id = ?");
        $stmt->execute([$newFeatureStatus, $courseId]);

        $success_message = "Course feature status updated successfully.";
    } catch (PDOException $e) {
        $error_message = "Error updating feature status: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Courses</title>
    <link rel="stylesheet" href="css/admin.css">
    <style>
        .content {
            padding: 20px;
        }

        .styled-table {
            width: 100%;
            border-collapse: collapse;
            margin: 25px 0;
            font-size: 1.2em;
            text-align: left;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.15);
        }

        .styled-table thead tr {
            background-color: #009879;
            color: #ffffff;
            text-align: left;
        }

        .styled-table th, .styled-table td {
            padding: 12px 15px;
        }

        .styled-table tbody tr {
            border-bottom: 1px solid #dddddd;
        }

        .styled-table tbody tr:nth-of-type(even) {
            background-color: #f3f3f3;
        }

        .styled-table tbody tr:last-of-type {
            border-bottom: 2px solid #009879;
        }

        .styled-table tbody tr:hover {
            background-color: #f1f1f1;
            cursor: pointer;
        }

        .action-buttons {
            display: flex;
            align-items: center;
        }

        .action-buttons button {
            margin-right: 10px;
            padding: 8px 12px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
        }

        .edit-button {
            background-color: #3498db;
            color: #fff;
        }

        .delete-button {
            background-color: #e74c3c;
            color: #fff;
        }

        .assign-button, .feature-button {
            background-color: #f39c12;
            color: #fff;
        }

        .add-button {
            background-color: #2ecc71;
            color: #fff;
            padding: 10px 20px;
            font-size: 16px;
            margin-bottom: 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .course-image {
            max-width: 100px;
            border-radius: 5px;
        }

        .assign-dropdown {
            padding: 8px 12px;
            border-radius: 5px;
            border: 1px solid #ccc;
            font-size: 14px;
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
                <li><a href="manage_services.php">Manage Services</a></li>
                <li><a href="manage_courses.php" class="active">Manage Courses</a></li>
                <li><a href="manage_requested_services.php">Manage Requested Services</a></li>
                <li><a href="manage_slideshow.php">Manage Slideshow</a></li>
            </ul>
        </nav>
    </div>
    <div class="content">
        <h2>Manage Courses</h2>
        <?php if (isset($success_message)): ?>
            <div class="success-message"><?= htmlspecialchars($success_message) ?></div>
        <?php endif; ?>
        <?php if (isset($error_message)): ?>
            <div class="error-message"><?= htmlspecialchars($error_message) ?></div>
        <?php endif; ?>
        <button class="add-button" onclick="location.href='add_course.php'">Add New Course</button>
        <?php if (!empty($courses)): ?>
            <table class="styled-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Image</th>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Duration</th>
                        <th>Featured</th>
                        <th>Assign to Staff</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($courses as $course): ?>
                        <tr>
                            <td><?= htmlspecialchars($course['id']) ?></td>
                            <td>
                                <?php if (!empty($course['course_image'])): ?>
                                    <img src="<?= htmlspecialchars($course['course_image']) ?>" alt="<?= htmlspecialchars($course['name']) ?>" class="course-image">
                                <?php else: ?>
                                    <span>No Image</span>
                                <?php endif; ?>
                            </td>
                            <td><?= htmlspecialchars($course['name']) ?></td>
                            <td><?= htmlspecialchars($course['description']) ?></td>
                            <td><?= htmlspecialchars($course['duration']) ?></td>
                            <td><?= $course['featured'] ? 'Yes' : 'No' ?></td>
                            <td>
                                <form method="POST">
                                    <input type="hidden" name="course_id" value="<?= htmlspecialchars($course['id']) ?>">
                                    <select name="staff_id" class="assign-dropdown">
                                        <option value="">Select Staff</option>
                                        <?php foreach ($staffUsers as $staff): ?>
                                            <option value="<?= htmlspecialchars($staff['id']) ?>">
                                                <?= htmlspecialchars($staff['first_name'] . ' ' . $staff['last_name']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <button class="assign-button" type="submit" name="assign_course">Assign</button>
                                </form>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <button class="edit-button" onclick="location.href='edit_course.php?id=<?= $course['id'] ?>'">Edit</button>
                                    <button class="delete-button" onclick="location.href='delete_course.php?id=<?= $course['id'] ?>'">Delete</button>
                                    <form action="manage_courses.php" method="post" style="display:inline;">
                                        <input type="hidden" name="course_id" value="<?= htmlspecialchars($course['id']) ?>">
                                        <input type="hidden" name="current_feature_status" value="<?= htmlspecialchars($course['featured']) ?>">
                                        <button type="submit" name="toggle_feature" class="feature-button">
                                            <?= $course['featured'] ? 'Unfeature' : 'Feature' ?>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="no-data">No courses available.</div>
        <?php endif; ?>
    </div>
</body>
</html>
