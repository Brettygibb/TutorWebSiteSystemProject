<?php
session_start();
include '../Database.php';
include '../Classes/Sessions.php';

$db = new Database($servername, $username, $password, $dbname);
$conn = $db->getConnection();
$studentId = $_SESSION['studentId']??null;

if($studentId){
    $tutorId = $_POST['tutorId'];
    $firstName = $_POST['FirstName'];
    $lastName = $_POST['LastName'];
    $email = $_POST['email'];
    $course = $_POST['Course'];
    $date = date('Y-m-d', strtotime($_POST['date']));
    $startTime = date('H:i:s', strtotime($_POST['startTime']));
    $endTime = date('H:i:s', strtotime($_POST['endTime']));
    $message = $_POST['message'];
    $status = "Pending";

    $session = new Sessions($conn);

    if($session->createSessionRequest($tutorId, $studentId, $firstName, $lastName, $email, $course, $date, $startTime, $endTime, $message, $status)){
        header("Location: ../StudentDashboard.php?success=true");
    } else {
        header("Location: ../StudentDashboard.php?success=false");
    }
}
else{
    echo "Please login to request a session";
    exit();
}





?>

