<?php
session_start();
include 'Connect.php';

$userid = $_SESSION['id'];

// Query to get the user information
$userSql = "SELECT * FROM users WHERE UserID = $userid";
$userResult = mysqli_query($conn, $userSql);
$userRow = mysqli_fetch_assoc($userResult);

// Query to get available courses for the tutor to subscribe
$availableCoursesSql = "
    SELECT *
    FROM courses
    WHERE CourseId NOT IN (
        SELECT CourseId FROM tutor_courses WHERE TutorId = $userid
    ) OR NOT EXISTS (
        SELECT 1 FROM tutor_courses WHERE TutorId = $userid
    );
";

$availableCoursesResult = mysqli_query($conn, $availableCoursesSql);

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $selectedCourseId = $_POST["courseId"];

    // Redirect to TutorSubscribeProc.php with selected course ID
    header("Location: TutorSubscribeProc.php?courseId=$selectedCourseId");
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
    <header>
        <h1>Tutor Subscribe Courses</h1>
        <nav>
            <ul>
                <li><a href="TutorDashboard.php">Home</a></li>
                <li><a href="#">Subscribe Course</a></li>
                <li><a href="#">Upcoming Sessions</a></li>
                <li><a href="#">Logout</a></li>
                <li><a href="StudentEditProfile.php">Edit Profile</a></li>
            </ul>
        </nav>
    </header>

    <section>
        <h2>Welcome to the Tutor Subscribe Courses Page</h2>
        <!-- Users Info -->
        <p>First Name: <?php echo $userRow['FirstName']; ?></p>
        <p>Last Name: <?php echo $userRow['LastName']; ?></p>
        <p>Email: <?php echo $userRow['Email']; ?></p>
        
        <?php if (!empty($userRow['image'])): ?>
            <img src="<?php echo $userRow['image']; ?>" alt="Profile Picture">
        <?php endif; ?>

        <!-- Display available courses -->
        <h3>Available Courses for Subscription</h3>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <label for="courseId">Select a Course:</label>
            <select name="courseId" id="courseId">
                <?php
                // Reset the pointer of the result set
                mysqli_data_seek($availableCoursesResult, 0);

                while ($courseRow = mysqli_fetch_assoc($availableCoursesResult)): ?>
                    <option value="<?php echo $courseRow['CourseId']; ?>"><?php echo $courseRow['CourseName']; ?></option>
                <?php endwhile; ?>
            </select>
            <br>
            <input type="submit" value="Subscribe">
        </form>
    </section>
</body>
</html>
