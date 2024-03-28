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
        header("Location: StudentEditProfile.php?message=fieldsRequired");
        exit();
    }
    if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
        header("Location: StudentEditProfile.php?message=Invalid Email");
        exit();
    }
    $sql = "Update users set FirstName = ?, LastName = ?, Email = ? where UserId = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssi", $firstName, $lastName, $email, $userId);
    $stmt->execute();
    $stmt->close();

    header("Location: StudentDashBoard.php?message=ProfileUpdatedSuccessfully");
    exit();

}
else{
    header("Location: StudentEditProfile.php?message=Invalid Request");
    exit();
}
mysqli_close($conn);