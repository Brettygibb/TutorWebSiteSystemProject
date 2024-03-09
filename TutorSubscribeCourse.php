<?php
session_start();
include 'Database.php';
$db = new Database($servername, $username, $password, $dbname);
$conn = $db->getConnection();
$userid = $_SESSION['id'];

// Query to get the tutor ID based on the user ID
//need a stored procedure to get the tutor id
$tutorIdSql = "SELECT TutorId FROM tutors WHERE UserId = ?";
$stmt = $conn->prepare($tutorIdSql);
$stmt->bind_param("i", $userid);
$stmt->execute();
$tutorIdResult = $stmt->get_result();
$tutorIdRow = $tutorIdResult->fetch_assoc();



if (!$tutorIdRow) {
    // Handle the case where the tutor ID is not found
    echo "Error: Tutor ID not found for the user.";
    exit();
}

$tutorid = $tutorIdRow['TutorId'];

// Query to get the user information
//need a stored procedure to get the user info
$userSql = "SELECT * FROM users WHERE UserID = ?";
$stmt = $conn->prepare($userSql);
$stmt->bind_param("i", $userid);
$stmt->execute();
$userResult = $stmt->get_result();
$userRow = $userResult->fetch_assoc();

// Query to get available courses for the tutor to subscribe
//need a stored procedure to get the available courses
$availableCoursesSql = "SELECT * FROM courses WHERE CourseId NOT IN (SELECT CourseId FROM tutor_courses WHERE TutorId = ?)";
$stmt = $conn->prepare($availableCoursesSql);
$stmt->bind_param("i", $tutorid);
$stmt->execute();
$availableCoursesResult = $stmt->get_result();

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $selectedCourseId = $_POST["courseId"];

    // Redirect to TutorSubscribeProc.php with selected course ID
    header("Location: TutorSubscribeCourseProc.php?courseId=$selectedCourseId&tutorId=$tutorid");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tutor Subscribe Courses</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <?php include 'Includes/TutorHeader.php'; ?>
    <section>
        <h2>Welcome to the Tutor Subscribe Courses Page</h2>
        <!-- User Info -->
        <p>First Name: <?php echo htmlspecialchars($userRow['FirstName']); ?></p>
        <p>Last Name: <?php echo htmlspecialchars($userRow['LastName']); ?></p>
        <p>Email: <?php echo htmlspecialchars($userRow['Email']); ?></p>
        
        <?php if (!empty($userRow['image'])): ?>
            <img src="<?php echo htmlspecialchars($userRow['image']); ?>" alt="Profile Picture">
        <?php endif; ?>

        <!-- Display available courses -->
        <h3>Available Courses for Subscription</h3>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <label for="courseId">Select a Course:</label>
            <select name="courseId" id="courseId">
                <?php while ($courseRow = $availableCoursesResult->fetch_assoc()): ?>
                    <option value="<?php echo $courseRow['CourseId']; ?>"><?php echo htmlspecialchars($courseRow['CourseName']); ?></option>
                <?php endwhile; ?>
            </select>
            <br>
            <input type="submit" value="Subscribe">
        </form>
    </section>
</body>
</html>
