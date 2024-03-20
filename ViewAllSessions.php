<?php 
session_start();
include 'Database.php';
require_once 'Tutor.php'; // Include the Tutor class

// Create a new instance of the Database class
$database = new Database($servername, $username, $password, $dbname);

// Get the database connection
$conn = $database->getConnection();

// Initialize variables from the URL or default them
$tutorId = isset($_GET['Id']) ? $_GET['Id'] : 0;
$courseId = isset($_GET['Course']) ? (int)$_GET['Course'] : 0; // Ensure integer value

// Fetch tutor and profile information
$sql = "SELECT u.FirstName, u.LastName, up.* FROM users_profiles up JOIN tutors t ON t.UserId = up.UserId JOIN users u ON t.UserId = u.UserId";
$stmt = $conn->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();
$tutor = $result->fetch_assoc();
$stmt->close();

// Fetch available time slots
$stmt = $conn->prepare("SELECT AvailableDate, StartTime, EndTime FROM tutor_availability WHERE TutorId = ? ORDER BY AvailableDate, StartTime;");
$stmt->bind_param("i", $tutorId);
$stmt->execute();
$result = $stmt->get_result();
$timeSlots = [];
while ($row = $result->fetch_assoc()) {
    $timeSlots[] = [
        'AvailableDate' => $row['AvailableDate'],
        'StartTime' => $row['StartTime'],
        'EndTime' => $row['EndTime'],
    ];
}
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Available Sessions</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <?php include 'Includes/StudentHeader.php'; ?>
    <!-- Tutor Information -->
    <h1>Available Sessions for Tutor ID: <?php echo htmlspecialchars($tutorId); ?></h1>
    <h1>Course ID: <?php echo htmlspecialchars($courseId); ?></h1>
    <h1>Student ID: <?php echo htmlspecialchars($_SESSION['studentId'] ?? 'Not Logged In'); ?></h1>

    <!-- Time Slots and Request Session Link -->
    <?php if (count($timeSlots) > 0): ?>
        <ul>
            <?php foreach ($timeSlots as $slot): ?>
                <li>
                    <?php echo htmlspecialchars($slot['AvailableDate']) . " from " . htmlspecialchars($slot['StartTime']) . " to " . htmlspecialchars($slot['EndTime']); ?>
                    <a href="RequestSessionForm.php?tutorId=<?php echo urlencode($tutorId); ?>&date=<?php echo urlencode($slot['AvailableDate']); ?>&startTime=<?php echo urlencode($slot['StartTime']); ?>&endTime=<?php echo urlencode($slot['EndTime']); ?>&course=<?php echo urlencode($courseId); ?>">Request Session</a>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>No available sessions.</p>
    <?php endif; ?>
</body>
</html>
