<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <?php
    include 'Database.php';
    $db = new Database($servername, $username, $password, $dbname); // Add parameters as required by your constructor
    $conn = $db->getConnection();

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
        $verifyTokenQuery = "CALL VerifyUserByResetToken(?)";
        $verifyTokenStmt = $conn->prepare($verifyTokenQuery);
        if(!$verifyTokenStmt){
            echo "Error: ".$conn->error;
            exit();
        }
        $verifyTokenStmt->bind_param("s", $token);
        $verifyTokenStmt->execute();
        $result = $verifyTokenStmt->get_result();

        if($result->num_rows > 0){
            $result->free();
            while($verifyTokenStmt->more_results()&&$verifyTokenStmt->next_result()){
                if($res =$verifyTokenStmt->get_result()){
                    $res->free();
                }
            }

            // Token is valid, update the password
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        

            //need a stored procedure
            $updatePasswordQuery = "CALL UpdateUserPasswordByToken(?,?)";
            $updatePasswordStmt = $conn->prepare($updatePasswordQuery);
            if(!$updatePasswordStmt){
                echo "Error: ".$conn->error;
                exit();
            }
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
