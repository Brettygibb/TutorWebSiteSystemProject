<?php
include 'Connect.php';

function loginUser($email, $pass, $role, $redirect) {
    global $conn;

    $sql = "SELECT * FROM users WHERE Email = ? AND PasswordHash = ? and Role = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "sss", $email, $pass, $role);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);

    if (mysqli_stmt_num_rows($stmt) == 1) {
        mysqli_stmt_bind_result($stmt, $id, $fname, $lname, $email, $pass, $role);
        if (mysqli_stmt_fetch($stmt)) {
            session_start();
            $_SESSION['email'] = $email;
            $_SESSION['pass'] = $pass;
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

    loginUser($email, $pass, 'Admin', 'AdminDashBoard.php');

    // If neither student nor tutor, login failed
    echo "Login Failed";
}

mysqli_close($conn);
