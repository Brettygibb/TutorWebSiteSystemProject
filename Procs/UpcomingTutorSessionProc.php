<?php
/*
session_start();
require '../Database.php';

$db = new Database($servername, $username, $password, $dbname);
$conn = $db->getConnection();

// Assuming these values are passed through the request
$RequestId = $_GET['RequestId'];
$action = $_GET['action']; // 'accept' or 'deny'

// Fetch session request details
$stmt = $conn->prepare("SELECT * FROM session_request WHERE RequestId = ?");
$stmt->bind_param("i", $sessionId);
$stmt->execute();
$requestDetails = $stmt->get_result()->fetch_assoc();
$stmt->close();
// convert the date to a datetime object
$requestdate = new DateTime($requestDetails['RequestDate']);
if ($action == 'accept') {
    // Begin transaction
    $conn->begin_transaction();
    try {
        // Insert into sessions table
        $stmt = $conn->prepare("INSERT INTO sessions (TutorId, StudentId, DateAndTime,StartTime Notes) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("iiss", $requestDetails['TutorId'], $requestDetails['StudentId'], $requestdate, $requestDetails['StartTime'], $requestDetails['message']);

        // Delete from tutor_availability
        $stmt = $conn->prepare("DELETE FROM tutor_availability WHERE TutorId = ? AND AvailableDate = ? AND StartTime = ? AND EndTime = ?");
        $stmt->bind_param("isss", $requestDetails['tutorId'], $requestDetails['requestdate'], $requestDetails['starttime'], $requestDetails['endtime']);
        $stmt->execute();
        $stmt->close();

        // Commit transaction
        $conn->commit();
    } catch (Exception $e) {
        $conn->rollback(); // Rollback transaction on error
        // Handle error
    }
} else if ($action == 'deny') {
    // Update session request to 'Denied'
    $stmt = $conn->prepare("UPDATE session_request SET status = 'Denied' WHERE RequestId = ?");
    $stmt->bind_param("i", $sessionId);
    $stmt->execute();
    $stmt->close();
}

// Redirect or inform the user of the result
header("Location: ../UpcomingTutorSessions.php?success=true");
*/

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
        $stmt->bind_param("iss", $tutorId, $requestDetails['requestdate'], $requestDetails['starttime']);
        $stmt->execute();
        $stmt->close();

        // Insert into sessions table
        $stmt = $conn->prepare("INSERT INTO sessions (TutorId, StudentId, DateAndTime, StartTime, Notes) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("iisss", $tutorId, $requestDetails['StudentId'], $requestDetails['RequestDate'], $requestDetails['StartTime'], $requestDetails['Message']);
        $stmt->execute();
        $stmt->close();
        
        // Prepare the notification insertion query for the student
        $sqlNotification = "INSERT INTO notifications (user_id, message) VALUES (?, ?)";
        $stmtNotification = $conn->prepare($sqlNotification);
        $message = "Your session request has been approved. Please review.";
        $stmtNotification->bind_param("is", $requestDetails['StudentId'], $message);
        $stmtNotification->execute();
        $stmtNotification->close();
        


    } elseif ($action == 'deny') {
        // Update the session request status to 'Denied'
        $stmt = $conn->prepare("UPDATE session_request SET status = 'Denied' WHERE RequestId = ? AND TutorId = ?");
        $stmt->bind_param("ii", $sessionId, $tutorId);
        $stmt->execute();
        $stmt->close();
        
        // Prepare the notification insertion query for the student
        $sqlNotification = "INSERT INTO notifications (user_id, message) VALUES (?, ?)";
        $stmtNotification = $conn->prepare($sqlNotification);
        $message = "Your session request has been denied. Please review.";
        $stmtNotification->bind_param("is", $requestDetails['StudentId'], $message);
        $stmtNotification->execute();
        $stmtNotification->close();
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



