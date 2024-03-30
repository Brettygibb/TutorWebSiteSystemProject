<?php

//include 'Connect.php';

include 'Database.php';

//Create a new instance of DB class 
$database= new Database($servername, $username, $password, $dbname);

//Get the database connection 
$conn= $database ->getConnection();

session_start();

if($_SERVER['REQUEST_METHOD']=='POST'){
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $userId = $_SESSION['id'];

    
    if(empty($firstName) || empty($lastName) || empty($email)){
        header("Location: TutorEditProfile.php?message=fieldsRequired");
        exit();
    }
    if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
        header("Location: TutorEditProfile.php?message=Invalid Email");
        exit();
    }
    $sql = "Call UpdateUserInfo(?,?,?,?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isss",$userId,$firstName,$lastName,$email);
    if(!$stmt->execute()){
        header("Location: TutorEditProfile.php?message=FailedToUpdateProfile");
        exit();
    }
    

    header("Location: TutorDashBoard.php?message=ProfileUpdatedSuccessfully");
    exit();

}
else{
    header("Location: TutorEditProfile.php?message=Invalid Request");
    exit();
}
mysqli_close($conn);