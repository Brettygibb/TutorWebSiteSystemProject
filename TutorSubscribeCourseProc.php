<?php
session_start();
include 'Database.php';

if (!isset($_SESSION['id'])) {
    // Redirect user or handle the case where the session is not set
    echo "User is not logged in.";
    exit(); // Stop script execution
}

$db = new Database($servername, $username, $password, $dbname);
$conn = $db->getConnection();
$userid = $_SESSION['id'];

// Using prepared statements to prevent SQL injection
$getTutorIdSql = "SELECT TutorId FROM tutors WHERE UserId = ?";
$stmt = $conn->prepare($getTutorIdSql);
$stmt->bind_param("i", $userid);
$stmt->execute();
$result = $stmt->get_result();

if ($result && $row = $result->fetch_assoc()) {
    $tutorid = $row['TutorId'];

    if (isset($_GET['courseId'])) {
        $selectedCourseId = $_GET['courseId'];

        // Ensure that courseId is an integer to prevent SQL injection
        if (!filter_var($selectedCourseId, FILTER_VALIDATE_INT)) {
            echo "Invalid course ID.";
            exit();
        }

        // Use prepared statement for insertion to prevent SQL injection
        $insertSql = "INSERT INTO requests (TutorId, CourseId, Status) VALUES (?, ?, 'Pending')";
        $insertStmt = $conn->prepare($insertSql);
        $insertStmt->bind_param("ii", $tutorid, $selectedCourseId);
        $insertStmt->execute();

        // Redirect to the Tutor Dashboard
        header("Location: TutorDashboard.php");
        exit();
    } else {
        echo "Error: No course selected for subscription.";
    }
} else {
    echo "Error: Tutor ID not found for the given user ID or unable to fetch tutor ID.";
}

// Close statement and connection if open
if (isset($stmt)) {
    $stmt->close();
}
$conn->close();
?>
