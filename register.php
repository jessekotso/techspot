<?php
session_start();
include 'php/db.php'; // Include the database connection
include 'templates/header.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve and sanitize form data
    $first_name = filter_var($_POST['first_name'], FILTER_SANITIZE_STRING);
    $last_name = filter_var($_POST['last_name'], FILTER_SANITIZE_STRING);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];
    $phone = filter_var($_POST['phone'], FILTER_SANITIZE_STRING);
    $address = filter_var($_POST['address'], FILTER_SANITIZE_STRING);
    $profile_picture = '';

    // Validate input
    if (empty($first_name) || empty($last_name) || empty($email) || empty($password) || empty($phone) || empty($address)) {
        $error = 'All fields are required.';
    } else {
        try {
            // Handle profile picture upload
            if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] == UPLOAD_ERR_OK) {
                $tmp_name = $_FILES['profile_picture']['tmp_name'];
                $name = basename($_FILES['profile_picture']['name']);
                $upload_dir = 'profile_pictures/';
                $profile_picture = $upload_dir . $name;

                // Create directory if it doesn't exist
                if (!is_dir($upload_dir)) {
                    mkdir($upload_dir, 0755, true);
                }

                move_uploaded_file($tmp_name, $profile_picture);
            }

            // Hash the password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Prepare and execute the SQL statement
            $sql = "INSERT INTO users (first_name, last_name, email, password, phone, address, profile_picture) 
                    VALUES (:first_name, :last_name, :email, :password, :phone, :address, :profile_picture)";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':first_name', $first_name, PDO::PARAM_STR);
            $stmt->bindParam(':last_name', $last_name, PDO::PARAM_STR);
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->bindParam(':password', $hashed_password, PDO::PARAM_STR);
            $stmt->bindParam(':phone', $phone, PDO::PARAM_STR);
            $stmt->bindParam(':address', $address, PDO::PARAM_STR);
            $stmt->bindParam(':profile_picture', $profile_picture, PDO::PARAM_STR);

            if ($stmt->execute()) {
                $_SESSION['success'] = 'Registration successful!';
                header('Location: register.php?success=true');
                exit();
            } else {
                $error = 'Registration failed. Please try again.';
            }
        } catch (PDOException $e) {
            $error = 'Database error: ' . $e->getMessage();
        }
    }
}

// Fetch the latest registered user for display after successful registration
$registered_user = null;
if (isset($_GET['success'])) {
    $sql = "SELECT * FROM users ORDER BY id DESC LIMIT 1";
    $stmt = $pdo->query($sql);
    $registered_user = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Registration | Tech Spot</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            height: 100vh;
            margin: 0;
        }

        .registration-container {
            background-color: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0px 4px 20px rgba(0, 0, 0, 0.1);
            max-width: 500px;
            width: 100%;
        }

        .registration-container h2 {
            margin-bottom: 20px;
            font-size: 1.8rem;
            color: #333;
            text-align: center;
        }

        .registration-container label {
            font-weight: bold;
            font-size: 0.9rem;
            color: #333;
            display: block;
            margin-bottom: 5px;
        }

        .registration-container input[type="text"], 
        .registration-container input[type="email"], 
        .registration-container input[type="password"], 
        .registration-container input[type="tel"], 
        .registration-container input[type="file"], 
        .registration-container textarea {
            width: 100%;
            padding: 12px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
            font-size: 1rem;
        }

        .registration-container input[type="submit"] {
            width: 100%;
            padding: 15px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1rem;
            transition: background-color 0.3s;
        }

        .registration-container input[type="submit"]:hover {
            background-color: #0056b3;
        }

        .message {
            color: #e74c3c;
            font-size: 0.9rem;
            margin-bottom: 15px;
            text-align: center;
        }

        .success-message {
            color: #2ecc71;
            font-size: 1rem;
            margin-bottom: 15px;
            text-align: center;
        }

        .user-details {
            margin-top: 20px;
            background-color: #ecf0f1;
            padding: 20px;
            border-radius: 5px;
            text-align: center;
        }

        .user-details img {
            border-radius: 50%;
            width: 100px;
            height: 100px;
            margin-bottom: 15px;
        }
    </style>
</head>

<body>
    <div class="registration-container">
        <h2>User Registration</h2>
        <?php if (isset($error)): ?>
            <div class="message"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        <form method="POST" enctype="multipart/form-data">
            <label for="first_name">First Name</label>
            <input type="text" id="first_name" name="first_name" required>

            <label for="last_name">Last Name</label>
            <input type="text" id="last_name" name="last_name" required>

            <label for="email">Email</label>
            <input type="email" id="email" name="email" required>

            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>

            <label for="phone">Phone Number</label>
            <input type="tel" id="phone" name="phone" required>

            <label for="address">Address</label>
            <textarea id="address" name="address" rows="3" required></textarea>

            <label for="profile_picture">Profile Picture</label>
            <input type="file" id="profile_picture" name="profile_picture">

            <input type="submit" value="Register">
        </form>

        <?php if ($registered_user): ?>
            <div class="success-message">Registration successful!</div>
            <div class="user-details">
                <h3>Welcome, <?= htmlspecialchars($registered_user['first_name']) ?>!</h3>
                <?php if ($registered_user['profile_picture']): ?>
                    <img src="<?= htmlspecialchars($registered_user['profile_picture']) ?>" alt="Profile Picture">
                <?php endif; ?>
                <p><strong>Email:</strong> <?= htmlspecialchars($registered_user['email']) ?></p>
                <p><strong>Phone:</strong> <?= htmlspecialchars($registered_user['phone']) ?></p>
                <p><strong>Address:</strong> <?= htmlspecialchars($registered_user['address']) ?></p>
            </div>
        <?php endif; ?>
    </div>
</body>

</html>
