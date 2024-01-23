<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
require '../PHPMailer/src/Exception.php';
require '../PHPMailer/src/PHPMailer.php';
require '../PHPMailer/src/SMTP.php';
include 'Connect.php';

function generateRandomToken($length =32){
    return bin2hex(random_bytes($length));
}
function saveUserToDataBase($firstName,$lastName,$email,$pass,$token){
    global $conn;

    $hashedPassword = password_hash($pass, PASSWORD_DEFAULT);
    // Insert data into the users table
    // Need to do stored procedure for this
    $sql = "INSERT INTO users (FirstName,LastName,Email,PasswordHash, ConfirmationToken) VALUES (?, ?, ?,?,?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssss",$firstName, $lastName,$email, $hashedPassword, $token);
    
    if($stmt->execute()){
        echo "User saved to database";

    }else{
        echo "Error: " . $conn->error;
    }
    $stmt->close();
    $conn->close();
}
function sendVerificationEmail($email,$token){
   $confirmLink = "http://localhost/TutorWebSiteSystemProject/ConfirmEmail.php?token=$token";
   $message = "Click on the link to verify your email: $confirmLink";
    $mail = new PHPMailer(true);

    try{
        $mail->SMTPDebug = 0;
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth =true;
        $mail->Username = 'bgibbons01@mynbcc.ca';
        $mail->Password = 'nbcc123456';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;
        $mail->setFrom('');
        $mail->addAddress($email);
        $mail->isHTML(true);
        $mail->Subject = 'Please verify your email';
        $mail->Body = $message;
        $mail->send();
        echo "Message has been sent";

    }
    catch(Exception $e){
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}