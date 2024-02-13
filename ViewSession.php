<?php
session_start();
include 'Connect.php';

$userid = $_SESSION['id'];
$sql = "SELECT * FROM users WHERE UserID = $userid";

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
    <?php include 'Includes/StudentHeader.php'; ?>
    <section>
        <h2>Welcome to the Student Dashboard</h2>
        <!-- Users Info -->
        <p>First Name: <?php echo $row['FirstName']; ?></p>
        <p>Last Name: <?php echo $row['LastName']; ?></p>
        <p>Email: <?php echo $row['Email']; ?></p>
        <?php if (!empty($row['image'])): ?>
            <img src="<?php echo $row['image']; ?>" alt="Profile Picture">
        <?php endif; ?>
    </section>

    <section>
        <?php

        $value = $_POST["submit"];

        //echo $value;
        $sql = "SELECT * FROM sessions WHERE SessionId = $value";

        $result = mysqli_query($conn,$sql);
        $row = mysqli_fetch_assoc($result);
        
        echo "<p>";
        echo $row['Course']; 
        echo "</p>";
        echo "<p>";
        echo $row['Description']; 
        echo "</p>";

        ?>

    </section>
</body>
</html>