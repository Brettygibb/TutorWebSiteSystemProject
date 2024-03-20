<?php
session_start();
include '../Database.php';

$db = new Database($servername, $username, $password, $dbname);

$conn = $db->getConnection();
$studentId = $_SESSION['studentId'];
//i left off here march 1 2024
$stmt = $conn->prepare("SELECT StudentId FROM students WHERE StudentId = ?");
$stmt->bind_param("i", $studentId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    // StudentId does not exist in the students table
    echo "Error: StudentId does not exist.";
    exit;
} else {

    $tutorId =$_POST['tutorId'];
    $courseId =$_POST['courseId'];
    $date = date('Y-m-d', strtotime($_POST['date']));
    $startTime = date('H:i:s', strtotime($_POST['startTime']));
    $endTime = date('H:i:s', strtotime($_POST['endTime']));
    $message = $_POST['message'];
    $status = "Pending";
    
    $sql = "INSERT INTO session_request (tutorId, studentId, courseId, requestdate, starttime, endtime, message, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iiisssss", $tutorId, $studentId, $courseId, $date, $startTime, $endTime, $message, $status);

    if ($stmt->execute()) {
        // Fetch the userId of the tutor associated with the request
        $userIdSql = "SELECT UserId FROM tutors WHERE TutorId = ?";
        $stmt = $conn->prepare($userIdSql);
        $stmt->bind_param("i", $tutorId); 
        $stmt->execute();
        $stmt->bind_result($userId);
        $stmt->fetch();
        $stmt->close();

        $message = "New session request received. Please review.";
        
        // Notification insertion query
        $sqlNotification = "INSERT INTO notifications (user_id, message) VALUES (?, ?)";
        $stmtNotification = $conn->prepare($sqlNotification);
        $stmtNotification->bind_param("is", $userId, $message); // Bind userId and message to the statement
        
        if ($stmtNotification->execute()) {
            header("Location: ../StudentDashBoard.php?success=true");
        } else {
            echo "Error: " . $conn->error;
            header("Location: ../StudentDashBoard.php?success=false");
        }
        $stmtNotification->close();
    } else {
        echo "Error: " . $conn->error;
        header("Location: ../StudentDashBoard.php?success=false");
    }
    
    $stmt->close();
}




?>

