<?php
include 'php/db.php'; // Include the database connection
include 'templates/header.php';

// Get service ID from URL
$service_id = $_GET['id'] ?? null;
if (!$service_id) {
    echo "Invalid service ID";
    exit;
}

// Fetch service data
try {
    $stmt = $pdo->prepare("SELECT * FROM services WHERE id = ?");
    $stmt->execute([$service_id]);
    $service = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$service) {
        echo "Service not found.";
        exit;
    }
} catch (PDOException $e) {
    echo "Error fetching service: " . $e->getMessage();
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Update service data
    $name = $_POST['name'] ?? '';
    $description = $_POST['description'] ?? '';
    $service_image = $service['service_image'];

    // Handle file upload for service image
    if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
        $service_image = handleFileUpload($_FILES['image'], 'service_images/');
    }

    try {
        $sql = "UPDATE services SET name = ?, description = ?, service_image = ? WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$name, $description, $service_image, $service_id]);
        $success = "Service updated successfully!";
    } catch (PDOException $e) {
        $error = "Error updating service: " . $e->getMessage();
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
    <h2>Edit Service</h2>
    <?php if (isset($success)): ?>
        <div class="message success"><?= htmlspecialchars($success) ?></div>
    <?php elseif (isset($error)): ?>
        <div class="message error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">
        <label for="name">Service Name:</label>
        <input type="text" id="name" name="name" value="<?= htmlspecialchars($service['name']) ?>" required>

        <label for="description">Description:</label>
        <textarea id="description" name="description" required><?= htmlspecialchars($service['description']) ?></textarea>

        <label for="image">Service Image:</label>
        <input type="file" id="image" name="image">
        <?php if ($service['service_image']): ?>
            <img src="<?= htmlspecialchars($service['service_image']) ?>" alt="Service Image" width="100">
        <?php endif; ?>

        <input type="submit" value="Update Service">
    </form>

    <button onclick="history.back()">Back</button>
</div>

<?php include 'templates/footer.php'; ?>
