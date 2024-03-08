<?php
session_start();

// Include necessary files
include 'Database.php';

// Create a new instance of the Database class
$database = new Database($servername, $username, $password, $dbname);

// Get the database connection
$conn = $database->getConnection();

// Check if the user is logged in
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
            // Add checkbox to mark notification as read using AJAX
            echo "<input type='checkbox' class='mark-as-read' data-notification-id='" . $row['id'] . "' onclick='markAsRead(this)'>";
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
<script>
    function markAsRead(checkbox) {
        var notificationId = checkbox.getAttribute('data-notification-id');
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "markasread.php", true);
        xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhr.onreadystatechange = function() {
            if (xhr.readyState == 4 && xhr.status == 200) {
                // Handle the response, if needed
                console.log(xhr.responseText);
            }
        };
        xhr.send("notification_id=" + notificationId);
    }
</script>

