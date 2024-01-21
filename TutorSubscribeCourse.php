<?php
session_start();
include 'Connect.php';

// Check if the user is logged in as a tutor
if ($_SESSION['role'] !== 'Tutor' || !isset($_SESSION['id'])) {
    header("Location: Login.php");
    exit();
}

$userId = $_SESSION['id'];

// Query to fetch available courses for subscription excluding already tutored courses
$sql = "SELECT CourseId, CourseName FROM courses
        WHERE CourseId NOT IN (
            SELECT CourseId FROM tutors WHERE UserId = ?
        )";

$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $userId);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tutor Subscribe Course</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h1>Tutor Subscribe Course</h1>
        <nav>
            <ul>
                <li><a href="TutorDashBoard.php">Dashboard</a></li>
                <li><a href="#">Subscribe Course</a></li>
                <li><a href="#">Upcoming Sessions</a></li>
                <li><a href="Logout.php">Logout</a></li>
                <li><a href="TutorEditProfile.php">Edit Profile</a></li>
            </ul>
        </nav>
    </header>

    <section>
        <h2>Available Courses for Subscription</h2>
        <form action="SubscribeCourseProc.php" method="post">
            <table>
                <thead>
                    <tr>
                        <th>Course Name</th>
                        <th>Select</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>
                                <td>{$row['CourseName']}</td>
                                <td><label><input type='checkbox' name='courses[]' value='{$row['CourseId']}'> </label></td>
                              </tr>";
                    }
                    ?>
                </tbody>
            </table>
            <button type="submit">Subscribe Selected Courses</button>
        </form>
    </section>
</body>
</html>
