<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require_once '../PHPMailer/src/Exception.php';
require_once '../PHPMailer/src/PHPMailer.php';
require_once '../PHPMailer/src/SMTP.php';

function sendResetEmail($email,$token){
    $resetLink = "http://localhost/tutorWebsite/TutorWebSiteSystemProject/resetPassword.php?token=$token";
    $message = "Click on the link to verify your email: $resetLink";

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
        $mail->addAddress($email);

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Password Reset';
        $mail->Body = $message;

        // Send email
        $mail->send();
        echo "Password reset email sent";
    } catch (Exception $e) {
        echo "Password reset email could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}