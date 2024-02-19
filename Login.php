<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    
    <form action = "LoginProc.php" method = "post">
        <input type = "text" name = "email" placeholder = "Email">
        <input type = "password"  name = "pass" placeholder = "Password">
        <button type = "submit">Login</button>
    </form>
    <p>Don't have an account? <a href = "studentSignup.php">Sign up</a></p>
    <p>Forgot your password? <a href = "ForgotPassword.php">Reset password</a></p>
</body>
</html>