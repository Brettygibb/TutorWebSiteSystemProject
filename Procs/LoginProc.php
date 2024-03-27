<?php
include '../Database.php';

$db = new Database($servername, $username, $password, $dbname);

$conn = $db->getConnection();
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $pass = $_POST['pass'];

    $sql = "CALL UserLogin(?)";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if(mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $hashedPassword = $row['PasswordHash'];

            if (password_verify($pass, $hashedPassword)) {
                $_SESSION['id'] = $row['UserId'];
                $_SESSION['fname'] = $row['FirstName'];
                $_SESSION['lname'] = $row['LastName'];
                $_SESSION['email'] = $row['Email'];
                
                // Handle multiple roles
                $roles = explode(',', $row['RoleName']);
                if (in_array('Student', $roles)) {
                    header("Location: ../StudentDashBoard.php");
                    exit();
                } elseif (in_array('Tutor', $roles)) {
                    header("Location: ../TutorDashBoard.php");
                    exit();
                } elseif (in_array('Admin', $roles)) {
                    header("Location: ../AdminDashBoard.php");
                    exit();
                } else {
                    echo "Invalid role";
                    exit();
                }
            }else{
                header("Location: ../Login.php?error=invalidpassword");
            }
        }
    } else {
        echo "Invalid email or password";
    }

    mysqli_stmt_close($stmt);
}

?>
