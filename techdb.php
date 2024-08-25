<?php
// Database configuration
$host = 'localhost'; // Database host, typically 'localhost'
$db_name = 'techman'; // Name of your database
$username = 'your_username'; // Your database username
$password = 'your_password'; // Your database password

try {
    // Create a new PDO instance
    $pdo = new PDO("mysql:host=$host;dbname=$db_name", $username, $password);
    
    // Set the PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Optional: Uncomment to check the connection
    // echo "Connected successfully"; 
} catch (PDOException $e) {
    // Handle connection error
    die("Connection failed: " . $e->getMessage());
}
?>
