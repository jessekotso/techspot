<?php
// service_requests_history.php
include 'php/db.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$user_id = $_SESSION['user_id'] ?? null;

if (!$user_id) {
    header('Location: login.php');
    exit();
}

// Fetch user service requests
$sql_requests = "SELECT * FROM service_requests WHERE user_id = ?";
$stmt_requests = $pdo->prepare($sql_requests);
$stmt_requests->execute([$user_id]);
$requests = $stmt_requests->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="service-requests">
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
                    <th>Date Requested</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($requests as $request): ?>
                    <tr>
                        <td><?= htmlspecialchars($request['service_type']) ?></td>
                        <td><?= htmlspecialchars($request['document_type'] ?? 'N/A') ?></td>
                        <td><?= htmlspecialchars($request['description']) ?></td>
                        <td><?= htmlspecialchars($request['request_date']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

<style>
    .service-requests {
        background: #fff;
        padding: 20px;
        margin: 20px 0;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }
    table {
        width: 100%;
        border-collapse: collapse;
    }
    th, td {
        padding: 10px;
        text-align: left;
        border: 1px solid #ddd;
    }
    th {
        background: #f4f4f4;
    }
</style>
