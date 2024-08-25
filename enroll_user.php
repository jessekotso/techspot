<?php
// enroll.php
include 'php/db.php';
session_start();
$user_id = $_SESSION['user_id'] ?? null;

if (!$user_id) {
    header('Location: login.php');
    exit();
}

// Fetch all available courses
$sql_all_courses = "SELECT id, name, description FROM courses";
$stmt_all_courses = $pdo->query($sql_all_courses);
$all_courses = $stmt_all_courses->fetchAll(PDO::FETCH_ASSOC);

// Handle course enrollment
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['enroll_course'])) {
    $course_id = $_POST['course_id'];
    $sql_enroll = "INSERT INTO enrollments (user_id, course_id) VALUES (?, ?)";
    $stmt_enroll = $pdo->prepare($sql_enroll);
    if ($stmt_enroll->execute([$user_id, $course_id])) {
        $success_message = 'Successfully enrolled in the course!';
    } else {
        $error_message = 'Failed to enroll in the course.';
    }
}
?>

<div class="enroll">
    <h2>Enroll in a New Course</h2>
    <form method="POST" action="">
        <label for="course_id">Select Course:</label>
        <select id="course_id" name="course_id" required>
            <?php foreach ($all_courses as $course): ?>
                <option value="<?= htmlspecialchars($course['id']) ?>"><?= htmlspecialchars($course['name']) ?></option>
            <?php endforeach; ?>
        </select>
        <input type="submit" name="enroll_course" value="Enroll">
    </form>
</div>

<style>
    .enroll {
        background: #fff;
        padding: 20px;
        margin: 20px 0;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }
    select, input[type="submit"] {
        padding: 10px;
        margin: 10px 0;
    }
    input[type="submit"] {
        background: #007bff;
        color: #fff;
        border: none;
        padding: 10px 15px;
        border-radius: 5px;
        cursor: pointer;
    }
    input[type="submit"]:hover {
        background: #0056b3;
    }
</style>
