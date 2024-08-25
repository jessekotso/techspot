<?php
// profile.php
include 'php/db.php'; // Include the database connection
session_start();
$user_id = $_SESSION['user_id'] ?? null;

if (!$user_id) {
    header('Location: login.php');
    exit();
}

// Fetch user profile information
$sql = "SELECT first_name, last_name, email, phone, address FROM users WHERE id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<div class="profile">
    <h2>Profile Information</h2>
    <p><strong>Name:</strong> <?= htmlspecialchars($user['first_name'] . ' ' . $user['last_name']) ?></p>
    <p><strong>Email:</strong> <?= htmlspecialchars($user['email']) ?></p>
    <p><strong>Phone:</strong> <?= htmlspecialchars($user['phone']) ?></p>
    <p><strong>Address:</strong> <?= htmlspecialchars($user['address']) ?></p>
    <a href="edit_profile.php">Edit Profile</a>
</div>

<style>
    .profile {
        background: #fff;
        padding: 20px;
        margin: 20px 0;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }
    h2 {
        margin-top: 0;
    }
</style>
