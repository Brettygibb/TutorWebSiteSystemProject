<?php
session_start();
include 'Connect.php';

$userid = $_SESSION['id'];
$sql = "select * from users where UserID = $userid";

$result = mysqli_query($conn,$sql);
$row = mysqli_fetch_assoc($result);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h1>Student Dashboard</h1>
        <nav>
            <ul>
                <li><a href="#">Home</a></li>
                <li><a href="#">Search Tutors</a></li>
                <li><a href="#">Upcoming Sessions</a></li>
                <li><a href="#">Logout</a></li>
                <li><a href="StudentEditProfile.php">Edit Profile</a></li>
            </ul>
        </nav>
    </header>

    
</body>
</html>