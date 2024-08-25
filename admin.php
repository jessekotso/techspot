<?php
// Start session and include authentication script
session_start();
include 'php/auth.php';

// Ensure the user is logged in and is an admin
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

// Fetch admin details from session
$admin = $_SESSION['user'];
?>

<?php include 'templates/header.php'; ?>

<div class="container">
    <h1>Admin Dashboard</h1>
    <p>Welcome, <?php echo $admin['username']; ?>!</p>

    <div class="dashboard-section">
        <h2>Manage Users</h2>
        <p><a href="manage_users.php">View, add, edit, or remove users.</a></p>
    </div>

    <div class="dashboard-section">
        <h2>Manage Products</h2>
        <p><a href="manage_products.php">View, add, edit, or remove products.</a></p>
    </div>

    <div class="dashboard-section">
        <h2>Manage Services</h2>
        <p><a href="manage_services.php">View, add, edit, or remove services.</a></p>
    </div>

    <div class="dashboard-section">
        <h2>Manage Training</h2>
        <p><a href="manage_training.php">View, add, edit, or remove training courses and schedules.</a></p>
    </div>

    <div class="dashboard-section">
        <h2>Content Management</
