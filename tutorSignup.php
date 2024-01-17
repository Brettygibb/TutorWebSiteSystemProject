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
        <h2>Signup for Tutors</h2>
        <form action="tutorSignupProc.php" method="post">
            <label for="firstname">First Name:</label>
            <input type="text" id="firstname" name="firstname" required><br>
            
            <label for="lastname">Last Name:</label>
            <input type="text" id="lastname" name="lastname" required><br>

            <label for="email">Email Address:</label>
            <input type="email" id="email" name="email" required><br>
            
            <label for="password">Password:</label>
            <input type="text" id="password" name="password" required><br>

            <label for="phone">Phone Number:</label>
            <input type="tel" id="phone" name="phone" required><br>

            <label for="studentid">Student ID:</label>
            <input type="text" id="studentid" name="studentid" required><br>

            <input type="submit" value="Signup">
        </form>
    </div>
</body>
</html>
