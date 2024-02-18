<?php
include('connect.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $firstName = $_POST['firstname'];
    $lastName = $_POST['lastname'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Hash the password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Prepare SQL statement to insert user data into users table
    $sql = "INSERT INTO users (firstName, lastName, email, PasswordHash, resetToken, confirmationToken, isConfirmed) 
            VALUES ('$firstName', '$lastName', '$email', '$hashedPassword', '', '', 1)";
    
    // Execute the SQL statement
    if (mysqli_query($conn, $sql)) {
        // Redirect user to index.php after successful signup
        header("Location: index.php");
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }
}
?>



<?php
    //saveUserToDataBase($firstName,$lastName,$email,$pass,$token);    
    //sendVerificationEmail($email,$token,$firstName,$lastName);
?>





