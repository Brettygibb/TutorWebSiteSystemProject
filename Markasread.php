<?php
session_start();

// Include necessary files
include 'Database.php';

// Create a new instance of the Database class
$database = new Database($servername, $username, $password, $dbname);

// Get the database connection
$conn = $database->getConnection();

// Check if the user is logged in
if (isset($_SESSION['id']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = $_SESSION['id'];
    $notificationId = $_POST['notification_id'];

    // Update the notification as read in the database
    $sql = "UPDATE notifications SET is_read = 1 WHERE id = ? AND user_id = ?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        echo "Error: " . $conn->error;
        exit();
    }
    $stmt->bind_param("ii", $notificationId, $userId);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo "Notification marked as read successfully.";
    } else {
        echo "Failed to mark notification as read.";
    }
    $stmt->close();
    $conn->close();
} else {
    echo "User is not logged in or invalid request method.";
}
?>
