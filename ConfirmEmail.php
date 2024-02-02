<?php

require_once 'Connect.php';
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
    $query = "UPDATE users SET IsConfirmed = true WHERE ConfirmationToken = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $token);
    $stmt->execute();
    if ($stmt->affected_rows == 1) {
        echo "Email verified successfully"; // Indicate successful email confirmation
        return true;
    } else {
        echo "Email verification failed"; // Indicate failure to confirm email
        return false;
    }
}