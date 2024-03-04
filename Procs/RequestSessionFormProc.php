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
    $date = $_POST['date'];
    $startTime = $_POST['startTime'];
    $endTime = $_POST['endTime'];
    $message = $_POST['message'];
    $status = "Pending";
    
    $sql = "INSERT INTO session_request (tutorId, studentId, requestdate, starttime, endtime, message, status) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iisssss", $tutorId, $studentId, $date, $startTime, $endTime, $message, $status);
    
    if ($stmt->execute()) {
        header("Location: ../RequestSession.php?success=true");
    } else {
        echo "Error: " . $conn->error;
        header("Location: ../RequestSession.php?success=false");
    }
    
    $stmt->close();
}




?>

