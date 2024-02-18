<?php
include('Connect.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $firstName = $_POST['firstname'];
    $lastName = $_POST['lastname'];
    $email = $_POST['email'];
    $pass = $_POST['password'];

    // Prepare SQL statement to insert user data into users table
    $sql = "INSERT INTO users (firstName, lastName, email, password, resetToken, confirmationToken, isConfirmed) 
            VALUES (:firstName, :lastName, :email, :password, NULL, NULL, 1)";
    
    // Prepare and execute the SQL statement
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'firstName' => $firstName,
        'lastName' => $lastName,
        'email' => $email,
        'password' => $pass
    ]);

    // Redirect user to index.php after successful signup
    header("Location: index.php");
    exit();
}
    //saveUserToDataBase($firstName,$lastName,$email,$pass,$token);    
    //sendVerificationEmail($email,$token,$firstName,$lastName);
?>





