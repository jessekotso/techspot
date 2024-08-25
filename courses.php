<?php
// courses.php
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

// Fetch courses the user is enrolled in
$sql_courses = "SELECT c.id, c.name, c.description, u.first_name AS instructor_first_name, u.last_name AS instructor_last_name 
                FROM enrollments e 
                JOIN courses c ON e.course_id = c.id 
                JOIN users u ON c.instructor_id = u.id 
                WHERE e.user_id = ?";
$stmt_courses = $pdo->prepare($sql_courses);
$stmt_courses->execute([$user_id]);
$courses = $stmt_courses->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Courses</title>
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
        <h2>Your Courses</h2>
        <?php if (empty($courses)): ?>
            <p>You are not enrolled in any courses.</p>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>Course Name</th>
                        <th>Description</th>
                        <th>Instructor</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($courses as $course): ?>
                        <tr>
                            <td><?= htmlspecialchars($course['name']) ?></td>
                            <td><?= htmlspecialchars($course['description']) ?></td>
                            <td><?= htmlspecialchars($course['instructor_first_name'] . ' ' . $course['instructor_last_name']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</body>
</html>
