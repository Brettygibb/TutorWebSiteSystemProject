<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Request Form</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <?php include 'Includes/StudentHeader.php'; ?>
    <h1>Student Request Form</h1>
    <form action="StudentRequestProc.php" method="post">
        <label for="firstName">First Name</label>
        <input type="text" name="firstName" id="firstName" required>
        <br>
        <label for="lastName">Last Name</label>
        <input type="text" name="lastName" id="lastName" required>
        <br>
        <label for="email">Email</label>
        <input type="email" name="email" id="email" required>
        <br>
        <label for="phone">Phone</label>
        <input type="text" name="phone" id="phone" required>
        <br>
        <label for="subject">Subject</label>
        <input type="text" name="subject" id="subject" required>
        <br>
        <label for="description">Description</label>
        <textarea name="description" id="description" required></textarea>
        <br>
        <input type="submit" value="Submit">
    </form>
    
</body>
</html>