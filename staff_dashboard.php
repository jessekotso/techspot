<?php
include 'php/db.php'; // Include the database connection

session_start();
$staff_id = $_SESSION['user_id'] ?? null;

if (!$staff_id) {
    header('Location: login.php');
    exit();
}

// Fetch staff profile information
$sql_profile = "SELECT first_name, last_name, email, phone FROM users WHERE id = ?";
$stmt_profile = $pdo->prepare($sql_profile);
$stmt_profile->execute([$staff_id]);
$profile = $stmt_profile->fetch(PDO::FETCH_ASSOC);

// Fetch assigned tasks
$sql_tasks = "SELECT t.id, t.title, t.description, t.status, t.created_at, u.first_name AS assigned_by 
              FROM tasks t 
              JOIN users u ON t.assigned_by = u.id 
              WHERE t.assigned_to = ?";
$stmt_tasks = $pdo->prepare($sql_tasks);
$stmt_tasks->execute([$staff_id]);
$tasks = $stmt_tasks->fetchAll(PDO::FETCH_ASSOC);

// Fetch services assigned by the admin
$sql_services = "SELECT sr.id, s.name AS service_name, sr.description, sr.status, sr.created_at, u.first_name AS requested_by 
                 FROM service_requests sr 
                 JOIN services s ON sr.service_id = s.id 
                 JOIN users u ON sr.user_id = u.id 
                 WHERE sr.staff_id = ?";
$stmt_services = $pdo->prepare($sql_services);
$stmt_services->execute([$staff_id]);
$assigned_services = $stmt_services->fetchAll(PDO::FETCH_ASSOC);

// Handle task update
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_task'])) {
    $task_id = $_POST['task_id'];
    $status = $_POST['status'];
    $sql_update_task = "UPDATE tasks SET status = ? WHERE id = ? AND assigned_to = ?";
    $stmt_update_task = $pdo->prepare($sql_update_task);
    if ($stmt_update_task->execute([$status, $task_id, $staff_id])) {
        $success_message = 'Task status updated successfully!';
    } else {
        $error_message = 'Failed to update task status.';
    }
}

// Handle service status update
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_service_status'])) {
    $service_id = $_POST['service_id'];
    $status = $_POST['status'];
    $sql_update_service = "UPDATE service_requests SET status = ? WHERE id = ? AND staff_id = ?";
    $stmt_update_service = $pdo->prepare($sql_update_service);
    if ($stmt_update_service->execute([$status, $service_id, $staff_id])) {
        $success_message = 'Service status updated successfully!';
    } else {
        $error_message = 'Failed to update service status.';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Dashboard</title>
    <link rel="stylesheet" href="css/admin.css">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f4f4;
            color: #333;
            margin: 0;
            padding: 0;
        }

        .sidebar {
            width: 220px;
            position: fixed;
            top: 0;
            left: 0;
            height: 100%;
            background-color: #0057ff;
            padding-top: 20px;
        }

        .sidebar h1 {
            color: #fff;
            text-align: center;
        }

        .sidebar nav ul {
            list-style-type: none;
            padding: 0;
        }

        .sidebar nav ul li {
            text-align: center;
            margin: 10px 0;
        }

        .sidebar nav ul li a {
            color: #ffffff;
            text-decoration: none;
            display: block;
            padding: 10px;
            transition: background-color 0.3s;
        }

        .sidebar nav ul li a:hover,
        .sidebar nav ul li a.active {
            background-color: #003bb5;
        }

        .content {
            margin-left: 240px; /* Adjust based on sidebar width */
            padding: 20px;
        }

        .dashboard-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .profile-section, .tasks-section, .services-section {
            background-color: white;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .section-title {
            font-size: 1.8em;
            margin-bottom: 10px;
            color: #0057ff;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 10px;
            text-align: left;
        }

        th {
            background: #f4f4f4;
        }

        .button {
            background-color: #0057ff;
            color: white;
            padding: 10px 20px;
            text-align: center;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            font-size: 14px;
            display: inline-block;
            transition: background-color 0.3s;
        }

        .button:hover {
            background-color: #003bb5;
        }

        .message {
            padding: 10px;
            margin-bottom: 20px;
            color: #fff;
            border-radius: 5px;
            text-align: center;
        }

        .success {
            background-color: #5cb85c;
        }

        .error {
            background-color: #d9534f;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
                height: auto;
                position: relative;
            }
            .sidebar nav ul {
                display: flex;
                flex-wrap: wrap;
                justify-content: center;
            }
            .content {
                margin-left: 0;
                padding: 20px;
            }
        }
    </style>
</head>
<body>

<!-- Sidebar -->
<div class="sidebar">
    <h1>Staff Dashboard</h1>
    <nav>
        <ul>
            <li><a href="staff_dashboard.php?page=profile" class="<?= (isset($_GET['page']) && $_GET['page'] == 'profile') ? 'active' : ''; ?>">Profile</a></li>
            <li><a href="staff_dashboard.php?page=tasks" class="<?= (isset($_GET['page']) && $_GET['page'] == 'tasks') ? 'active' : ''; ?>">Assigned Tasks</a></li>
            <li><a href="staff_dashboard.php?page=services" class="<?= (isset($_GET['page']) && $_GET['page'] == 'services') ? 'active' : ''; ?>">Assigned Services</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </nav>
</div>

<!-- Main Content -->
<div class="content">
    <div class="dashboard-header">
        <h1>Welcome to Your Dashboard, <?= htmlspecialchars($profile['first_name']) ?>!</h1>
    </div>

    <?php if (isset($success_message)): ?>
        <div class="message success"><?= htmlspecialchars($success_message) ?></div>
    <?php endif; ?>
    <?php if (isset($error_message)): ?>
        <div class="message error"><?= htmlspecialchars($error_message) ?></div>
    <?php endif; ?>

    <?php
    // Load the appropriate section based on the query string parameter
    if (isset($_GET['page'])) {
        switch ($_GET['page']) {
            case 'profile':
                include 'staff_profile.php';
                break;
            case 'tasks':
                include 'staff_tasks.php';
                break;
            case 'services':
                include 'staff_services.php';
                break;
            default:
                echo "<p>Invalid section selected.</p>";
        }
    } else {
        // Default section (Profile)
        include 'staff_profile.php';
    }
    ?>
</div>

</body>
</html>
