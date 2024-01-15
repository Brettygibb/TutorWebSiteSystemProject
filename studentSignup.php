<?php
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tutoring Program Signup</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="signup-container">
        <h2>Signup for Students</h2>
        <form action="process_signup.php" method="post">
            <label for="fullname">Full Name:</label>
            <input type="text" id="fullname" name="fullname" required><BR>

            <label for="email">Email Address:</label>
            <input type="email" id="email" name="email" required><BR>

            <label for="phone">Phone Number:</label>
            <input type="tel" id="phone" name="phone" required><BR>

            <label for="studentid">Student ID:</label>
            <input type="text" id="studentid" name="studentid" required><BR>

            <input type="submit" value="Signup">
        </form>
    </div>
</body>
</html>

