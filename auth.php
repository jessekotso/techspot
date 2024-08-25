<?php
session_start();

// Mock authentication functions
function login($username, $password) {
    // Mock user data
    $users = [
        'admin' => ['username' => 'admin', 'password' => 'admin123', 'role' => 'admin'],
        'staff' => ['username' => 'staff', 'password' => 'staff123', 'role' => 'staff'],
        'user' => ['username' => 'user', 'password' => 'user123', 'role' => 'user']
    ];

    if (isset($users[$username]) && $users[$username]['password'] == $password) {
        $_SESSION['user'] = $users[$username];
        header("Location: {$users[$username]['role']}_dashboard.php");
        exit();
    } else {
        echo "Invalid login credentials.";
    }
}

function register($username, $email, $password) {
    // Mock registration logic
    echo "User registered successfully.";
}

function logout() {
    session_destroy();
    header("Location: login.php");
    exit();
}
?>
