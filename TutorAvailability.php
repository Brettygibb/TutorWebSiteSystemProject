<?php
session_start();

include 'Database.php'; // Include database connection

// Establish database connection
$database = new Database($servername, $username, $password, $dbname);
$conn = $database->getConnection();

$tutorId = $_SESSION['tutorId'];

if (!isset($_SESSION['tutorId'])) {
    header("Location: Login.php");
}

if (isset($_GET['success'])) {
    if ($_GET['success'] == "true") {
        echo "<script>alert('Availability added successfully');</script>";
    } else if ($_GET['success'] == "false") {
        echo "<script>alert('Error adding availability');</script>";
    }
}
if (isset($_GET['error'])) {
    if ($_GET['error'] == "overlap") {
        echo "<script>alert('You are already available at this time');</script>";
    }
    if ($_GET['error'] == "past") {
        echo "<script>alert('You cannot add availability for a past date');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enter Availability</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <?php include 'Includes/TutorHeader.php'; ?>
    <form action="Procs/TutorAvailabilityProc.php" method="post">
        <label for="availableDate">Date:</label>
        <input type="date" id="availableDate" name="availableDate" required>
        <label for="startTime">Start Time:</label>
        <input type="time" id="startTime" name="startTime" step="600" required>
        <button type="submit">Submit</button>
    </form>
</body>
</html>
