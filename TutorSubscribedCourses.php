<?php
session_start();
//include 'Connect.php';

include 'Database.php';

//Create a new instance of DB class 
$database= new Database($servername, $username, $password, $dbname);

//Get the database connection 
$conn= $database ->getConnection();

$userid = $_SESSION['id'];
// Get tutor ID based on the user ID
$sql_tutor_id = "SELECT TutorId FROM tutors WHERE UserId = $userid";
$result_tutor_id = mysqli_query($conn, $sql_tutor_id);
$row_tutor_id = mysqli_fetch_assoc($result_tutor_id);
$tutor_id = $row_tutor_id['TutorId'];

// Get list of courses taught by the tutor
$sql_courses = "SELECT c.CourseName
                FROM tutor_courses tc
                INNER JOIN courses c ON tc.CourseId = c.CourseId
                WHERE tc.TutorId = $tutor_id";
$result_courses = mysqli_query($conn, $sql_courses);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subscribed Courses</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <?php include 'Includes/TutorHeader.php'; ?>
    <section>
        <h2>Subscribed Courses</h2>
        <?php if(mysqli_num_rows($result_courses) > 0): ?>
            <ul>
                <?php while($row_courses = mysqli_fetch_assoc($result_courses)): ?>
                    <li><?php echo $row_courses['CourseName']; ?></li>
                <?php endwhile; ?>
            </ul>
        <?php else: ?>
            <p>No courses subscribed yet.</p>
        <?php endif; ?>
    </section>
</body>
</html>
