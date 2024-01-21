<?php
session_start();
include 'Connect.php';

// Check if the user is logged in as a tutor
if ($_SESSION['role'] !== 'Tutor' || !isset($_SESSION['id'])) {
    header("Location: Login.php");
    exit();
}

$tutorId = $_SESSION['id'];

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if courses are selected
    if (isset($_POST['courses']) && is_array($_POST['courses'])) {
        $selectedCourses = $_POST['courses'];

        // Prepare and execute INSERT queries to subscribe the tutor to selected courses
        foreach ($selectedCourses as $courseId) {
            $sql = "INSERT INTO tutors (UserId, CourseId) VALUES (?, ?)";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "ii", $tutorId, $courseId);
            mysqli_stmt_execute($stmt);
        }

        echo "Courses subscribed successfully!";
    } else {
        echo "No courses selected for subscription.";
    }
} else {
    echo "Invalid request.";
}

mysqli_close($conn);
?>
