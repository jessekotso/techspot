<?php
session_start();
include 'php/db.php'; // Include the database connection

try {
    // Fetch all service requests with related user and staff information
    $stmt = $pdo->prepare("
        SELECT sr.id, sr.status, u.first_name, u.last_name, s.name AS service_name, 
               st.first_name AS staff_first_name, st.last_name AS staff_last_name, sr.staff_id
        FROM service_requests sr 
        JOIN users u ON sr.user_id = u.id 
        JOIN services s ON sr.service_id = s.id
        LEFT JOIN users st ON sr.staff_id = st.id
    ");
    $stmt->execute();
    $service_requests = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Fetch staff users for assignment
    $stmt = $pdo->prepare("SELECT id, first_name, last_name FROM users WHERE user_type = 'staff'");
    $stmt->execute();
    $staff_users = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error fetching data: " . $e->getMessage();
    exit();
}

// Handle staff assignment
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['assign_service'])) {
    $request_id = $_POST['request_id'];
    $staff_id = $_POST['staff_id'];

    try {
        $stmt = $pdo->prepare("UPDATE service_requests SET staff_id = ? WHERE id = ?");
        $stmt->execute([$staff_id, $request_id]);
        $success_message = "Staff assigned successfully.";
        header("Location: manage_requested_services.php"); // Redirect to avoid form resubmission
        exit();
    } catch (PDOException $e) {
        $error_message = "Error assigning staff: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Requested Services</title>
    <link rel="stylesheet" href="css/admin.css">
    <style>
        /* Additional styles for better visuals */
        .content {
            padding: 20px;
            margin-left: 220px; /* Adjust for sidebar width */
        }

        .success-message, .error-message {
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 5px;
            text-align: center;
        }

        .success-message {
            background-color: #d4edda;
            color: #155724;
        }

        .error-message {
            background-color: #f8d7da;
            color: #721c24;
        }

        .styled-table {
            width: 100%;
            border-collapse: collapse;
            margin: 25px 0;
            font-size: 1em;
            text-align: left;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
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

        .action-button {
            background-color: #0057ff;
            color: white;
            border: none;
            padding: 8px 12px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            transition: background-color 0.3s ease;
        }

        .action-button:hover {
            background-color: #003bb5;
        }

        .staff-select {
            width: 100%;
            padding: 8px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        .assign-button {
            margin-top: 10px;
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
                <li><a href="manage_courses.php">Manage Courses</a></li>
                <li><a href="manage_requested_services.php" class="active">Manage Requested Services</a></li>
                <li><a href="manage_slideshow.php">Manage Slideshow</a></li>
            </ul>
        </nav>
    </div>
    <div class="content">
        <h2>Manage Requested Services</h2>

        <!-- Display messages -->
        <?php if (isset($success_message)): ?>
            <div class="success-message"><?= htmlspecialchars($success_message) ?></div>
        <?php endif; ?>
        <?php if (isset($error_message)): ?>
            <div class="error-message"><?= htmlspecialchars($error_message) ?></div>
        <?php endif; ?>

        <?php if (!empty($service_requests)): ?>
            <table class="styled-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Service Name</th>
                        <th>User Name</th>
                        <th>Status</th>
                        <th>Assign to Staff</th>
                        <th>Assigned Staff</th>
                        <th>Delete</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($service_requests as $request): ?>
                        <tr>
                            <td><?= htmlspecialchars($request['id']) ?></td>
                            <td><?= htmlspecialchars($request['service_name']) ?></td>
                            <td><?= htmlspecialchars($request['first_name'] . ' ' . $request['last_name']) ?></td>
                            <td>
                                <span class="status <?= htmlspecialchars($request['status']) ?>">
                                    <?= htmlspecialchars(ucfirst($request['status'])) ?>
                                </span>
                            </td>
                            <td>
                                <form method="POST" action="">
                                    <input type="hidden" name="request_id" value="<?= htmlspecialchars($request['id']) ?>">
                                    <select name="staff_id" class="staff-select" required>
                                        <option value="" disabled selected>Select Staff</option>
                                        <?php foreach ($staff_users as $staff): ?>
                                            <option value="<?= htmlspecialchars($staff['id']) ?>" <?= $request['staff_id'] == $staff['id'] ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($staff['first_name'] . ' ' . $staff['last_name']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <button class="action-button assign-button" type="submit" name="assign_service">Assign</button>
                                </form>
                            </td>
                            <td><?= htmlspecialchars($request['staff_first_name'] . ' ' . $request['staff_last_name']) ?></td>
                            <td>
                                <form method="POST" action="delete_requested_service.php" onsubmit="return confirm('Are you sure you want to delete this service request?');">
                                    <input type="hidden" name="request_id" value="<?= htmlspecialchars($request['id']) ?>">
                                    <button class="action-button delete-button" type="submit">Delete</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="no-data">No requested services available.</div>
        <?php endif; ?>
    </div>
</body>
</html>
