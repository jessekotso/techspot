<?php
include 'php/db.php'; // Include the database connection
include 'templates/header.php';

// Get user ID from URL
$user_id = $_GET['id'] ?? null;
if (!$user_id) {
    echo "Invalid user ID";
    exit;
}

// Fetch user data
try {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$user) {
        echo "User not found.";
        exit;
    }
} catch (PDOException $e) {
    echo "Error fetching user: " . $e->getMessage();
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Update user data
    $first_name = $_POST['first_name'] ?? '';
    $last_name = $_POST['last_name'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $address = $_POST['address'] ?? '';
    $email = $_POST['email'] ?? '';
    $user_type = $_POST['user_type'] ?? '';
    $profile_picture = $user['profile_picture'];

    // Handle file upload for profile picture
    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] == UPLOAD_ERR_OK) {
        $profile_picture = handleFileUpload($_FILES['profile_picture'], 'profile_pictures/');
    }

    try {
        $sql = "UPDATE users SET first_name = ?, last_name = ?, phone = ?, address = ?, email = ?, user_type = ?, profile_picture = ? WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$first_name, $last_name, $phone, $address, $email, $user_type, $profile_picture, $user_id]);
        $success = "User updated successfully!";
    } catch (PDOException $e) {
        $error = "Error updating user: " . $e->getMessage();
    }
}

// Function to handle file uploads
function handleFileUpload($file, $uploadDir) {
    if (isset($file) && $file['error'] == UPLOAD_ERR_OK) {
        $tmp_name = $file['tmp_name'];
        $name = $file['name'];
        $uploadFile = $uploadDir . basename($name);

        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        if (move_uploaded_file($tmp_name, $uploadFile)) {
            return $uploadFile;
        }
    }
    return null;
}
?>

<div class="container">
    <h2>Edit User</h2>
    <?php if (isset($success)): ?>
        <div class="message success"><?= htmlspecialchars($success) ?></div>
    <?php elseif (isset($error)): ?>
        <div class="message error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">
        <label for="first_name">First Name:</label>
        <input type="text" id="first_name" name="first_name" value="<?= htmlspecialchars($user['first_name']) ?>" required>

        <label for="last_name">Last Name:</label>
        <input type="text" id="last_name" name="last_name" value="<?= htmlspecialchars($user['last_name']) ?>" required>

        <label for="phone">Phone:</label>
        <input type="text" id="phone" name="phone" value="<?= htmlspecialchars($user['phone']) ?>" required>

        <label for="address">Address:</label>
        <input type="text" id="address" name="address" value="<?= htmlspecialchars($user['address']) ?>" required>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>

        <label for="user_type">User Type:</label>
        <select id="user_type" name="user_type" required>
            <option value="admin" <?= $user['user_type'] == 'admin' ? 'selected' : '' ?>>Admin</option>
            <option value="staff" <?= $user['user_type'] == 'staff' ? 'selected' : '' ?>>Staff</option>
            <option value="user" <?= $user['user_type'] == 'user' ? 'selected' : '' ?>>User</option>
        </select>

        <label for="profile_picture">Profile Picture:</label>
        <input type="file" id="profile_picture" name="profile_picture">
        <?php if ($user['profile_picture']): ?>
            <img src="<?= htmlspecialchars($user['profile_picture']) ?>" alt="Profile Picture" width="100">
        <?php endif; ?>

        <input type="submit" value="Update User">
    </form>
</div>

<?php include 'templates/footer.php'; ?>
