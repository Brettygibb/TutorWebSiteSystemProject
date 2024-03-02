<?php
session_start();
//include 'Connect.php';

include 'Database.php';

//Create a new instance of DB class 
$database= new Database($servername, $username, $password, $dbname);

//Get the database connection 
$conn= $database ->getConnection();

if(isset($_SESSION['id'])) {
    $userId = $_SESSION['id'];

    // Check if the user exists in the students table
    $sql = "SELECT * FROM students WHERE UserId = ?";
    $stmt = $conn->prepare($sql);
    if(!$stmt){
        echo "Error: ".$conn->error;
        exit();
    }
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if($result && $result->num_rows > 0) {
        // User exists in the students table, redirect to StudentDashboard.php
        header("Location: StudentDashboard.php");
        exit();
    } else {
        // User doesn't exist in the students table, display message and redirect to TutorDashboard.php
        echo "You do not have permissions to switch as a student.";
        header("refresh:3; url=TutorDashboard.php");
        exit();
    }
    $stmt->close();
    $conn->close();
} else {
    echo "User is not logged in.";
}
?>
