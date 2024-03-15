<?php
session_start();
//include 'Connect.php';

include 'Database.php';

//Create a new instance of DB class 
$database = new Database($servername, $username, $password, $dbname);

//Get the database connection 
$conn = $database->getConnection();

$userId = $_SESSION['id'];

// Check if the user exists in the tutors table
$sql = "CALL switchToTutor(?)";

// Prepare the statement
$stmt = $conn->prepare($sql);

// Bind the parameter
$stmt->bind_param("i", $userId);

// Execute the statement
$stmt->execute();

// Get the result
$result = $stmt->get_result();

if (mysqli_num_rows($result) > 0) {
    // User is already a tutor, redirect to TutorDashboard.php
    header("Location: TutorDashboard.php");
    exit();
} else {
    // User is not a tutor, display message and redirect to StudentDashboard.php
    echo "You do not have permissions to become a tutor.";
    header("refresh:3; url=StudentDashboard.php");
    exit();
}
?>
