<?php
session_start();
include '../Database.php';
include '../Classes/Sessions.php';

$db = new Database($servername, $username, $password, $dbname);
$conn = $db->getConnection();



// Assume other variables like $tutorId, $date, etc., are retrieved similarly
$tutorId = $_POST['tutorId'] ?? '';
$date = $_POST['date'] ?? '';
$startTime = $_POST['startTime'] ?? '';
$endTime = $_POST['endTime'] ?? '';
$courseId = $_POST['courseId'] ?? 0; // Default to 0 if not set


$studentId = $_SESSION['studentId'] ?? ''; // Assuming the student ID is stored in the session

// Other data might be retrieved from a form submission, for example
$firstName = $_POST['FirstName'] ?? '';
$lastName = $_POST['LastName'] ?? '';
$email = $_POST['email'] ?? '';
$message = $_POST['message'] ?? '';
$status = 'Pending'; // Default status for a new session request
$subject = $_POST['Subject'] ?? '';

$session = new Sessions($conn);
if ($session->createSessionRequest($tutorId, $studentId, $courseId, $firstName, $lastName, $email,$subject, $date, $startTime, $endTime, $message, $status)) {
    header("Location: ../StudentDashboard.php?success=true");
    exit();
} else {
    header("Location: ../StudentDashboard.php?error=Failed to create session.");
    exit();
}

?>
