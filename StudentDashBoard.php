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
if(isset($_GET['success'])){
    if($_GET['success'] == "true"){
        echo "<script>alert('Request Sent Successfully');</script>";
    }else{
        echo "<script>alert('Request Failed');</script>";
    }
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
$sql = "SELECT 
s.SessionId,
t.TutorId,
u.FirstName AS TutorFirstName,
u.LastName AS TutorLastName,
c.CourseId,
c.CourseName,
s.DateAndTime,
s.StartTime
FROM 
sessions s
INNER JOIN 
tutors t ON s.TutorId = t.TutorId
INNER JOIN 
users u ON t.UserId = u.UserId
INNER JOIN 
courses c ON s.CourseId = c.CourseId
WHERE 
s.DateAndTime >= CURDATE()
ORDER BY 
s.DateAndTime ASC, s.StartTime ASC;
";

$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    $sessionsResult = $result; // Use $sessionsResult in your HTML/PHP output
} else {
    $sessionsResult = null;
    echo "<p>No upcoming sessions.</p>";
}

//stores the student id in a session
$_SESSION['userid'] = $userid;
//get pending session requests for the student
$stmt = $conn->prepare("SELECT 
sr.RequestId, 
sr.TutorId, 
sr.StudentId, 
sr.RequestDate, 
sr.StartTime, 
sr.EndTime, 
sr.Message, 
sr.Status, 
u.FirstName AS TutorFirstName, 
u.LastName AS TutorLastName, 
u.Email AS TutorEmail
FROM 
session_request AS sr
JOIN 
tutors AS t ON sr.TutorId = t.TutorId
JOIN 
users AS u ON t.UserId = u.UserId
WHERE 
sr.Status = 'Pending';");
$stmt->execute();
$pendingRequests = $stmt->get_result();

$completedSessionsSql = "SELECT 
    s.SessionId,
    t.TutorId,
    u.FirstName AS TutorFirstName,
    u.LastName AS TutorLastName,
    c.CourseId,
    c.CourseName,
    s.DateAndTime,
    s.StartTime
FROM 
    sessions s
INNER JOIN 
    tutors t ON s.TutorId = t.TutorId
INNER JOIN 
    users u ON t.UserId = u.UserId
INNER JOIN 
    courses c ON s.CourseId = c.CourseId
WHERE 
    s.Status = 'Completed'
AND 
    s.StudentId = ?
ORDER BY 
    s.DateAndTime DESC, s.StartTime DESC;
";

$stmt = $conn->prepare($completedSessionsSql);
$stmt->bind_param("i", $studentgetid); // Assuming $studentgetid holds the logged-in student's ID
$stmt->execute();
$completedSessionsResult = $stmt->get_result();

echo $studentgetid;




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
        <h2>Pending Session Requests</h2>
        <?php if ($pendingRequests->num_rows > 0): ?>
            <ul>
                <?php while ($request = $pendingRequests->fetch_assoc()): ?>
                    <li>
                        Tutor: <?php echo htmlspecialchars($request['TutorFirstName'] . ' ' . $request['TutorLastName']); ?><br>
                        Date: <?php echo htmlspecialchars($request['RequestDate']); ?><br>
                        Start Time: <?php echo date("g:i A", strtotime($request['StartTime'])); ?><br>
                        Status: <?php echo htmlspecialchars($request['Status']); ?><br>
                    </li>
                <?php endwhile; ?>
            </ul>
        <?php else: ?>
            <p>No pending session requests.</p>
        <?php endif; ?>

    <section>
        <h2>Upcoming Sessions</h2>
        <?php if ($sessionsResult->num_rows > 0): ?>
            <ul>
                <?php while ($session = $sessionsResult->fetch_assoc()): ?>
                    <li>
                        Tutor: <?php echo htmlspecialchars($session['TutorId']); ?><br>
                        Tutors Name: <?php echo htmlspecialchars($session['TutorFirstName'] . ' ' . $session['TutorLastName']); ?><br>
                        Course: <?php echo htmlspecialchars($session['CourseId']); ?><br>
                        Course Name: <?php echo htmlspecialchars($session['CourseName']); ?><br>
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
    <section>
    <h2>Completed Sessions</h2>
    <?php if ($completedSessionsResult->num_rows > 0): ?>
        <ul>
            <?php while ($sessions = $completedSessionsResult->fetch_assoc()): ?>
                <li>

                    Tutor: <?php echo htmlspecialchars($sessions['TutorFirstName'] . ' ' . $sessions['TutorLastName']); ?><br>
                    Course: <?php echo htmlspecialchars($sessions['CourseName']); ?><br>
                    Date: <?php echo htmlspecialchars($sessions['DateAndTime']); ?><br>
                    Start Time: <?php echo date("g:i A", strtotime($sessions['StartTime'])); ?><br>
                    <a href="LeaveReview.php?sessionId=<?php echo $sessions['SessionId']; ?>&tutorId=<?php echo $sessions['TutorId']; ?>">Leave a Review</a>
                </li>
            <?php endwhile; ?>
        </ul>
    <?php else: ?>
        <p>No completed sessions.</p>
    <?php endif; ?>
</section>

</body>
</html>
