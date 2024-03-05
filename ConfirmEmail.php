<?php

include 'Database.php';

$db = new Database($servername, $username, $password, $dbname);

$conn = $db->getConnection();
require_once 'Email_Confirmation.php';
session_start();

if(isset($_GET['token'])){
    $token = $_GET['token'];
    if(verifyEmail($token)){
        header("Location: Login.php");
        exit();
    }
    else{
        echo "Email verification failed";
    }

}
else{
    echo "No token provided";
}

function verifyEmail($token) {
    global $conn;
    //stored procedure 
    $stmt = $conn->prepare("CALL UpdateIsConfirmedByToken(?)");
    $stmt->bind_param("s", $token);
    
    if (!$stmt) die('Unable to prepare statement');
    $result = $stmt->execute();
    $rows   = $stmt->get_result();
    $stmt->close();
    $conn->close();
    return $result;
    
}