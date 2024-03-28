<?php
    if(isset($_GET['message'])){
        if($_GET['message'] == 'fieldsRequired'){
            echo "<script>alert('All fields are required');</script>";
        }
        else if($_GET['message'] == 'Invalid Email'){
            echo "<script>alert('Invalid Email');</script>";
        }
        else if($_GET['message'] == 'ProfileUpdatedSuccessfully'){
            echo "<script>alert('Profile Updated Successfully');</script>";
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
    <?php include 'Includes/StudentHeader.php'; ?>
    <form action="StudentEditProc.php" method="post" enctype="multipart/form-data">
        <label for="firstName">First Name:</label>
        <input type="text" name="firstName" placeholder="First Name">
        <label for="lastName">Last Name:</label>
        <input type="text" name="lastName" placeholder="Last Name">
        <label for="email">Email:</label>
        <input type="text" name="email" placeholder="Email">
        
        <button type="submit">Submit</button>
</body>
</html>