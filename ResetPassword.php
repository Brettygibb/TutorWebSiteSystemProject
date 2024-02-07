<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
</head>
<body>
    <?php
    include 'Connect.php';

    if(isset($_POST['token']) && isset($_POST['password']) && isset($_POST['passwordConf'])){
        $token = $_POST['token'];
        $password = $_POST['password'];
        $passwordConf = $_POST['passwordConf'];

        // Validate if passwords match
        if($password !== $passwordConf) {
            echo "Passwords do not match";
            exit;
        }

        // Check if token is valid
        //need a stored procedure
        $verifyTokenQuery = "SELECT * FROM users WHERE ResetToken = ?";
        $verifyTokenStmt = $conn->prepare($verifyTokenQuery);
        $verifyTokenStmt->bind_param("s", $token);
        $verifyTokenStmt->execute();
        $result = $verifyTokenStmt->get_result();

        if($result->num_rows > 0){
            // Token is valid, update the password
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        
            //need a stored procedure
            $updatePasswordQuery = "UPDATE users SET PasswordHash = ?, ResetToken = NULL WHERE ResetToken = ?";
            $updatePasswordStmt = $conn->prepare($updatePasswordQuery);
            $updatePasswordStmt->bind_param("ss", $hashedPassword, $token);
            $updatePasswordStmt->execute();

            if($updatePasswordStmt->affected_rows > 0) {
                echo "Password updated successfully";
            } else {
                echo "Failed to update password";
            }
        } else {
            echo "Invalid token";
        }
    } else {
        echo "Invalid request";
    }
    ?>
    <form action="" method="post">
        <input type="hidden" name="token" value="<?php echo $_GET['token']; ?>">
        <label for="password">New Password:</label>
        <input type="password" name="password" id="password" required><br>
        <label for="passwordConf">Confirm Password:</label>
        <input type="password" name="passwordConf" id="passwordConf" required><br>
        <input type="submit" value="Reset Password">
    </form>
</body>
</html>
