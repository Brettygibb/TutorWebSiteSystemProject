<?php
session_start();
if(isset($_GET['profile'])){
    if($_GET['profile'] == "success"){
        echo "<script>alert('Profile updated successfully');</script>";
    }else if($_GET['profile'] == "error"){
        echo "<script>alert('Error updating profile');</script>";
    }
}
//display userid
if (!isset($_SESSION['id'])) {
    header("Location: Login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <?php include 'Includes/TutorHeader.php'; ?>
    <h1>Edit Profile</h1>
    <form action="Procs/TutorEditProc.php" method="post" enctype="multipart/form-data">
        <label for="FirstName">First Name:</label><br>
        <input type="text" id="FirstName" name="FirstName" required><br>
        <label for="LastName">Last Name:</label><br>
        <input type="text" id="LastName" name="LastName" required><br>
        <label for="email">Email:</label><br>
        <input type="email" id="email" name="email" required><br>
        <label for="academic_background">Academic Background:</label><br>
        <textarea id="academic_background" name="academic_background" required></textarea><br>
        <label for="expertise">Areas of Expertise:</label><br>
        <input type="text" id="expertise" name="expertise" required><br>
        <label for="achievements">Relevant Achievements or Qualifications:</label><br>
        <textarea id="achievements" name="achievements" required></textarea><br>
        <lable for="bio">Bio:</lable><br>
        <textarea id="bio" name="bio" required></textarea><br>
        <button type="submit">Submit</button>
</body>
</html>