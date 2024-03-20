<?php
session_start();
//include 'Connect.php';
include 'Database.php';
include 'Admin.php';

//Create a new instance of DB class 
$database= new Database($servername, $username, $password, $dbname);

//Get the database connection 
$conn= $database ->getConnection();

// Create a new instance of the Admin class
$admin = new Admin();

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && isset($_POST['student_id'])) {
    $action = $_POST['action'];
    $studentId = $_POST['student_id'];

    // Call the processBecomeTutor method of the Admin class
    $admin->processBecomeTutor($action, $studentId, $conn);
} else {
    echo "Invalid request!";
    exit();
}
?>