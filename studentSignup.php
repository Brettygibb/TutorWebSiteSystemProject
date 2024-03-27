<?php

if(isset($_GET['error'])){
    echo "<script>alert('Email Already Exists')</script>";
}
    
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Program Signup</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="signup-container">
        <h1>Signup for Students</h1>
        <form action="studentSignupProc.php" method="post">
        <div class="form-header">
                <img src="images/nbccLogo.png" alt="NBCC Tutoring Logo" class="form-logo">
            </div>
            <label for="firstname">First Name:</label>
            <input type="text" id="firstname" name="firstname" required><br>
            
            <label for="lastname">Last Name:</label>
            <input type="text" id="lastname" name="lastname" required><br>

            <label for="email">Email Address:</label>
            <input type="email" id="email" name="email" required><br>
            
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required><br>

            <button type="submit" value="Signup">Sign Up</button>
        </form>
    </div>
</body>
</html>
