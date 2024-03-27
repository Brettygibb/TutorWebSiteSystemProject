<?php
session_start();
include 'Database.php';
$db = new Database($servername, $username, $password, $dbname);
$conn = $db->getConnection();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Subscribed Courses</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <?php include 'Includes/TutorHeader.php'; ?>
    <h2>Subscribed Courses</h2>
    <?php

    $userid = $_SESSION['id'];

    $subscribedCoursesResult = null;

    $stmt =$conn->prepare("CALL GetTutorId(?)");
    $stmt->bind_param("i", $userid);
    $stmt->execute();
    $result = $stmt->get_result();
    if($row = $result->fetch_assoc()){
        $tutorId = $row['TutorId'];
        $stmt->free_result();
        $stmt->close();


        $stmt = $conn->prepare("CALL GetSubscribedCourses(?)");
        $stmt->bind_param("i", $tutorId);
        $stmt->execute();
        $result_courses = $stmt->get_result();
    }
    else{
        echo "No tutor found for user ID: ".$userid;
        exit();
    }
    ?>
    <?php if ($result_courses && $result_courses->num_rows > 0): ?>
        <ul>
            <?php while ($course = $result_courses->fetch_assoc()): ?>
                <li><?php echo htmlspecialchars($course['CourseName']); ?></li>
            <?php endwhile; ?>
        </ul>
    <?php else: ?>
        <p>You have not subscribed to any courses.</p>
    <?php endif; ?>
</body>
</html>
