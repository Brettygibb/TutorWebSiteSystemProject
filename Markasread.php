<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['mark_as_read']) && isset($_POST['notification_id'])) {
    // Include necessary files
    include 'Database.php';

    // Create a new instance of the Database class
    $database = new Database($servername, $username, $password, $dbname);

    // Get the database connection
    $conn = $database->getConnection();

    // Update the notification as read
    $notificationId = $_POST['notification_id'];
    $sql = "UPDATE notifications SET is_read = 1 WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $notificationId);
    $stmt->execute();

    // Check if the update was successful
    if ($stmt->affected_rows > 0) {
        echo "Notification marked as read successfully.";
    } else {
        echo "Failed to mark notification as read.";
    }

    $stmt->close();
    $conn->close();
} else {
    echo "Invalid request.";
}
?>
