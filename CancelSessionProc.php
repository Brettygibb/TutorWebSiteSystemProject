<?php
session_start();
require 'Database.php';

$db = new Database($servername, $username, $password, $dbname);
$conn = $db->getConnection();

if (isset($_POST['cancelSession'], $_POST['sessionId'])) {
    $sessionId = $_POST['sessionId'];
    $studentId = $_SESSION['studentId'];

    // Start transaction for atomicity
    $conn->begin_transaction();

    try {
        // Delete the session
        $stmt = $conn->prepare("DELETE FROM sessions WHERE SessionId = ? AND StudentId = ?");
        $stmt->bind_param("ii", $sessionId, $studentId);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            // Notification message
            $message = "A session has been canceled.";

            // Fetch all admin user IDs
            $adminsQuery = "SELECT UserId FROM admins";
            $result = $conn->query($adminsQuery);

            // Prepare notification statement
            $notificationStmt = $conn->prepare("INSERT INTO notifications (user_id, message) VALUES (?, ?)");

            while ($admin = $result->fetch_assoc()) {
                // Notify each admin
                $notificationStmt->bind_param("is", $admin['UserId'], $message);
                $notificationStmt->execute();
            }

            // Commit transaction
            $conn->commit();
            $_SESSION['message'] = "Session cancelled successfully and admins notified.";
        } else {
            throw new Exception("Error cancelling session.");
        }

        // Close statements
        $stmt->close();
        $notificationStmt->close();
    } catch (Exception $e) {
        $conn->rollback();
        $_SESSION['message'] = "Transaction failed: " . $e->getMessage();
    }

    // Redirect back to the dashboard
    header('Location: StudentDashboard.php');
    exit;
}
?>