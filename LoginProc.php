<?php
include 'Connect.php';

function loginUser($email, $pass, $role, $redirect) {
    global $conn;

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


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $pass = $_POST['pass'];

    // Student login
    loginUser($email, $pass, 'Student', 'StudentDashBoard.php');
    // Tutor login
    loginUser($email, $pass, 'Tutor', 'TutorDashBoard.php');
    // Admin login
    loginUser($email, $pass, 'Admin', 'AdminDashBoard.php');

    // If neither student nor tutor no admin, login failed
    echo "Login Failed";
}

mysqli_close($conn);
