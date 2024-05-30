<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require_once 'PHPMailer\PHPMailer\src\Exception.php';
require_once 'PHPMailer\PHPMailer\src\PHPMailer.php';
require_once 'PHPMailer\PHPMailer\src\SMTP.php';
//require 'vendor/autoload.php';
include 'Connect.php';

function generateRandomToken($length =32){
    return bin2hex(random_bytes($length));
}
function saveUserToDataBase($firstName, $lastName, $email, $pass, $token) {
    global $conn;

    // Check if the email address already exists
    //this doesnt work
    $checkQuery = "CALL GetUserByEmail(?)";
    $checkStmt = $conn->prepare($checkQuery);
    $checkStmt->bind_param("s", $email);
    $checkStmt->execute();
    $result = $checkStmt->get_result();

    if ($result->num_rows > 0) {
        // Email address already exists, display alert
        echo "<script>alert('User with this email already exists. Please choose a different email.');</script>";
    } else {
        $result->free();
        while($conn->more_results()&&$conn->next_result()){
            if($result =$conn->use_result()){
                $result->free();
            }
        }

        // Insert new user into the database
        $hashedPassword = password_hash($pass, PASSWORD_DEFAULT);
        $insertQuery = "CALL AddUser(?,?,?,?,?)";
        $insertStmt = $conn->prepare($insertQuery);
        $insertStmt->bind_param("sssss", $firstName, $lastName, $email, $hashedPassword, $token);
        
        if ($insertStmt->execute()) {
            // User saved to database, send confirmation email
            sendVerificationEmail($email, $token, $firstName, $lastName);
            // Redirect to confirmation message page
            header('Location: ConfirmationMessage.php');
            exit;
        } else {
            echo "Error: " . $conn->error;
        }

        // Close the insert statement
        $insertStmt->close();
    }

    // Close the check statement
    $checkStmt->close();
    $conn->close();
}



function sendVerificationEmail($email, $token, $firstName, $lastName) {
    $confirmLink = "http://localhost/tutorWebsite/TutorWebSiteSystemProject/ConfirmEmail.php?token=$token";
    $message = "Click on the link to verify your email: $confirmLink";

    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = '';
        $mail->Password = '';
        $mail->SMTPSecure = '';
        $mail->Port = ;

        // Recipients
        $mail->setFrom('');
        $mail->addAddress($email, $firstName . ' ' . $lastName);

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Please verify your email';
        $mail->Body = $message;

        // Send email
        $mail->send();
        echo "Message has been sent";
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}

function welcomeEmail($email, $firstName) {
    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host = '';
        $mail->SMTPAuth = true;
        $mail->Username = '';
        $mail->Password = '';
        $mail->SMTPSecure = '';
        $mail->Port = 465;

        // Recipients
        $mail->setFrom('');
        $mail->addAddress($email, $firstName);

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Welcome to NBCC Tutoring';
        $mail->Body = 'Welcome to NBCC Tutoring!';
        $mail->AltBody = 'Welcome to NBCC Tutoring!';

        // Send email
        $mail->send();
        echo "Message has been sent";
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}
