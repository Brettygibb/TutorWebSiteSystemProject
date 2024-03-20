<?php
session_start();
include '../Database.php';

$db = new Database($servername, $username, $password, $dbname);

$conn = $db->getConnection();
// Check if the user is logged in as a tutor
if (!isset($_SESSION['tutorId'])) {
    die("You are not logged in.");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $tutorId = $_SESSION['tutorId'];
    $availableDate = $_POST["availableDate"]; 
    $startTime = $_POST["startTime"]; 
   // $endTime = $_POST["endTime"]; 

    $availableDateTime = new DateTime($availableDate . " " . $startTime);
    $currentTime = new DateTime();
    if($availableDateTime < $currentTime){
        header("Location: ../TutorAvailability.php?error=past");
        exit();
    }

   $endDateTime = clone $availableDateTime;
   $endDateTime->add(new DateInterval('PT1H'));
    $endTime = $endDateTime->format('H:i:s');



   
    //need a stored procedure to check if the tutor is already available at this time
    $checkStmt = $conn->prepare("SELECT * FROM tutor_availability WHERE TutorId = ? AND AvailableDate = ? AND ((StartTime <= ? AND EndTime > ?) OR (StartTime < ? AND EndTime >= ?))");
    $checkStmt->bind_param("isssss", $tutorId, $availableDate, $startTimeFormatted, $startTimeFormatted, $endTimeFormatted, $endTimeFormatted);
    $checkStmt->execute();
    $checkResult = $checkStmt->get_result();
    if($checkResult->num_rows > 0) {
        header("Location: ../TutorAvailability.php?error=overlap");
    }else{

        //need a stored procedure to insert the availability
        $stmt = $conn->prepare("INSERT INTO tutor_availability (TutorId, AvailableDate, StartTime, EndTime) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("isss", $tutorId, $availableDate, $startTime, $endTime);

        if ($stmt->execute()) {
            header("Location: ../TutorAvailability.php?success=true");
        } else {
            echo "Error: " . $conn->error;
            header("Location: ../TutorAvailability.php?success=false");
        }
        
        $stmt->close();
    }
    $checkStmt->close();
}

 else {
    echo "Invalid request method.";
    header("Location: ../TutorAvailability.php");
}
?>
