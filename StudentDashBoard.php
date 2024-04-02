<?php
session_start();
include 'Database.php';
include 'Calendar.php';
// Assuming Database.php includes logic to establish database connection
$database = new Database($servername, $username, $password, $dbname);
$conn = $database->getConnection();

// Ensure the user ID is properly set in the session
$userid = isset($_SESSION['id']) ? $_SESSION['id'] : null;
if (!$userid) {
    // Redirect to login page or show an error if the user ID isn't set
    header("Location: Login.php");
    exit;
}

// Fetch user details
$userDetailsStmt = $conn->prepare("CALL GetUserDetails(?)");
$userDetailsStmt->bind_param("i", $userid);
$userDetailsStmt->execute();
$userDetailsResult = $userDetailsStmt->get_result();
$userDetails = $userDetailsResult->fetch_assoc();
$userDetailsStmt->close();

// Fetch student ID 
$studentIdStmt = $conn->prepare("CALL GetStudentId(?)");
$studentIdStmt->bind_param("i", $userid);
$studentIdStmt->execute();
$studentIdResult = $studentIdStmt->get_result();
$studentId = $studentIdResult->fetch_assoc();
$studentIdStmt->close();

//
$_SESSION['studentId'] = $studentId['StudentId'];
$studentgetid = $_SESSION['studentId'];
//WE NEED TO CALL GetUpcomingSessions(?) WITH THE NEXT CODE
// Fetch upcoming sessions
$stmt = $conn->prepare("CALL GetUpcomingSessions(?)");
$stmt->bind_param("i", $studentgetid);
$stmt->execute();
$sessionsResult = $stmt->get_result();
$stmt->close();
//stores the student id in a session
$_SESSION['userid'] = $userid;

//create calendar object
$calendar = new SimpleCalendar();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard</title>
    <link rel="stylesheet" href="styles.css">
    <link href="Calendar.css" rel="stylesheet" type="text/css">
</head>
<body>
    <?php include 'Includes/StudentHeader.php'; ?>

    <section>
        <h2>Welcome to the Student Dashboard</h2>
        <!-- Users Info -->
        <p>First Name: <?php echo htmlspecialchars($userDetails['FirstName']); ?></p>
        <p>Last Name: <?php echo htmlspecialchars($userDetails['LastName']); ?></p>
        <p>Email: <?php echo htmlspecialchars($userDetails['Email']); ?></p>
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
    
                        Course Name: <?php echo htmlspecialchars($session['CourseName']); ?><br>
                        Course: <?php echo htmlspecialchars($session['CourseId']); ?><br>
                        Date: <?php echo htmlspecialchars($session['DateAndTime']); ?><br>
                        Start Time: <?php echo date("g:i A", strtotime($session['StartTime'])); ?><br>
                        <a href="ViewSession.php?sessionId=<?php echo $session['SessionId']; ?>&tutorId=<?php echo $session['TutorId']; ?>">View Session</a>
                    </li>
                    
                <?php endwhile; ?>
            </ul>
        <?php else: ?>
            <p>No upcoming sessions.</p>
        <?php endif; ?>
    </section>
    
</body>


<?php
    //render the calendar
        echo $calendar->render();
    ?>
</html>
