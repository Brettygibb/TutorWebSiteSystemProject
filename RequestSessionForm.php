<?php
session_start();

// Retrieve session details from URL parameters
$tutorId = isset($_GET['tutorId']) ? $_GET['tutorId'] : '';
$date = isset($_GET['date']) ? $_GET['date'] : '';
$startTime = isset($_GET['startTime']) ? $_GET['startTime'] : '';
$endTime = isset($_GET['endTime']) ? $_GET['endTime'] : '';
//get student id from session
$studentId = $_SESSION['studentId'];
$courseId = isset($_GET['courseId']) ? $_GET['courseId'] : ''; 


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Request Session</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1>Request Session</h1>
    <form action="Procs/RequestSessionFormProc.php" method="post">
        <input type="hidden" name="tutorId" value="<?php echo htmlspecialchars($tutorId); ?>">
        <input type="hidden" name="date" value="<?php echo htmlspecialchars($date); ?>">
        <input type="hidden" name="startTime" value="<?php echo htmlspecialchars($startTime); ?>">
        <input type="hidden" name="endTime" value="<?php echo htmlspecialchars($endTime); ?>">
        <input type="hidden" name="courseId" value="<?php echo htmlspecialchars($courseId); ?>"> 
        
        
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
        <label for="message">Message:</label>
        <textarea id="message" name="message"></textarea><br>
        
        <button type="submit">Send Request</button>
    </form>
</body>
</html>
