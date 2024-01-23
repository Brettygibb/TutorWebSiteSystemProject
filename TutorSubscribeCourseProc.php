<?php
session_start();
include 'Connect.php';

$userid = $_SESSION['id'];

// Get the selected course ID from the URL parameter
if (isset($_GET['courseId'])) {
    $selectedCourseId = $_GET['courseId'];

    // Insert the selected course into the tutor_courses table
    $insertSql = "INSERT INTO tutor_courses (TutorId, CourseId) VALUES ($userid, $selectedCourseId)";
    mysqli_query($conn, $insertSql);

    // Redirect to the Tutor Dashboard or any other page
    header("Location: TutorDashboard.php");
    exit();
} else {
    // Handle the case where no course ID is provided
    echo "Error: No course selected for subscription.";
}
?>
