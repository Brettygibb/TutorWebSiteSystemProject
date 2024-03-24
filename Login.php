<?php


if(isset($_GET['error'])){
    if($_GET['error'] == "invalidpassword"){
        session_start();
        $_SESSION['password_error'] = "Invalid password";
        header("Location: Login.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="styles.css">
    
</head>
<body>
<?php session_start(); ?> 
    
    <!-- Display error messages -->
    <?php if (!empty($_SESSION['email_error'])): ?>
        <script>alert('<?php echo addslashes($_SESSION['email_error']); ?>');</script>
        <?php unset($_SESSION['email_error']); ?>
    <?php endif; ?>

    <?php if (!empty($_SESSION['password_error'])): ?>
        <script>alert('<?php echo addslashes($_SESSION['password_error']); ?>');</script>
        <?php unset($_SESSION['password_error']); ?>
    <?php endif; ?>
    <div class="login-container">
        <form action="Procs/LoginProc.php" method="post">
            <div class="form-header">
                <img src="images/nbccLogo.png" alt="NBCC Tutoring Logo" class="form-logo">
            </div>
            <input type="text" name="email" placeholder="Email">
            <input type="password" name="pass" placeholder="Password">
            <button type="submit">Login</button>
            <p>Don't have an account? <a href="studentSignup.php">Sign up</a></p>
            <p>Forgot your password? <a href="ForgotPassword.php">Reset password</a></p>
        </form>
    </div>
</body>
</html>