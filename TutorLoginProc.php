<?php

include 'Connect.php';

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $email = $_POST['email'];
    $pass = $_POST['pass'];

    $sql = "SELECT * FROM users WHERE Email = ? AND PasswordHash = ? and Role = 'Tutor'";
    $stmt =mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ss", $email, $pass);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);

    if(mysqli_stmt_num_rows($stmt) == 1){
        mysqli_stmt_bind_result($stmt, $id, $fname, $lname, $email, $pass, $role);
        if(mysqli_stmt_fetch($stmt)){
            session_start();
        $_SESSION['email'] = $email;
        $_SESSION['pass'] = $pass;
        $_SESSION['id'] = $id;
        $_SESSION['name'] = $fname;
        $_SESSION['role'] = $role;
        $_SESSION['loggedin'] = true;
        header("Location: index.php");
        exit();
        }
    }else{
        echo "Login Failed";
    }

    mysqli_stmt_close($stmt);
}
mysqli_close($conn);