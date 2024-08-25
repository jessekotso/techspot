<?php
if (isset($_GET['course_name'], $_GET['date'], $_GET['time'])) {
    $course_name = htmlspecialchars($_GET['course_name']);
    $date = htmlspecialchars($_GET['date']);
    $time = htmlspecialchars($_GET['time']);

    $content = "Course Name: $course_name\nDate: $date\nTime: $time\n";
    $filename = "Schedule_" . str_replace(' ', '_', $course_name) . ".txt";

    header('Content-Type: text/plain');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    echo $content;
    exit();
} else {
    echo "Invalid schedule data.";
}
