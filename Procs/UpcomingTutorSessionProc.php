<?php
session_start();
include '../Database.php';
include '../Classes/Sessions.php';

// Check if the tutor is logged in and the required parameters are present
if (!isset($_SESSION['tutorId']) || !isset($_GET['sessionId']) || !isset($_GET['action'])) {
    die("Unauthorized access.");
}

try {
    // Sanitize input
    $tutorId = intval($_SESSION['tutorId']);
    $sessionId = intval($_GET['sessionId']);
    $action = $_GET['action'];

    // Create database connection
    $db = new Database($servername, $username, $password, $dbname);
    $conn = $db->getConnection();

    // Initialize Sessions class with database connection
    $session = new Sessions($conn);

    // Begin transaction for data integrity
    $conn->begin_transaction();

    // Retrieve session details
    $sessionDetailsResult = $session->getSessionDetails($sessionId);

    if (!$sessionDetailsResult) {
        throw new Exception("Session request not found.");
    }

    $requestDetails = $sessionDetailsResult;

    if ($action == 'accept') {
        // Actions for accepting the session request
        $session->updateSessionRequestStatus($sessionId, 'Approved');
        $session->deleteTutorAvailability($tutorId, $requestDetails['RequestDate'], $requestDetails['StartTime']);
        $session->createSession($tutorId, $requestDetails['StudentId'], $requestDetails['RequestDate'], $requestDetails['StartTime'], $requestDetails['Message']);
    } elseif ($action == 'deny') {
        // Action for denying the session request
        $session->updateSessionRequestStatus($sessionId, 'Denied');
    }

    // Commit transaction
    $conn->commit();
    header("Location: ../UpcomingTutorSessions.php?success=true");
    exit(); // Terminate script execution after redirect
} catch (Exception $e) {
    // Rollback transaction on error
    $conn->rollback();
    header("Location: ../UpcomingTutorSessions.php?error=" . urlencode($e->getMessage()));
    exit(); // Terminate script execution after redirect
}
?>
