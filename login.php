<?php
session_start();
ob_start(); // Start output buffering
include 'php/db.php'; // Include the database connection


$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve and sanitize form data
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];

    // Validate the input
    if (empty($email) || empty($password)) {
        $error = 'Both fields are required.';
    } else {
        try {
            // Prepare and execute the SQL statement
            $sql = "SELECT id, password, user_type FROM users WHERE email = :email";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->execute();

            // Check if the email exists
            if ($stmt->rowCount() === 1) {
                $user = $stmt->fetch(PDO::FETCH_ASSOC);
                $id = $user['id'];
                $hashed_password = $user['password'];
                $user_type = $user['user_type'];

                // Verify the password
                if (password_verify($password, $hashed_password)) {
                    // Set session variables and redirect to the appropriate dashboard
                    $_SESSION['user_id'] = $id;
                    $_SESSION['user_type'] = $user_type;

                    // Redirect based on user type
                    switch ($user_type) {
                        case 'admin':
                            header('Location: admin_dashboard.php');
                            break;
                        case 'staff':
                            header('Location: staff_dashboard.php');
                            break;
                        case 'user':
                            header('Location: user_dashboard.php');
                            break;
                        default:
                            $error = 'Invalid user type.';
                    }
                    exit(); // Ensure no further code is executed after redirection
                } else {
                    $error = 'Incorrect password.';
                }
            } else {
                $error = 'No user found with this email.';
            }
        } catch (PDOException $e) {
            $error = 'Database error: ' . $e->getMessage();
        }
    }
}

ob_end_flush(); // End output buffering and flush the output
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Tech Spot</title>
    <link rel="stylesheet" href="css/style.css"> <!-- Ensure this points to your main stylesheet -->
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            padding: 0;
        }

        .login-container {
            background-color: #ffffff;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0px 4px 20px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            width: 100%;
            text-align: center;
        }

        .login-container h2 {
            margin-bottom: 30px;
            font-size: 1.8rem;
            color: #333;
            text-align: center;
        }

        .login-container input[type="email"],
        .login-container input[type="password"] {
            width: 100%;
            padding: 15px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
            font-size: 1rem;
        }

        .login-container input[type="submit"] {
            width: 100%;
            padding: 15px;
            background-color: #0057ff;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1rem;
            transition: background-color 0.3s;
        }

        .login-container input[type="submit"]:hover {
            background-color: #003bb5;
        }

        .login-container .message {
            color: #e74c3c;
            font-size: 0.9rem;
            margin-bottom: 15px;
            text-align: center;
        }

        .login-container label {
            font-weight: bold;
            font-size: 0.9rem;
            color: #333;
        }

        .login-container .form-links {
            text-align: center;
            margin-top: 20px;
        }

        .login-container .form-links a {
            color: #0057ff;
            text-decoration: none;
        }

        .login-container .form-links a:hover {
            text-decoration: underline;
        }

        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            background-color: #333;
            color: #fff;
            text-align: center;
            padding: 10px 0;
        }

    </style>
</head>

<body>
    <div class="login-container">
        <h2>Login to Tech Spot</h2>
        <?php if (!empty($error)): ?>
            <div class="message"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        <form method="POST" action="">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" required>

            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>

            <input type="submit" value="Login">
        </form>
        <div class="form-links">
            <a href="forgot_password.php">Forgot Password?</a> |
            <a href="register.php">Register</a>
        </div>
    </div>

    <!-- Footer -->
    <div class="footer">
        <p>&copy; <?= date('Y'); ?> Tech Spot. All rights reserved.</p>
    </div>

</body>

</html>
