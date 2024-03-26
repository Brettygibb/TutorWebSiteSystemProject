<?php
session_start();
require_once '../Database.php'; // Adjust the path as needed
require_once '../Classes/Sessions.php'; // Adjust the path as needed

// Ensure that this script can only be accessed through POST method
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die('Invalid request method.');
}

if (!isset($_POST['sessionId'], $_SESSION['tutorId'])) {
    die("Required information missing.");
}

// Sanitize input
$sessionId = intval($_POST['sessionId']);
$tutorId = intval($_SESSION['tutorId']); // Assuming tutorId is already sanitized when stored in session

// Create database connection
$db = new Database($servername, $username, $password, $dbname); // Add parameters as required by your constructor
$conn = $db->getConnection();

// Initialize Sessions class with database connection
$session = new Sessions($conn);

// Attempt to mark the session as completed
$result = $session->markSessionAsComplete($sessionId);

if ($result) {
    header("Location: ../TutorDashBoard.php?success=Session marked as completed");
} else {
    header("Location: ../TutorDashBoard.php?error=Unable to mark session as completed");
}
exit();
