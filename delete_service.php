<?php
session_start();
include 'php/db.php'; // Include the database connection

// Check if a service ID has been passed in the URL
if (isset($_GET['id'])) {
    $service_id = intval($_GET['id']);

    try {
        // Start a transaction
        $pdo->beginTransaction();

        // First, delete the related service requests
        $stmt = $pdo->prepare("DELETE FROM service_requests WHERE service_id = :service_id");
        $stmt->bindParam(':service_id', $service_id, PDO::PARAM_INT);
        $stmt->execute();

        // Then, delete the service itself
        $stmt = $pdo->prepare("DELETE FROM services WHERE id = :service_id");
        $stmt->bindParam(':service_id', $service_id, PDO::PARAM_INT);
        $stmt->execute();

        // Commit the transaction
        $pdo->commit();

        // Set a success message in the session and redirect
        $_SESSION['success_message'] = "Service and related requests deleted successfully.";
        header("Location: manage_services.php");
        exit();
    } catch (PDOException $e) {
        // Roll back the transaction if something went wrong
        $pdo->rollBack();
        $_SESSION['error_message'] = "Error deleting service: " . $e->getMessage();
        header("Location: manage_services.php");
        exit();
    }
} else {
    // Redirect if no ID is provided
    $_SESSION['error_message'] = "Invalid service ID.";
    header("Location: manage_services.php");
    exit();
}
