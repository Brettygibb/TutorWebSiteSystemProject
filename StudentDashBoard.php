<?php
session_start();
include 'Database.php';

// Assuming Database.php includes logic to establish database connection
$database = new Database($servername, $username, $password, $dbname);
$conn = $database->getConnection();

// Ensure the user ID is properly set in the session
$userid = isset($_SESSION['id']) ? $_SESSION['id'] : null;
if (!$userid) {
    // Redirect to login page or show an error if the user ID isn't set
    header("Location: login.php");
    exit;
}

// Fetch user details
$stmt = $conn->prepare("SELECT * FROM users WHERE UserID = ?");
$stmt->bind_param("i", $userid);
$stmt->execute();
$result = $stmt->get_result();
$userDetails = $result->fetch_assoc();
//get student id
$student = $conn->prepare("SELECT StudentId FROM students WHERE UserID = ?");
$student->bind_param("i", $userid);
$student->execute();
$studentId = $student->get_result();
$studentId = $studentId->fetch_assoc();

$_SESSION['studentId'] = $studentId['StudentId'];
$studentgetid = $_SESSION['studentId'];
// Fetch upcoming sessions
$stmt = $conn->prepare("SELECT * FROM sessions WHERE StudentId = ?");
$stmt->bind_param("i", $studentgetid);
$stmt->execute();
$sessionsResult = $stmt->get_result();
//stores the student id in a session
$_SESSION['userid'] = $userid;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard</title>
    <link rel="stylesheet" href="styles.css">
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
                <?php while ($session = $sessionsResult->fetch_assoc()): ?>
                    <li>
                        Course: <?php echo htmlspecialchars($session['CourseId']); ?><br>
                        Date: <?php echo htmlspecialchars($session['DateAndTime']); ?><br>
                        Start Time: <?php echo date("g:i A", strtotime($session['StartTime'])); ?><br>
                        <a href="ViewSession.php?sessionId=<?php echo $session['SessionId']; ?>">View Session</a>
                    </li>
                <?php endwhile; ?>
            </ul>
        <?php else: ?>
            <p>No upcoming sessions.</p>
        <?php endif; ?>
    </section>
</body>
</html>
