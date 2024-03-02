<?php
//comment this whole page

//include 'Connect.php';
include 'Database.php';

//Create a new instance of DB class 
$database= new Database($servername, $username, $password, $dbname);

//Get the database connection 
$conn= $database ->getConnection();

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

        move_uploaded_file($image_tmp, $image_path);
    }
    else{
        $image_path = 'default_image.jpg';
    }

     //need a stored procedure
    //we need to add the user gender, image to the database that was kinda my(Brett) fuck up
    //so dont let me(Brett) forget about this
    $sql = "update users set PasswordHash = ?,Gender = ?,image = ? where UserID = ?";
    $stmt = mysqli_prepare($conn,$sql);
    $hashed_password = password_hash($pass,PASSWORD_DEFAULT);
    mysqli_stmt_bind_param($stmt,"sssi",$hashed_password,$gender,$image_path,$_SESSION['id']);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    header("Location: AdminDashBoard.php");
    exit();

}
mysqli_close($conn);