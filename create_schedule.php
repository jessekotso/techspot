<?php
include 'php/db.php'; // Include the database connection
include 'templates/header.php';

$scheduleCreated = false;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve form data
    $course_name = $_POST['course_name'];
    $instructor_name = $_POST['instructor_name'];
    $location = $_POST['location'];
    $date = $_POST['date'];
    $time = $_POST['time'];
    $capacity = $_POST['capacity'];
    $description = $_POST['description'];

    // Validate the input
    if (empty($course_name) || empty($instructor_name) || empty($location) || empty($date) || empty($time) || empty($capacity)) {
        $error = 'All fields marked with * are required.';
    } else {
        try {
            // Prepare and execute the SQL statement to insert the schedule
            $sql = "INSERT INTO training_schedule (course_name, instructor_name, location, date, time, capacity, description) 
                    VALUES (:course_name, :instructor_name, :location, :date, :time, :capacity, :description)";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':course_name', $course_name, PDO::PARAM_STR);
            $stmt->bindParam(':instructor_name', $instructor_name, PDO::PARAM_STR);
            $stmt->bindParam(':location', $location, PDO::PARAM_STR);
            $stmt->bindParam(':date', $date, PDO::PARAM_STR);
            $stmt->bindParam(':time', $time, PDO::PARAM_STR);
            $stmt->bindParam(':capacity', $capacity, PDO::PARAM_INT);
            $stmt->bindParam(':description', $description, PDO::PARAM_STR);
            $stmt->execute();

            $success = "Schedule created successfully!";
            $scheduleCreated = true;

            // Fetch the last inserted schedule to display
            $schedule = [
                'course_name' => $course_name,
                'instructor_name' => $instructor_name,
                'location' => $location,
                'date' => $date,
                'time' => $time,
                'capacity' => $capacity,
                'description' => $description,
            ];
        } catch (PDOException $e) {
            $error = "Error creating schedule: " . $e->getMessage();
        }
    }
}
?>

<div class="container">
    <h1>Create Training Schedule</h1>

    <?php if (isset($error)): ?>
        <div class="message error"><?= htmlspecialchars($error) ?></div>
    <?php elseif (isset($success)): ?>
        <div class="message success"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>

    <form method="POST" action="">
        <div class="form-group">
            <label for="course_name">Course Name *:</label>
            <input type="text" id="course_name" name="course_name" required>
        </div>
        <div class="form-group">
            <label for="instructor_name">Instructor Name *:</label>
            <input type="text" id="instructor_name" name="instructor_name" required>
        </div>
        <div class="form-group">
            <label for="location">Location *:</label>
            <input type="text" id="location" name="location" required>
        </div>
        <div class="form-group">
            <label for="date">Date *:</label>
            <input type="date" id="date" name="date" required>
        </div>
        <div class="form-group">
            <label for="time">Time *:</label>
            <input type="time" id="time" name="time" required>
        </div>
        <div class="form-group">
            <label for="capacity">Capacity *:</label>
            <input type="number" id="capacity" name="capacity" required min="1">
        </div>
        <div class="form-group">
            <label for="description">Course Description:</label>
            <textarea id="description" name="description" rows="4"></textarea>
        </div>
        <button type="submit" class="btn">Create Schedule</button>
    </form>

    <?php if ($scheduleCreated): ?>
        <div class="schedule-detail">
            <h2>Schedule Details</h2>
            <table class="detail-table">
                <tr>
                    <th>Course Name</th>
                    <td><?= htmlspecialchars($schedule['course_name']) ?></td>
                </tr>
                <tr>
                    <th>Instructor Name</th>
                    <td><?= htmlspecialchars($schedule['instructor_name']) ?></td>
                </tr>
                <tr>
                    <th>Location</th>
                    <td><?= htmlspecialchars($schedule['location']) ?></td>
                </tr>
                <tr>
                    <th>Date</th>
                    <td><?= htmlspecialchars($schedule['date']) ?></td>
                </tr>
                <tr>
                    <th>Time</th>
                    <td><?= htmlspecialchars($schedule['time']) ?></td>
                </tr>
                <tr>
                    <th>Capacity</th>
                    <td><?= htmlspecialchars($schedule['capacity']) ?></td>
                </tr>
                <tr>
                    <th>Description</th>
                    <td><?= htmlspecialchars($schedule['description']) ?></td>
                </tr>
            </table>
            <button onclick="window.print();" class="btn">Print Schedule</button>
            <a href="save_schedule.php?course_name=<?= urlencode($schedule['course_name']) ?>&instructor_name=<?= urlencode($schedule['instructor_name']) ?>&location=<?= urlencode($schedule['location']) ?>&date=<?= urlencode($schedule['date']) ?>&time=<?= urlencode($schedule['time']) ?>&capacity=<?= urlencode($schedule['capacity']) ?>&description=<?= urlencode($schedule['description']) ?>" class="btn">Save as File</a>
        </div>
    <?php endif; ?>
</div>

<?php include 'templates/footer.php'; ?>

<style>
    .container {
        max-width: 700px;
        margin: 0 auto;
        padding: 20px;
    }

    h1, h2 {
        text-align: center;
        color: #2c3e50;
        margin-bottom: 30px;
    }

    .form-group {
        margin-bottom: 20px;
    }

    label {
        display: block;
        font-weight: bold;
        margin-bottom: 5px;
        color: #2c3e50;
    }

    input[type="text"], input[type="date"], input[type="time"], input[type="number"], textarea {
        width: 100%;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 5px;
        font-size: 1rem;
    }

    textarea {
        resize: none;
    }

    .btn {
        background-color: #1abc9c;
        color: #fff;
        padding: 10px 20px;
        text-decoration: none;
        border-radius: 5px;
        display: inline-block;
        transition: background-color 0.3s;
        margin-top: 20px;
    }

    .btn:hover {
        background-color: #16a085;
    }

    .message {
        padding: 10px;
        margin-bottom: 20px;
        border-radius: 5px;
        font-size: 1rem;
    }

    .message.error {
        background-color: #e74c3c;
        color: #fff;
    }

    .message.success {
        background-color: #2ecc71;
        color: #fff;
    }

    .schedule-detail {
        margin-top: 40px;
    }

    .detail-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 20px;
    }

    .detail-table th, .detail-table td {
        text-align: left;
        padding: 10px;
        border: 1px solid #ccc;
    }

    .detail-table th {
        background-color: #f4f4f4;
        font-weight: bold;
    }
</style>
