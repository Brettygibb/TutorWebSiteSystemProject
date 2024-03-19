<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Available Sessions</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <?php include 'Includes/StudentHeader.php'; ?>

    <?php
    // Include the Tutor class
    require_once 'Tutor.php';

    // Get the tutor ID from the query string
    $tutorId = isset($_GET['Id']) ? $_GET['Id'] : 0;

    // Prepare the database connection
    $database = new Database($servername, $username, $password, $dbname);
    $conn = $database->getConnection();

    // Fetch tutor details
    $stmt = $conn->prepare("SELECT * FROM tutors WHERE TutorId = ?");
    $stmt->bind_param("i", $tutorId);
    $stmt->execute();
    $result = $stmt->get_result();
    $tutorDetails = $result->fetch_assoc();

    // Create a Tutor object with fetched details
    $tutor = new Tutor($tutorDetails['FirstName'], $tutorDetails['LastName'], $tutorId);

    // Close the statement
    $stmt->close();
    ?>

    <h1>Available Sessions for Tutor: <?php echo $tutor->getFirstName(); ?></h1>

    <?php
    // Fetch available sessions for the tutor
    $stmt = $conn->prepare("SELECT AvailableDate, StartTime, EndTime 
                            FROM tutor_availability 
                            WHERE TutorId = ?
                            ORDER BY AvailableDate, StartTime;");
    $stmt->bind_param("i", $tutorId);
    $stmt->execute();
    $result = $stmt->get_result();
    $timeSlots = [];

    // Format session details
    while ($row = $result->fetch_assoc()) {
        $availableDate = new DateTime($row['AvailableDate']);
        $startTime = new DateTime($row['StartTime']);
        $endTime = new DateTime($row['EndTime']);

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

    <?php if (count($timeSlots) > 0): ?>
        <ul>
            <?php foreach ($timeSlots as $slot): ?>
                <li>
                    <?php echo $slot['AvailableDate'] . " from " . $slot['StartTime'] . " to " . $slot['EndTime']; ?>
                    <!-- Link to requestSession.php with session details -->
                    <a href="RequestSessionForm.php?tutorId=<?php echo urlencode($tutorId); ?>&date=<?php echo urlencode($slot['AvailableDate']); ?>&startTime=<?php echo urlencode($slot['StartTime']); ?>&endTime=<?php echo urlencode($slot['EndTime']); ?>">Request Session</a>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>No available sessions.</p>
    <?php endif; ?>
</body>
</html>
