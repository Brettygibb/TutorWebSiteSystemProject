<?php

include("Connect.php");

if($_SERVER['REQUEST_METHOD']=='POST'){
    $email = $_POST['email'];

    //validate email,very simple validation
    if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
        echo "Invalid email format";
        exit();
    }

    $sql ="select * from users where Email = ?";
    $stmt = mysqli_prepare($conn,$sql);
    mysqli_stmt_bind_param($stmt,"s",$email);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);

    if(mysqli_stmt_num_rows($stmt)==1){
        $token = bin2hex(random_bytes(32));

        $updatesql = "u";
    }
}
?>