<?php
session_start();
include 'php/db.php'; // Include the database connection
include 'templates/header.php';

// Get the course ID from the URL
$course_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Fetch course details from the database
$stmt = $pdo->prepare("SELECT * FROM courses WHERE id = :id LIMIT 1");
$stmt->bindParam(':id', $course_id, PDO::PARAM_INT);
$stmt->execute();
$course = $stmt->fetch(PDO::FETCH_ASSOC);

// If the course doesn't exist, redirect or show an error
if (!$course) {
    echo "<p>Course not found.</p>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($course['name']) ?> | Course Details</title>
    <link rel="stylesheet" href="css/style.css"> <!-- Ensure this points to your main stylesheet -->
    <style>
        /* Basic styling for the course detail page */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f4f4;
            color: #333;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        .course-header {
            display: flex;
            flex-wrap: wrap;
            margin-bottom: 40px;
        }

        .course-header img {
            max-width: 100%;
            height: auto;
            border-radius: 10px;
            margin-right: 20px;
        }

        .course-header h1 {
            font-size: 2.5rem;
            color: #2c3e50;
            margin-bottom: 10px;
        }

        .course-header p {
            font-size: 1.2rem;
            margin-bottom: 10px;
            color: #555;
        }

        .course-content {
            margin-bottom: 40px;
        }

        .course-content h2 {
            font-size: 2rem;
            color: #2c3e50;
            margin-bottom: 20px;
        }

        .course-content p {
            font-size: 1rem;
            line-height: 1.6;
            color: #333;
        }

        .course-actions {
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
            margin-top: 30px;
        }

        .course-actions .btn {
            background-color: #1abc9c;
            color: white;
            padding: 15px 30px;
            text-decoration: none;
            border-radius: 5px;
            font-size: 1.2rem;
            transition: background-color 0.3s, transform 0.3s;
            text-align: center;
            margin-bottom: 10px;
        }

        .course-actions .btn:hover {
            background-color: #16a085;
            transform: scale(1.05);
        }
    </style>
</head>
<body>
<div class="container">
    <div class="course-header">
        <img src="img/<?= htmlspecialchars($course['image']) ?>" alt="<?= htmlspecialchars($course['name']) ?>">
        <div>
            <h1><?= htmlspecialchars($course['name']) ?></h1>
            <p><?= htmlspecialchars($course['description']) ?></p>
            <p><strong>Duration:</strong> <?= htmlspecialchars($course['duration']) ?> weeks</p>
            <p><strong>Instructor:</strong> <?= htmlspecialchars($course['instructor_name']) ?></p>
        </div>
    </div>

    <div class="course-content">
        <h2>Course Overview</h2>
        <p><?= nl2br(htmlspecialchars($course['overview'])) ?></p>

        <h2>Syllabus</h2>
        <p><?= nl2br(htmlspecialchars($course['syllabus'])) ?></p>
    </div>

    <div class="course-actions">
        <a href="enroll.php?course_id=<?= htmlspecialchars($course['id']) ?>" class="btn">Enroll Now</a>
        <a href="courses.php" class="btn">Back to Courses</a>
    </div>
</div>
</body>
</html>

<?php include 'templates/footer.php'; ?>
