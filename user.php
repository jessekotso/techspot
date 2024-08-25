<?php
// Start session and include authentication script
session_start();
include 'php/auth.php';
include 'php/db.php'; // Include database connection

// Ensure the user is logged in
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

// Fetch user details from session
$user = $_SESSION['user'];
$user_id = $user['id'];

// Handle form submission to update user profile
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Validate inputs
    if (!empty($username) && !empty($email)) {
        $query = "UPDATE users SET username = ?, email = ?";

        // If a new password is provided, include it in the update
        if (!empty($password)) {
            $password_hashed = password_hash($password, PASSWORD_DEFAULT);
            $query .= ", password = ?";
        }

        $query .= " WHERE id = ?";

        // Prepare and execute the query
        $stmt = $conn->prepare($query);
        if (!empty($password)) {
            $stmt->bind_param("sssi", $username, $email, $password_hashed, $user_id);
        } else {
            $stmt->bind_param("ssi", $username, $email, $user_id);
        }

        if ($stmt->execute()) {
            // Update session data with the new user details
            $_SESSION['user']['username'] = $username;
            $_SESSION['user']['email'] = $email;

            $success_message = "Profile updated successfully!";
        } else {
            $error_message = "Failed to update profile. Please try again.";
        }

        $stmt->close();
    } else {
        $error_message = "Username and email are required.";
    }
}

// Fetch the latest user data from the database
$query = "SELECT * FROM users WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

$stmt->close();
?>

<?php include 'templates/header.php'; ?>

<div class="container">
    <h1>Your Profile</h1>

    <?php if (isset($success_message)) : ?>
        <div class="success-message">
            <?php echo $success_message; ?>
        </div>
    <?php endif; ?>

    <?php if (isset($error_message)) : ?>
        <div class="error-message">
            <?php echo $error_message; ?>
        </div>
    <?php endif; ?>

    <form method="post" action="user.php">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" placeholder="Enter new password (optional)">
        
        <button type="submit">Update Profile</button>
    </form>
</div>

<?php include 'templates/footer.php'; ?>

<?php
// Close database connection
$conn->close();
?>
