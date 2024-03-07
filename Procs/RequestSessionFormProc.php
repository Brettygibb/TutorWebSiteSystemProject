<?php
session_start();
include '../Database.php';

$db = new Database($servername, $username, $password, $dbname);

$conn = $db->getConnection();
$studentId = $_SESSION['studentId'];
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
    $firstName = $_POST['FirstName'];
    $lastName = $_POST['LastName'];
    $email = $_POST['email'];
    $course = $_POST['Course'];
    $date = date('Y-m-d', strtotime($_POST['date']));
    $startTime = date('H:i:s', strtotime($_POST['startTime']));
    $endTime = date('H:i:s', strtotime($_POST['endTime']));
    $message = $_POST['message'];
    $status = "Pending";
    
    $sql = "INSERT INTO session_request (tutorId, studentId,FirstName,LastName,Email,Course, requestdate, starttime, endtime, message, status) VALUES (?, ?, ?, ?, ?, ?, ?,?,?,?,?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iisssssssss", $tutorId, $studentId, $firstName, $lastName, $email, $course, $date, $startTime, $endTime, $message, $status);
   
    
    if ($stmt->execute()) {
        header("Location: ../StudentDashBoard.php?success=true");
    } else {
        echo "Error: " . $conn->error;
        header("Location: ../StudentDashBoard.php?success=false");
    }
    
    $stmt->close();
}




?>

