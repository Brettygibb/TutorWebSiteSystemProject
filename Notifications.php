<?php
session_start();

// Include necessary files
include 'Database.php';

// Create a new instance of the Database class
$database = new Database($servername, $username, $password, $dbname);

// Get the database connection
$conn = $database->getConnection();

// Check if the admin user is logged in
if (isset($_SESSION['id'])) {
    $userId = $_SESSION['id']; // Modified variable name to $userId

    // Fetch notifications for the logged-in user
    $sql = "SELECT * FROM notifications WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        echo "Error: " . $conn->error;
        exit();
    }
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();

    // Display notifications
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<div class='notification'>";
            echo "<p>" . $row['message'] . "</p>";
            echo "<span class='timestamp'>" . $row['created_at'] . "</span>";
            echo "</div>";
        }
    } else {
        echo "No notifications found.";
    }
    $stmt->close();
    $conn->close();
} else {
    echo "User is not logged in.";
}
?>

