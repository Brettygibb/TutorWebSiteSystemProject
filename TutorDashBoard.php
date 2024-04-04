<?php
session_start();
include 'Database.php';
include 'Calendar.php';

$db = new Database($servername, $username, $password, $dbname);
$calendar = new SimpleCalendar();
$conn = $db->getConnection();

// Ensure the user ID is properly set in the session
$userid = isset($_SESSION['id']) ? $_SESSION['id'] : null;
if (!$userid) {
    // Redirect to login page or show an error if the user ID isn't set
    header("Location: Login.php");
    exit;
}

// Fetch user details
$stmt = $conn->prepare("CALL GetUserByUserId(?)");
$stmt->bind_param("i", $userid);
$stmt->execute();
$result = $stmt->get_result();
$userDetails = $result->fetch_assoc();
$stmt->close();

// Fetch tutor ID
$tutorStmt = $conn->prepare("CALL GetTutorId(?)");
$tutorStmt->bind_param("i", $userid);
$tutorStmt->execute();
$tutorResult = $tutorStmt->get_result();
if ($tutorRow = $tutorResult->fetch_assoc()) {
    // Correctly fetching and storing the TutorId in the session
    $_SESSION['tutorId'] = $tutorRow['TutorId'];
} else {
    echo "Tutor ID not found for user.";
    exit; // Or handle this scenario appropriately
}
$tutorStmt->close();

$tutorgetid = $_SESSION['tutorId'];
//WE NEED TO CALL GetUpcomingSessions(?) WITH THE NEXT CODE
// Fetch upcoming sessions
$Calstmt = $conn->prepare("CALL GetTutorsSessions(?)");
$Calstmt->bind_param("i", $tutorgetid);
$Calstmt->execute();
$sessionsResult = $Calstmt->get_result();
$Calstmt->close();


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tutor Dashboard</title>
    <link rel="stylesheet" href="styles.css">
    <link href="Calendar.css" rel="stylesheet" type="text/css"> 
</head>
<body>
    <?php include 'Includes/TutorHeader.php'; ?>

    <section>
        <h2>Welcome to the Tutor Dashboard</h2>
        <!-- Users Info -->
        <p>First Name: <?php echo htmlspecialchars($userDetails['FirstName']); ?></p>
        <p>Last Name: <?php echo htmlspecialchars($userDetails['LastName']); ?></p>
        <p>Email: <?php echo htmlspecialchars($userDetails['Email']); ?></p>
        <p>Tutor ID: <?php echo htmlspecialchars($_SESSION['tutorId']); ?></p> <!-- Displaying TutorId -->
        <?php if (!empty($userDetails['image'])): ?>
            <img src="<?php echo htmlspecialchars($userDetails['image']); ?>" alt="Profile Picture">
        <?php endif; ?>
        
    </section>

    <section>
        <h2>Upcoming Sessions</h2>
        <?php if ($sessionsResult->num_rows > 0): ?>
            <ul>
                <?php while ($session = $sessionsResult->fetch_assoc()):?>
                    <br>
                    <li>
                        <!-- Add session to calendar -->
                        <?php  $calendar->addSession($session['CourseName'], $session['DateAndTime'], $session['SessionId'], $session['TutorId'], "blue"); ?>
                    </li>
                    
                <?php endwhile; ?>
            </ul>
        <?php else: ?>
            <p>No upcoming sessions.</p>
        <?php endif; ?>
    </section>
</body>
</html>

<?php
    //render the calendar
        echo $calendar->render();
    ?>