<?php
session_start();
include 'Database.php';

$db = new Database($servername, $username, $password, $dbname);
$conn = $db->getConnection();

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

// Fetch tutor ID
$tutorStmt = $conn->prepare("SELECT TutorId FROM tutors WHERE UserId = ?");
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

// Fetch tutor profile details
$profileStmt = $conn->prepare("SELECT * FROM users_profiles WHERE UserId = ?");
$profileStmt->bind_param("i", $_SESSION['id']);
$profileStmt->execute();
$profileResult = $profileStmt->get_result();
$profileDetails = $profileResult->fetch_assoc();
$profileStmt->close();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tutor Dashboard</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <?php include 'Includes/TutorHeader.php'; ?>

    <section>
        <h2>Welcome to the Tutor Dashboard</h2>
        <!-- Users Info -->
        <p>First Name: <?php echo htmlspecialchars($userDetails['FirstName']); ?></p>
        <p>Last Name: <?php echo htmlspecialchars($userDetails['LastName']); ?></p>
        <p>Email: <?php echo htmlspecialchars($userDetails['Email']); ?></p>
        <p>Tutor ID: <?php echo htmlspecialchars($_SESSION['tutorId']); ?></p>
        <p>Academic Background: <?php echo htmlspecialchars($profileDetails['academicBackground']); ?></p>
        <p>Expertise: <?php echo htmlspecialchars($profileDetails['expertise']); ?></p>
        <p>Achievements: <?php echo htmlspecialchars($profileDetails['achievements']); ?></p>
        <p>Bio: <?php echo htmlspecialchars($profileDetails['bio']); ?></p>
        <?php if (!empty($userDetails['image'])): ?>
            <img src="<?php echo htmlspecialchars($userDetails['image']); ?>" alt="Profile Picture">
        <?php endif; ?>
    </section>
    <div id="UpcomingSessions">
        <h2>Upcoming Sessions</h2>
        <table>
            <tr>
                <th>Student</th>
                <th>Date</th>
                <th>Start Time</th>
                <th>Course</th>
                <th>Message</th>
                <th>Status</th>
            </tr>

            <?php
            $stmt = $conn->prepare("SELECT 
            u.FirstName AS StudentFirstName,
            u.LastName AS StudentLastName,
            s.DateAndTime AS SessionDate,
            s.StartTime AS SessionTime,
            c.CourseName,
            s.Notes AS Message,
            s.Status,
            s.SessionId
        FROM sessions s
        JOIN students st ON s.StudentId = st.StudentId
        JOIN users u ON st.UserId = u.UserId
        JOIN courses c ON s.CourseId = c.CourseId
        WHERE s.status = 'Scheduled';
        ");

            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['StudentFirstName'] . ' ' . $row['StudentLastName']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['SessionDate']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['SessionTime']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['CourseName']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['Message']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['Status']) . "</td>";
                    //button row
                    //add sessionid,tutorid,and action to the url
                    echo "<td>";
                    echo "<form action='Procs/CompleteSessionProc.php' method='post'>";
                    echo "<input type='hidden' name='sessionId' value='" . $row['SessionId'] . "'>";
                    echo "<input type='hidden' name='tutorId' value='" . $_SESSION['tutorId'] . "'>";
                    echo "<input type='submit' name='complete' value='Complete'>";
                    echo "</form>";
                    echo "</td>";
                    echo "</tr>";
                    
                    
                }
            } else {
                echo "<tr><td colspan='6'>No upcoming sessions</td></tr>";
            }
            ?>
        </table>
    </div>
</body>
</html>
