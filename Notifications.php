<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifications</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h1>Notifications</h1>
        <nav>
            <ul>
                <li><a href="AdminDashboard.php">Back to Dashboard</a></li>
                <li><a href="#">Logout</a></li>
            </ul>
        </nav>
    </header>
    <section>
        <table>
            <thead>
                <tr>

                </tr>
            </thead>
            <tbody>
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
                            echo "<tr>";
                            echo "<td>" . $row['message'] . " ({$row['created_at']})</td>";
                            echo "<td><label><input type='checkbox' class='mark-as-read' data-notification-id='" . $row['id'] . "' onclick='markAsRead(this)'> Read</label></td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='2'>No notifications found.</td></tr>";
                    }
                    $stmt->close();
                    $conn->close();
                } else {
                    echo "<tr><td colspan='2'>User is not logged in.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </section>
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
                    // Disable the checkbox after marking as read
                    checkbox.disabled = true;
                }
            };
            xhr.send("notification_id=" + notificationId);
        }
    </script>
</body>
</html>

