<?php
session_start();
include 'Connect.php';

$userid = $_SESSION['id'];

// Get the tutorid based on the userid
$getTutorIdSql = "SELECT TutorId FROM tutors WHERE UserId = $userid";
$result = mysqli_query($conn, $getTutorIdSql);

if ($result) {
    $row = mysqli_fetch_assoc($result);

    // Check if a tutorid is found for the given userid
    if ($row) {
        $tutorid = $row['TutorId'];

        // Get the selected course ID from the URL parameter
        if (isset($_GET['courseId'])) {
            $selectedCourseId = $_GET['courseId'];

            // Insert the selected course into the requests table with Status 'Pending'
            $insertSql = "INSERT INTO requests (TutorId, CourseId, Status) VALUES ($tutorid, $selectedCourseId, 'Pending')";
            mysqli_query($conn, $insertSql);

            if ($insertResult) {
                // Redirect to the Tutor Dashboard or any other page
                header("Location: TutorDashboard.php");
                exit();
            } else {
                // Handle the case where the SQL insert query fails
                echo "Error: Unable to insert request for subscription.";
            }
        } else {
            // Handle the case where no course ID is provided
            echo "Error: No course selected for subscription.";
        }
    } else {
        // Handle the case where no tutorid is found for the given userid
        echo "Error: Tutor ID not found for the given user ID.";
    }
} else {
    // Handle the case where the SQL query fails
    echo "Error: Unable to fetch tutor ID.";
}
?>
