<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
require '../PHPMailer/src/Exception.php';
require '../PHPMailer/src/PHPMailer.php';
require '../PHPMailer/src/SMTP.php';
//require 'vendor/autoload.php';
include 'Connect.php';

function generateRandomToken($length =32){
    return bin2hex(random_bytes($length));
}
function saveUserToDataBase($firstName, $lastName, $email, $pass, $token) {
    global $conn;

    // Check if the email address already exists
    //this doesnt work
    $checkQuery = "SELECT * FROM users WHERE Email = ?";
    $checkStmt = $conn->prepare($checkQuery);
    $checkStmt->bind_param("s", $email);
    $checkStmt->execute();
    $result = $checkStmt->get_result();

    if ($result->num_rows > 0) {
        // Email address already exists, display alert
        echo "<script>alert('User with this email already exists. Please choose a different email.');</script>";
    } else {
        // Insert new user into the database
        $hashedPassword = password_hash($pass, PASSWORD_DEFAULT);
        $insertQuery = "INSERT INTO users (FirstName, LastName, Email, PasswordHash, ConfirmationToken) VALUES (?, ?, ?, ?, ?)";
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
        $mail->Username = 'nbcctutoremail@gmail.com';
        $mail->Password = 'hxymgawarsapwfza';
        $mail->SMTPSecure = 'ssl';
        $mail->Port = 465;

        // Recipients
        $mail->setFrom('nbcctutoremail@gmail.com');
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
