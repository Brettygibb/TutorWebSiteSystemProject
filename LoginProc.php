<?php
include 'Connect.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $pass = $_POST['pass'];

    $sql = "CALL UserLogin(?)";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if(mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);
        $hashedPassword = $row['PasswordHash'];

        if (password_verify($pass, $hashedPassword)) {
            $_SESSION['id'] = $row['UserId'];
            $_SESSION['fname'] = $row['FirstName'];
            $_SESSION['lname'] = $row['LastName'];
            $_SESSION['email'] = $row['Email'];
            
            // Check if the user has multiple roles
            $roles = explode(",", $row['RoleName']);
            if (count($roles) > 1) {
                // Default to student role
                $_SESSION['role'] = "Student";
            } else {
                $_SESSION['role'] = $roles[0];
            }
            
            // Redirect based on role
            switch($_SESSION['role']) {
                case "Student":
                    header("Location: StudentDashBoard.php");
                    exit();
                case "Tutor":
                    header("Location: TutorDashBoard.php");
                    exit();
                case "Admin":
                    header("Location: AdminDashBoard.php");
                    exit();
                default:
                    echo "Invalid role";
                    exit();
            }
        }
    } else {
        echo "Invalid email or password";
    }

    mysqli_stmt_close($stmt);
}
?>
