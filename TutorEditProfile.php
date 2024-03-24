<?php
if(isset($_GET['profile'])){
    if($_GET['profile'] == "success"){
        echo "<script>alert('Profile updated successfully');</script>";
    }else if($_GET['profile'] == "error"){
        echo "<script>alert('Error updating profile');</script>";
    }
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
        <label for="academic_background">Academic Background:</label><br>
        <textarea id="academic_background" name="academic_background" required></textarea><br>
        <label for="expertise">Areas of Expertise:</label><br>
        <input type="text" id="expertise" name="expertise" required><br>
        <label for="achievements">Relevant Achievements or Qualifications:</label><br>
        <textarea id="achievements" name="achievements" required></textarea><br>
        <lable for="bio">Bio:</lable><br>
        <textarea id="bio" name="bio" required></textarea><br>
        <label for="profilePicture">Profile Picture:</label>              
        <input type="file" name="image" placeholder="Profile Picture">
        <button type="submit">Submit</button>
</body>
</html>