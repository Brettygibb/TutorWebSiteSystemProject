<?php
include('Connect.php');
include('Email_Confirmation.php');
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $firstName = $_POST['firstname'];
    $lastName = $_POST['lastname'];
    $email = $_POST['email'];
    $pass = $_POST['password'];
    $token = generateRandomToken();
    saveUserToDataBase($firstName,$lastName,$email,$pass,$token);
    sendVerificationEmail($email,$token,$firstName,$lastName);
    header("Location: ConfirmationMessage.php");
    exit();
}
?>
