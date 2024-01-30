<?php

include("Connect.php");
include("sendResetEmail.php");

if($_SERVER['REQUEST_METHOD']=='POST'){
    $email = $_POST['email'];

    //need stored procedure
    $sql = "SELECT * FROM users WHERE email=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s",$email);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows > 0){
        $resetToken = generateRandomToken();
        //need stored procedure
        $sql = "UPDATE users SET ResetToken=? WHERE email=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss",$resetToken,$email);
        $stmt->execute();

        sendResetEmail($email,$resetToken);

        echo "Check your email to reset your password";
    }
    else{
        echo "Email does not exist";
    }
}
function generateRandomToken($length = 32) {
    return bin2hex(random_bytes($length));
}
?>