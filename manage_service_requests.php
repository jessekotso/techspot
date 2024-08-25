<?php
session_start();
include 'php/db.php'; // Include the database connection

// Fetch requested services from the service_requests table
$sql = "SELECT sr.id, sr.description, sr.status, u.first_name, u.last_name, s.name AS service_type 
        FROM service_requests sr 
        JOIN users u ON sr.user_id = u.id
        JOIN services s ON sr.service_id = s.id";
$service_requests = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);

// Fetch all staff users
$staff_sql = "SELECT id, first_name, last_name FROM users WHERE user_type = 'staff'";
$staff_users = $pdo->query($staff_sql)->fetchAll(PDO::FETCH_ASSOC);

// Handle different actions (e.g., approve, delete, assign, etc.)
if (isset($_POST['manage_service_request'])) {
    $action = $_POST['action'];
    $id = isset($_POST['id']) ? intval($_POST['id']) : '';

    if ($action === 'approve') {
        // Approve the requested service
        $sql = "UPDATE service_requests SET status = 'approved' WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        if ($stmt->execute([$id])) {
            $success_message = "Service request ID $id approved successfully.";
        } else {
            $error_message = "Failed to approve service request ID $id.";
        }
    } elseif ($action === 'delete') {
        // Delete the requested service
        $sql = "DELETE FROM service_requests WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        if ($stmt->execute([$id])) {
            $success_message = "Service request ID $id deleted successfully.";
        } else {
            $error_message = "Failed to delete service request ID $id.";
        }
    } elseif ($action === 'assign') {
        // Assign the requested service to a staff member
        $staff_id = isset($_POST['staff_id']) ? intval($_POST['staff_id']) : '';
        $sql = "UPDATE service_requests SET staff_id = ? WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        if ($stmt->execute([$staff_id, $id])) {
            $success_message = "Service request ID $id assigned to staff member successfully.";
        } else {
            $error_message = "Failed to assign service request ID $id.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Service Requests</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f0f2f5;
            margin: 0;
            padding: 20px;
            color: #333;
        }
        .container {
            width: 90%;
            max-width: 1000px;
            margin: 0 auto;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table th, table td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: left;
        }
        table th {
            background-color: #f4f4f4;
        }
        form {
            display: inline;
        }
        input[type="submit"], select {
            background: #007bff;
            color: #fff;
            padding: 8px 12px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin: 0 2px;
        }
        input[type="submit"]:hover {
            background: #0056b3;
        }
        .message {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
        }
        .success {
            background-color: #28a745;
            color: #fff;
        }
        .error {
            background-color: #dc3545;
            color: #fff;
        }
    </style>
</head>
<body>
    <div class="container">
        <?php if (isset($success_message)): ?>
            <div class="message success"><?= htmlspecialchars($success_message) ?></div>
        <?php endif; ?>
        <?php if (isset($error_message)): ?>
            <div class="message error"><?= htmlspecialchars($error_message) ?></div>
        <?php endif; ?>

        <h2>Manage Service Requests</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Service Type</th>
                    <th>Description</th>
                    <th>User Name</th>
                    <th>Status</th>
                    <th>Assign to Staff</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($service_requests as $request): ?>
                    <tr>
                        <td><?= htmlspecialchars($request['id']) ?></td>
                        <td><?= htmlspecialchars($request['service_type']) ?></td>
                        <td><?= htmlspecialchars($request['description']) ?></td>
                        <td><?= htmlspecialchars($request['first_name'] . ' ' . $request['last_name']) ?></td>
                        <td><?= htmlspecialchars($request['status']) ?></td>
                        <td>
                            <form method="POST">
                                <input type="hidden" name="manage_service_request" value="true">
                                <input type="hidden" name="action" value="assign">
                                <input type="hidden" name="id" value="<?= htmlspecialchars($request['id']) ?>">
                                <select name="staff_id" required>
                                    <option value="">Select Staff</option>
                                    <?php foreach ($staff_users as $staff): ?>
                                        <option value="<?= htmlspecialchars($staff['id']) ?>"><?= htmlspecialchars($staff['first_name'] . ' ' . $staff['last_name']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <input type="submit" value="Assign">
                            </form>
                        </td>
                        <td>
                            <form method="POST" onsubmit="return confirm('Are you sure you want to approve this request?');">
                                <input type="hidden" name="manage_service_request" value="true">
                                <input type="hidden" name="action" value="approve">
                                <input type="hidden" name="id" value="<?= htmlspecialchars($request['id']) ?>">
                                <input type="submit" value="Approve">
                            </form>
                            <form method="POST" onsubmit="return confirm('Are you sure you want to delete this request?');">
                                <input type="hidden" name="manage_service_request" value="true">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="id" value="<?= htmlspecialchars($request['id']) ?>">
                                <input type="submit" value="Delete">
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
