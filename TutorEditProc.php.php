<?php

include 'Connect.php';

session_start();

if($_SERVER['REQUEST_METHOD']=='POST'){
    $email = $_POST['email'];
    $pass = $_POST['pass'];
    $gender = $_POST['gender'];

    if($_FILES['image']['size']>0){
        $image = $_FILES['image'];
        $image_name = $image['name'];
        $image_tmp = $image['tmp_name'];
        $image_path = "images/".$image_name;

        if(move_uploaded_file($image_tmp, $image_path)) {
            echo "Image uploaded successfully";
        } else {
            echo "Error: Failed to upload image";
            exit();
        }
    }
    else{
        $image_path = 'default_image.jpg';
    }

    //need a stored procedure to update the user info
    $sql = "update users set PasswordHash = ?,Gender = ?,image = ? where UserID = ?";
    $stmt = mysqli_prepare($conn,$sql);
    $hashed_password = password_hash($pass,PASSWORD_DEFAULT);
    mysqli_stmt_bind_param($stmt,"sssi",$hashed_password,$gender,$image_path,$_SESSION['id']);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    header("Location: StudentDashBoard.php");
    exit();

}
mysqli_close($conn);