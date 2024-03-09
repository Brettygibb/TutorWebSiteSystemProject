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
        $insertSql = "INSERT INTO requests (TutorId, CourseId, Status) VALUES (?, ?, ?)";
        $insertStmt = $conn->prepare($insertSql);

        // Status variable to hold the value
        $status = "Pending";

        // Bind parameters
        $insertStmt->bind_param("iis", $tutorid, $selectedCourseId, $status);

        // Execute the statement
        $insertStmt->execute();

        // Insert notification records for all admins
        $getAdminIdsSql = "SELECT UserId FROM admins";
        $adminIdsResult = $conn->query($getAdminIdsSql);
        while ($adminIdRow = $adminIdsResult->fetch_assoc()) {
            $adminId = $adminIdRow['UserId'];
            $notificationSql = "INSERT INTO notifications (user_id, message, is_read) VALUES (?, ?, 0)";
            $notificationStmt = $conn->prepare($notificationSql);
            $notificationStmt->bind_param("is", $adminId, $message);
            $message = "A new course request is pending approval.";
            $notificationStmt->execute();
        }

        // Close statement
        $notificationStmt->close();

        // Close statement
        $insertStmt->close();

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
$stmt->close();
$conn->close();
?>
