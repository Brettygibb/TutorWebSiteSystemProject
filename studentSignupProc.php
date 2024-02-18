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
        // Retrieve the userid of the recently saved record
        $userid = mysqli_insert_id($conn);

        // Insert a new record into the user_roles table
        $role = 1; // Assuming 1 represents the role for students
        $sqlUserRole = "INSERT INTO user_roles (userid, roleid) VALUES ('$userid', '$role')";
        if (mysqli_query($conn, $sqlUserRole)) {
            // Insert a record into the student table
            $sqlStudent = "INSERT INTO student (StudentId, UserId) VALUES (NULL, '$userid')";
            if (mysqli_query($conn, $sqlStudent)) {
                // Redirect user to index.php after successful signup
                header("Location: index.php");
                exit();
            } else {
                echo "Error inserting into student table: " . mysqli_error($conn);
            }
        } else {
            echo "Error inserting into user_roles table: " . mysqli_error($conn);
        }
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }
}
?>





<?php
    //saveUserToDataBase($firstName,$lastName,$email,$pass,$token);    
    //sendVerificationEmail($email,$token,$firstName,$lastName);
?>





