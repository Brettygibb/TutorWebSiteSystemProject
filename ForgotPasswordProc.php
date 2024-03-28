<?php

include("Database.php");
include("sendResetEmail.php");

$db = new Database($servername, $username, $password, $dbname); // Add parameters as required by your constructor
$conn = $db->getConnection();

if($_SERVER['REQUEST_METHOD']=='POST'){
    $email = $_POST['email'];

    //need stored procedure
    $sql = "CALL GetUserByEmail(?)";
    $stmt = $conn->prepare($sql);
    if(!$stmt){
        echo "<script> alert('Unable to prepare statement. Error: ".$conn->error."')</script";
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
            echo "<script> alert('Unable to prepare statement. Error: ".$conn->error."')</script";
            
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

        echo "<script> alert('Password reset email sent')</script>";
        header("Location: Login.php?reset=success");
    }
    else{
        header("Location: ForgotPassword.php?error=invalidemail");
    }
    $stmt->close();
    $conn->close();
}
function generateRandomToken($length = 32) {
    return bin2hex(random_bytes($length));
}
?>