<?php
session_start();
include 'php/db.php'; // Include the database connection

$slide_id = intval($_GET['id']);

// Delete the slide
$stmt = $pdo->prepare("DELETE FROM slideshow WHERE id = ?");
if ($stmt->execute([$slide_id])) {
    $_SESSION['success_message'] = "Slide deleted successfully.";
} else {
    $_SESSION['error_message'] = "Error deleting slide.";
}

header('Location: manage_slideshow.php'); // Redirect back to the slideshow management page
exit();
?>
