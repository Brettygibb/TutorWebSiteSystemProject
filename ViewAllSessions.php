<?php
session_start();
include 'Database.php';
require_once 'Tutor.php'; // Include the Tutor class

// Create a new instance of the Database class
$database = new Database($servername, $username, $password, $dbname);

// Get the database connection
$conn = $database->getConnection();

$tutorId = isset($_GET['Id']) ? $_GET['Id'] : 0;
$courseId = isset($_GET['Course']) ? $_GET['Course'] : 0; // Add this line to get the courseId


// Fetch tutor details including the first name
$stmt = $conn->prepare("SELECT u.FirstName
                       FROM tutors AS t
                       INNER JOIN users AS u ON t.UserId = u.UserId
                       WHERE t.TutorId = ?");
$stmt->bind_param("i", $tutorId);
$stmt->execute();
$result = $stmt->get_result();
$tutorDetails = $result->fetch_assoc();

// Create an instance of the Tutor class with fetched details
$tutor = new Tutor($tutorDetails['FirstName'], '', $tutorId);

$stmt->close();

// Fetch available sessions for the tutor
$stmt = $conn->prepare("SELECT AvailableDate, StartTime, EndTime 
                        FROM tutor_availability 
                        WHERE TutorId = ?
                        ORDER BY AvailableDate, StartTime");
$stmt->bind_param("i", $tutorId);
$stmt->execute();
$result = $stmt->get_result();
$timeSlots = [];
while ($row = $result->fetch_assoc()) {
    // Format dates and times
    $availableDate = new DateTime($row['AvailableDate']);
    $startTime = new DateTime($row['StartTime']);
    $endTime = new DateTime($row['EndTime']);

    // Adjust the format as per your preference
    $formattedDate = $availableDate->format('F j, Y');
    $formattedStartTime = $startTime->format('g:i A');
    $formattedEndTime = $endTime->format('g:i A');

    $timeSlots[] = [
        'AvailableDate' => $formattedDate,
        'StartTime' => $formattedStartTime,
        'EndTime' => $formattedEndTime,
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
    <h1>Available Sessions for Tutor: <?php echo $tutor->getFirstName(); ?></h1>
    <?php if (count($timeSlots) > 0): ?>
        <ul>
            <?php foreach ($timeSlots as $slot): ?>
                <li>
                    <?php echo $slot['AvailableDate'] . " from " . $slot['StartTime'] . " to " . $slot['EndTime']; ?>
                    <!-- Link to requestSession.php with session details -->
                    <!--<a href="RequestSessionForm.php?tutorId=<?php echo urlencode($tutorId); ?>&date=<?php echo urlencode($slot['AvailableDate']); ?>&startTime=<?php echo urlencode($slot['StartTime']); ?>&endTime=<?php echo urlencode($slot['EndTime']); ?>">Request Session</a> -->
                    <a href="RequestSessionForm.php?tutorId=<?php echo urlencode($tutorId); ?>&courseId=<?php echo urlencode($courseId); ?>&date=<?php echo urlencode($slot['AvailableDate']); ?>&startTime=<?php echo urlencode($slot['StartTime']); ?>&endTime=<?php echo urlencode($slot['EndTime']); ?>">Request Session</a>

                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>No available sessions.</p>
    <?php endif; ?>
</body>
</html>
