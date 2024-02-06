<?php
include 'Connect.php';
session_start();


<<<<<<< Updated upstream
=======
    $sql = "SELECT UserID, FirstName, LastName, Email, PasswordHash, Role FROM users WHERE Email = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);

    if (mysqli_stmt_num_rows($stmt) == 1) {
        mysqli_stmt_bind_result($stmt, $id, $fname, $lname, $email, $hashedPassword, $role);
//password will not work unless the users password is hashed
        if (mysqli_stmt_fetch($stmt) && password_verify($pass, $hashedPassword)) {
            session_start();
            $_SESSION['email'] = $email;
            $_SESSION['id'] = $id;
            $_SESSION['name'] = $fname;
            $_SESSION['role'] = $role;
            $_SESSION['loggedin'] = true;
            header("Location: $redirect");
            exit();
        }
    }

    mysqli_stmt_close($stmt);
}
>>>>>>> Stashed changes


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $pass = $_POST['pass'];

    // need a stored procedure
    $sql = "SELECT UserId, FirstName, LastName, Email, PasswordHash, Role FROM users WHERE Email = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);

    if(mysqli_stmt_num_rows($stmt) == 1) {
        mysqli_stmt_bind_result($stmt, $id, $fname, $lname, $email, $hashedPassword, $role);
        mysqli_stmt_fetch($stmt);

        if (password_verify($pass, $hashedPassword)) {
            $_SESSION['id'] = $id;
            $_SESSION['fname'] = $fname;
            $_SESSION['lname'] = $lname;
            $_SESSION['email'] = $email;
            $_SESSION['role'] = $role;
            if($role == "Student"){
                header("Location: StudentDashBoard.php");
                exit();
            }
            else if($role == "Tutor"){
                header("Location: TutorDashBoard.php");
                exit();
            }
            else if($role == "Admin"){
                header("Location: AdminDashBoard.php");
                exit();
            }

        }


    
    
    }
    else{
        echo "Invalid email or password";
    }
    mysqli_stmt_close($stmt);

}

?>
