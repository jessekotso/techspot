<?php
// assign_service.php
include 'php/db.php';

// Check if a session is already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Verify admin is logged in
$admin_id = $_SESSION['admin_id'] ?? null;

//if (!$admin_id) {
  //  header('Location: login.php');
    //exit();
//}

// Fetch unassigned service requests
$sql_requests = "SELECT sr.id, sr.service_type, sr.description, sr.request_date, u.first_name, u.last_name 
                 FROM service_requests sr
                 JOIN users u ON sr.user_id = u.id
                 WHERE sr.staff_id IS NULL";
$stmt_requests = $pdo->query($sql_requests);
$requests = $stmt_requests->fetchAll(PDO::FETCH_ASSOC);

// Fetch all staff users
$sql_staff = "SELECT id, first_name, last_name FROM users WHERE user_type = 'staff'";
$stmt_staff = $pdo->query($sql_staff);
$staff_members = $stmt_staff->fetchAll(PDO::FETCH_ASSOC);

// Handle assignment
$success_message = '';
$error_message = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['assign_service'])) {
    $request_id = $_POST['request_id'];
    $staff_id = $_POST['staff_id'];

    // Update the service request with the assigned staff member
    $sql_assign = "UPDATE service_requests SET staff_id = ? WHERE id = ?";
    $stmt_assign = $pdo->prepare($sql_assign);
    if ($stmt_assign->execute([$staff_id, $request_id])) {
        $success_message = 'Service request assigned successfully!';
    } else {
        $error_message = 'Failed to assign the service request.';
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assign Service Requests to Staff</title>
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
        select, input[type="submit"] {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }
        input[type="submit"] {
            background: #007bff;
            color: #fff;
            border: none;
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

        <h2>Assign Service Requests to Staff</h2>
        <form method="POST" action="">
            <div class="form-group">
                <label for="request_id">Select Service Request:</label>
                <select id="request_id" name="request_id" required>
                    <option value="">-- Select Service Request --</option>
                    <?php foreach ($requests as $request): ?>
                        <option value="<?= htmlspecialchars($request['id']) ?>">
                            <?= htmlspecialchars($request['service_type']) ?> - <?= htmlspecialchars($request['description']) ?> (Requested by <?= htmlspecialchars($request['first_name'] . ' ' . $request['last_name']) ?> on <?= htmlspecialchars($request['request_date']) ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="staff_id">Assign to Staff Member:</label>
                <select id="staff_id" name="staff_id" required>
                    <option value="">-- Select Staff Member --</option>
                    <?php foreach ($staff_members as $staff): ?>
                        <option value="<?= htmlspecialchars($staff['id']) ?>">
                            <?= htmlspecialchars($staff['first_name'] . ' ' . $staff['last_name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <input type="submit" name="assign_service" value="Assign Service">
        </form>

        <h2>Unassigned Service Requests</h2>
        <?php if (empty($requests)): ?>
            <p>All service requests have been assigned.</p>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>Service Type</th>
                        <th>Description</th>
                        <th>Requested By</th>
                        <th>Date Requested</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($requests as $request): ?>
                        <tr>
                            <td><?= htmlspecialchars($request['service_type']) ?></td>
                            <td><?= htmlspecialchars($request['description']) ?></td>
                            <td><?= htmlspecialchars($request['first_name'] . ' ' . $request['last_name']) ?></td>
                            <td><?= htmlspecialchars($request['request_date']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</body>
</html>
