<?php
    if(isset($_GET['error'])){
        if($_GET['error'] == 'invalidemail'){
            echo "<script>alert('Invalid email')</script>";
        }
        
    }
    
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <form action="ForgotPasswordProc.php" method="post">
        <input type="email" name="email" placeholder="Enter your email">
        <button type="submit">Reset Password</button>
    </form>
</body>
</html>