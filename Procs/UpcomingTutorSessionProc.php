<?php

session_start();
include '../Database.php';

// Check if the tutor is logged in and the required parameters are present
if (!isset($_SESSION['tutorId']) || !isset($_GET['sessionId']) || !isset($_GET['action'])) {
    die("Unauthorized access.");
}

$tutorId = $_SESSION['tutorId'];
$sessionId = $_GET['sessionId'];
$action = $_GET['action'];

$db = new Database($servername, $username, $password, $dbname);
$conn = $db->getConnection();

// Begin transaction for data integrity
$conn->begin_transaction();

try {
    $stmt = $conn->prepare("SELECT * FROM session_request WHERE RequestId = ? AND TutorId = ?");
    $stmt->bind_param("ii", $sessionId, $tutorId);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows === 0) {
        throw new Exception("Session request not found.");
    }
    $requestDetails = $result->fetch_assoc();
    $stmt->close();


    if ($action == 'accept') {
        // Update the session request status to 'Accepted'
        $stmt = $conn->prepare("UPDATE session_request SET status = 'Approved' WHERE RequestId = ? AND TutorId = ?");
        $stmt->bind_param("ii", $sessionId, $tutorId);
        $stmt->execute();
        $stmt->close();

        //Remove tutor availability
        $stmt = $conn->prepare("DELETE FROM tutor_availability WHERE TutorId = ? AND AvailableDate = ? AND StartTime = ?");
        $stmt->bind_param("iss", $tutorId, $requestDetails['RequestDate'], $requestDetails['StartTime']);
        $stmt->execute();
        $stmt->close();

        // Insert into sessions table
        $stmt = $conn->prepare("INSERT INTO sessions (TutorId, StudentId, DateAndTime, StartTime, Notes) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("iisss", $tutorId, $requestDetails['StudentId'], $requestDetails['RequestDate'], $requestDetails['StartTime'], $requestDetails['Message']);
        $stmt->execute();
        $stmt->close();

    } elseif ($action == 'deny') {
        // Update the session request status to 'Denied'
        $stmt = $conn->prepare("UPDATE session_request SET status = 'Denied' WHERE RequestId = ? AND TutorId = ?");
        $stmt->bind_param("ii", $sessionId, $tutorId);
        $stmt->execute();
        $stmt->close();
    }

    // Commit transaction
    $conn->commit();
    header("Location: ../UpcomingTutorSessions.php?success=true");
} catch (Exception $e) {
    // Rollback transaction on error
    $conn->rollback();
    header("Location: ../UpcomingTutorSessions.php?error=" . $e->getMessage());
}

$conn->close();
?>



