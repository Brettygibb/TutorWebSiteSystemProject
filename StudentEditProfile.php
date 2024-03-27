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
        <button type="submit">Submit</button>
</body>
</html>