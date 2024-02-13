<?php
include 'Connect.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $pass = $_POST['pass'];

    // Call the stored procedure
    $sql = "CALL UserLogin(?)";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt); // Get the result set from the statement

    if ($result && $row = mysqli_fetch_assoc($result)) {
        $id = $row['UserId'];
        $fname = $row['FirstName'];
        $lname = $row['LastName'];
        $hashedPassword = $row['PasswordHash'];
        $role = $row['Role'];

        if (password_verify($pass, $hashedPassword)) {
            // Set session variables
            $_SESSION['id'] = $id;
            $_SESSION['fname'] = $fname;
            $_SESSION['lname'] = $lname;
            $_SESSION['email'] = $email;
            $_SESSION['role'] = $role;

            // Redirect based on role
            if ($role == "Student") {
                header("Location: StudentDashBoard.php");
                exit();
            } elseif ($role == "Tutor") {
                header("Location: TutorDashBoard.php");
                exit();
            } elseif ($role == "Admin") {
                header("Location: AdminDashBoard.php");
                exit();
            }
        } else {
            // Handle incorrect password
            $_SESSION['password_error'] = "Incorrect password";
        }
    } else {
        // Handle invalid email
        $_SESSION['email_error'] = "Invalid email";
    }

    // Clean up
    mysqli_stmt_free_result($stmt);
    mysqli_stmt_close($stmt);
}

// Redirect to login page
if(isset($_SESSION['email_error']) || isset($_SESSION['password_error'])) {
    header("Location: Login.php");
    exit();
}
?>
