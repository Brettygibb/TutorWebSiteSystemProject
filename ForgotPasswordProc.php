<?php

include("Connect.php");
include("sendResetEmail.php");

if($_SERVER['REQUEST_METHOD']=='POST'){
    $email = $_POST['email'];

    //need stored procedure
    $sql = "CALL GetUserByEmail(?)";
    $stmt = $conn->prepare($sql);
    if(!$stmt){
        echo "Error: ".$conn->error;
        exit();
    }
    $stmt->bind_param("s",$email);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows > 0){
        $resetToken = generateRandomToken();
        //frees reseult set cant run more then one stored proc at a time
        //need to add this
        $result->free();
        while($stmt->more_results()&&$stmt->next_result()){
            if($res =$stmt->get_result()){
                $res->free();
            }
        }
        $stmt->close();

        $sql = "CALL UpdateUserResetToken(?,?)";
        $stmt = $conn->prepare($sql);
        if(!$stmt){
            echo "Error: ".$conn->error;
            exit();
        }
        $stmt->bind_param("ss",$resetToken,$email);
        $stmt->execute();

        do{
            if($res =$stmt->get_result()){
                $res->free();
            }
        }while($stmt->more_results()&&$stmt->next_result());

        sendResetEmail($email,$resetToken);

        echo "Check your email to reset your password";
    }
    else{
        echo "Email does not exist";
    }
    $stmt->close();
    $conn->close();
}
function generateRandomToken($length = 32) {
    return bin2hex(random_bytes($length));
}
?>