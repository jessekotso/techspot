<?php
// service_request.php
include 'php/db.php';

// Check if a session is already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$user_id = $_SESSION['user_id'] ?? null;

if (!$user_id) {
    header('Location: login.php');
    exit();
}

// Fetch all available services
$sql_services = "SELECT id, name FROM services";
$stmt_services = $pdo->query($sql_services);
$services = $stmt_services->fetchAll(PDO::FETCH_ASSOC);

// Handle service requests
$success_message = '';
$error_message = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['request_service'])) {
    $service_type = $_POST['service_type'];
    $document_type = $_POST['document_processing_type'] ?? null;
    $description = $_POST['description'];
    $preferred_date = $_POST['preferred_date'];
    $additional_instructions = $_POST['additional_instructions'];
    $attachment = $_FILES['attachment']['name'] ?? null;

    // Handle file upload if there is an attachment
    if ($attachment) {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($attachment);
        if (move_uploaded_file($_FILES['attachment']['tmp_name'], $target_file)) {
            $attachment_path = $target_file;
        } else {
            $error_message = 'Failed to upload the attachment.';
        }
    }

    // Insert service request into the database
    if (!$error_message) {
        $sql_request = "INSERT INTO service_requests (user_id, service_id, document_type, description, preferred_date, additional_instructions, attachment, status) 
                        VALUES (?, (SELECT id FROM services WHERE name = ?), ?, ?, ?, ?, ?, 'pending')";
        $stmt_request = $pdo->prepare($sql_request);
        if ($stmt_request->execute([$user_id, $service_type, $document_type, $description, $preferred_date, $additional_instructions, $attachment_path ?? null])) {
            $success_message = 'Service request submitted successfully!';
        } else {
            $error_message = 'Failed to submit service request.';
        }
    }
}

// Fetch user service requests
$sql_requests = "SELECT sr.*, s.name AS service_name FROM service_requests sr 
                 JOIN services s ON sr.service_id = s.id 
                 WHERE sr.user_id = ?";
$stmt_requests = $pdo->prepare($sql_requests);
$stmt_requests->execute([$user_id]);
$requests = $stmt_requests->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Request a Service</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f0f2f5;
            margin: 0;
            padding: 0;
            color: #333;
        }
        .container {
            width: 90%;
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        h2 {
            margin-top: 0;
            border-bottom: 2px solid #007bff;
            padding-bottom: 10px;
        }
        .message {
            padding: 15px;
            margin-bottom: 20px;
            color: #fff;
            border-radius: 5px;
        }
        .success {
            background-color: #28a745;
        }
        .error {
            background-color: #dc3545;
        }
        label {
            display: block;
            margin-top: 10px;
            font-weight: bold;
        }
        input[type="text"], input[type="date"], textarea, select, input[type="file"] {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }
        textarea {
            resize: vertical;
        }
        input[type="submit"] {
            background: #007bff;
            color: #fff;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 20px;
            transition: background 0.3s ease;
        }
        input[type="submit"]:hover {
            background: #0056b3;
        }
        .form-group {
            margin-bottom: 15px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table th, table td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }
        table th {
            background-color: #f4f4f4;
        }
    </style>
</head>
<body>
    <div class="container">
        <?php if ($success_message): ?>
            <div class="message success"><?= htmlspecialchars($success_message) ?></div>
        <?php endif; ?>
        <?php if ($error_message): ?>
            <div class="message error"><?= htmlspecialchars($error_message) ?></div>
        <?php endif; ?>

        <h2>Request a Service</h2>
        <form method="POST" action="" enctype="multipart/form-data">
            <div class="form-group">
                <label for="service_type">Select Service Type:</label>
                <select id="service_type" name="service_type" required>
                    <option value="">-- Select Service --</option>
                    <?php foreach ($services as $service): ?>
                        <option value="<?= htmlspecialchars($service['name']) ?>"><?= htmlspecialchars($service['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Dynamic options for Document Processing -->
            <div class="form-group" id="document_processing_options" style="display: none;">
                <label for="document_processing_type">Select Document Processing Type:</label>
                <select id="document_processing_type" name="document_processing_type">
                    <option value="Typing">Typing</option>
                    <option value="Photocopy">Photocopy</option>
                    <option value="Printing">Printing</option>
                    <option value="Lamination">Lamination</option>
                    <option value="Scanning">Scanning</option>
                    <option value="Spiral Binding">Spiral Binding</option>
                </select>
            </div>

            <div class="form-group">
                <label for="description">Service Description:</label>
                <textarea id="description" name="description" rows="4" required placeholder="Provide a detailed description of the service you require..."></textarea>
            </div>

            <div class="form-group">
                <label for="preferred_date">Preferred Date:</label>
                <input type="date" id="preferred_date" name="preferred_date" required>
            </div>

            <div class="form-group">
                <label for="additional_instructions">Additional Instructions:</label>
                <textarea id="additional_instructions" name="additional_instructions" rows="3" placeholder="Any specific instructions or requests?"></textarea>
            </div>

            <div class="form-group">
                <label for="attachment">Attach Document (Optional):</label>
                <input type="file" id="attachment" name="attachment">
            </div>

            <input type="submit" name="request_service" value="Submit Request">
        </form>

        <h2>Your Service Requests</h2>
        <?php if (empty($requests)): ?>
            <p>No service requests found.</p>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>Service Type</th>
                        <th>Document Type</th>
                        <th>Description</th>
                        <th>Preferred Date</th>
                        <th>Date Requested</th>
                        <th>Attachment</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($requests as $request): ?>
                        <tr>
                            <td><?= htmlspecialchars($request['service_name']) ?></td>
                            <td><?= htmlspecialchars($request['document_type'] ?? 'N/A') ?></td>
                            <td><?= htmlspecialchars($request['description']) ?></td>
                            <td><?= htmlspecialchars($request['preferred_date']) ?></td>
                            <td><?= htmlspecialchars($request['request_date']) ?></td>
                            <td><?= $request['attachment'] ? '<a href="' . htmlspecialchars($request['attachment']) . '" target="_blank">View</a>' : 'None' ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>

    <script>
        document.getElementById('service_type').addEventListener('change', function () {
            var value = this.value;
            var docOptions = document.getElementById('document_processing_options');
            if (value === 'Document Processing') {
                docOptions.style.display = 'block';
            } else {
                docOptions.style.display = 'none';
            }
        });
    </script>
</body>
</html>
