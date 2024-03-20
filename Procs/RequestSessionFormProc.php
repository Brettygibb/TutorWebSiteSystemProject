<?php
session_start();
include '../Database.php';
include '../Classes/Sessions.php';

$db = new Database($servername, $username, $password, $dbname);
$conn = $db->getConnection();



// Assume other variables like $tutorId, $date, etc., are retrieved similarly
$tutorId = $_POST['tutorId'] ?? '';
$date = $_POST['date'] ?? '';
$startTime = $_POST['startTime'] ?? '';
$endTime = $_POST['endTime'] ?? '';
$courseId = $_POST['courseId'] ?? 0; // Default to 0 if not set


$studentId = $_SESSION['studentId'] ?? ''; // Assuming the student ID is stored in the session

// Other data might be retrieved from a form submission, for example
$firstName = $_POST['FirstName'] ?? '';
$lastName = $_POST['LastName'] ?? '';
$email = $_POST['email'] ?? '';
$message = $_POST['message'] ?? '';
$status = 'Pending'; // Default status for a new session request
$subject = $_POST['Subject'] ?? '';

$session = new Sessions($conn);
if ($session->createSessionRequest($tutorId, $studentId, $courseId, $firstName, $lastName, $email,$subject, $date, $startTime, $endTime, $message, $status)) {
    header("Location: ../StudentDashboard.php?success=true");
    exit();
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
