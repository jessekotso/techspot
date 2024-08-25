<?php
session_start();
include 'php/db.php'; // Include the database connection

// Initialize success and error messages
$success_message = isset($_SESSION['success_message']) ? $_SESSION['success_message'] : '';
$error_message = isset($_SESSION['error_message']) ? $_SESSION['error_message'] : '';

// Clear session messages
unset($_SESSION['success_message']);
unset($_SESSION['error_message']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="css/admin.css">
    <style>
        /* General Styles */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f0f2f5;
            margin: 0;
            padding: 0;
            color: #333;
            display: flex;
            height: 100vh;
        }

        .sidebar {
            width: 250px;
            background-color: #34495e;
            color: #ecf0f1;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding-top: 20px;
            position: fixed;
            height: 100%;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
        }

        .sidebar h1 {
            margin: 20px 0;
            font-size: 1.5rem;
            color: #ecf0f1;
        }

        .sidebar nav ul {
            list-style: none;
            padding: 0;
            margin: 20px 0;
            width: 100%;
            text-align: center;
        }

        .sidebar nav ul li {
            margin: 10px 0;
        }

        .sidebar nav ul li a {
            text-decoration: none;
            color: #ecf0f1;
            display: block;
            padding: 10px;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .sidebar nav ul li a:hover,
        .sidebar nav ul li a.active {
            background-color: #1abc9c;
        }

        .content {
            margin-left: 250px;
            padding: 20px;
            width: calc(100% - 250px);
            overflow-y: auto;
        }

        .header {
            background-color: #1abc9c;
            padding: 20px;
            color: white;
            text-align: center;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        /* Additional styles can be added here for specific sections */
    </style>
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
        <div class="header">
            <h1>Welcome to Tech Spot Admin Dashboard</h1>
        </div>

        <!-- Additional content for the dashboard home page -->
    </div>

    <script>
        // JavaScript can be added here for additional interactivity if needed
    </script>
</body>
</html>
