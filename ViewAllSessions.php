<?php
session_start();
include 'Connect.php';

//$userid = $_SESSION['id'];
$userid = 12;
//need a stored procedure
$sql = "select * from users where UserID = $userid";

$result = mysqli_query($conn,$sql);
$row = mysqli_fetch_assoc($result);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Sessions</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h1>Student Dashboard</h1>
        <nav>
            <ul>
                <li><a href="#">Home</a></li>
                <li><a href="#">Search Tutors</a></li>
                <li><a href="#">Logout</a></li>
                <li><a href="StudentEditProfile.php">Edit Profile</a></li>
            </ul>
        </nav>
    </header>

    <section>
        <h2>Sessions</h2>
        <?php

        $tutor = $_GET['Id'];
        $course = $_GET['Course'];

        $results = mysqli_query($conn,"CALL GetTutorsOfCourse($tutor, '$course')");
        //$row = mysqli_fetch_assoc($result);

        $resultset = array();
        while ($row = mysqli_fetch_array($results)) {
            $resultset[] = $row;
        }

        echo '<form action="" method="post">';

        foreach ($resultset as $result){
            echo "<p>";
            echo $result['FirstName'], " ", $result['LastName'], " ", $result['Course'], " ", $result['DateAndTime'], " ", $result['Description'], " ", "<button name=submit value=$result[SessionId] >Request Admission</button>";
            echo "</p>";
        }

        echo '</form>';

        ?>

    </section>
</body>
</html>