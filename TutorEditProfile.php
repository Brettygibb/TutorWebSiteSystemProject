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
        <input type="text" name="email" placeholder="Email">
        <input type="text" name="pass" placeholder="Password">
        <label for="gender">Gender:</label>
        <select name="gender" id="gender">
            <option value="Male">Male</option>
            <option value="Female">Female</option>
            <option value="Other">Other</option>
        </select><br>  
        <label for="profilePicture">Profile Picture:</label>              
        <input type="file" name="image" placeholder="Profile Picture">
        <button type="submit">Submit</button>
</body>
</html>