<?php
// enroll.php
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

// Fetch user profile information
$sql_user = "SELECT first_name, last_name, email, phone FROM users WHERE id = ?";
$stmt_user = $pdo->prepare($sql_user);
$stmt_user->execute([$user_id]);
$user = $stmt_user->fetch(PDO::FETCH_ASSOC);

// Fetch all available courses
$sql_all_courses = "SELECT id, name, description, duration, schedule FROM courses";
$stmt_all_courses = $pdo->query($sql_all_courses);
$all_courses = $stmt_all_courses->fetchAll(PDO::FETCH_ASSOC);

// Handle course enrollment
$success_message = '';
$error_message = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['enroll_course'])) {
    $course_id = $_POST['course_id'];
    $user_notes = $_POST['user_notes'];
    $preferred_start_date = $_POST['preferred_start_date'];
    $additional_requirements = $_POST['additional_requirements'];

    // Insert enrollment data into the database
    $sql_enroll = "INSERT INTO enrollments (user_id, course_id, user_notes, preferred_start_date, additional_requirements) VALUES (?, ?, ?, ?, ?)";
    $stmt_enroll = $pdo->prepare($sql_enroll);
    if ($stmt_enroll->execute([$user_id, $course_id, $user_notes, $preferred_start_date, $additional_requirements])) {
        $success_message = 'Successfully enrolled in the course!';
    } else {
        $error_message = 'Failed to enroll in the course.';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enroll in a Course</title>
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
        input[type="text"], input[type="date"], textarea, select {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }
        textarea {
            resize: vertical;
        }
        input[type="submit"] {
            background: #007bff;
            color: #fff;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
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
        .course-details {
            margin-top: 10px;
            padding: 10px;
            background-color: #f8f9fa;
            border: 1px solid #ddd;
            border-radius: 5px;
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

        <h2>Enroll in a New Course</h2>
        <form method="POST" action="">
            <div class="form-group">
                <label for="course_id">Select Course:</label>
                <select id="course_id" name="course_id" required onchange="showCourseDetails(this)">
                    <option value="">-- Select Course --</option>
                    <?php foreach ($all_courses as $course): ?>
                        <option value="<?= htmlspecialchars($course['id']) ?>" data-description="<?= htmlspecialchars($course['description']) ?>" data-duration="<?= htmlspecialchars($course['duration']) ?>" data-schedule="<?= htmlspecialchars($course['schedule']) ?>">
                            <?= htmlspecialchars($course['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div id="course-details" class="course-details" style="display: none;">
                <h4>Course Details:</h4>
                <p><strong>Description:</strong> <span id="course-description"></span></p>
                <p><strong>Duration:</strong> <span id="course-duration"></span></p>
                <p><strong>Schedule:</strong> <span id="course-schedule"></span></p>
            </div>

            <div class="form-group">
                <label for="user_notes">Why do you want to enroll in this course?</label>
                <textarea id="user_notes" name="user_notes" rows="4" required placeholder="Explain your motivation for enrolling..."></textarea>
            </div>

            <div class="form-group">
                <label for="preferred_start_date">Preferred Start Date:</label>
                <input type="date" id="preferred_start_date" name="preferred_start_date" required>
            </div>

            <div class="form-group">
                <label for="additional_requirements">Any Additional Requirements?</label>
                <textarea id="additional_requirements" name="additional_requirements" rows="3" placeholder="Do you have any special requirements or needs?"></textarea>
            </div>

            <div class="form-group">
                <label>Contact Information:</label>
                <p><strong>Name:</strong> <?= htmlspecialchars($user['first_name'] . ' ' . $user['last_name']) ?></p>
                <p><strong>Email:</strong> <?= htmlspecialchars($user['email']) ?></p>
                <p><strong>Phone:</strong> <?= htmlspecialchars($user['phone']) ?></p>
            </div>

            <input type="submit" name="enroll_course" value="Enroll">
        </form>
    </div>

    <script>
        function showCourseDetails(selectElement) {
            var selectedOption = selectElement.options[selectElement.selectedIndex];
            var description = selectedOption.getAttribute('data-description');
            var duration = selectedOption.getAttribute('data-duration');
            var schedule = selectedOption.getAttribute('data-schedule');

            if (description && duration && schedule) {
                document.getElementById('course-description').innerText = description;
                document.getElementById('course-duration').innerText = duration;
                document.getElementById('course-schedule').innerText = schedule;
                document.getElementById('course-details').style.display = 'block';
            } else {
                document.getElementById('course-details').style.display = 'none';
            }
        }
    </script>
</body>
</html>
