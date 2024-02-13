<?php
session_start();
include 'Connect.php';

$userid = $_SESSION['id'];
//need a stored procedure
$sql = "select * from users where UserID = $userid";

$result = mysqli_query($conn,$sql);

// Check if query was successful
if (!$result) {
    die("Error: Failed to retrieve user information from the database.");
}

$row = mysqli_fetch_assoc($result);

// Check if user data exists
if (!$row) {
    die("Error: User information not found.");
}

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
                <li><a href="#">Logout</a></li>
                <li><a href="StudentEditProfile.php">Edit Profile</a></li>
            </ul>
        </nav>
    </header>

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
        <h2>Upcoming Sessions</h2>
        <?php
        $sql = "SELECT * FROM sessions WHERE StudentId = 5031242";

        $results = mysqli_query($conn,$sql);
        //$row = mysqli_fetch_assoc($result);

        // Check if query was successful
        if (!$results) {
            die("Error: Failed to retrieve upcoming sessions from the database.");
        }

        $resultset = array();
        while ($row = mysqli_fetch_array($results)) {
            $resultset[] = $row;
        }

        echo '<form action="ViewSession.php" method="post">';

        foreach ($resultset as $result){
            echo "<p>";
            echo $result['Course'], " ", $result['DateAndTime']," ", "<button name=submit value=$result[SessionId] >View Session</button>";
            echo "</p>";
        }

        echo '</form>';

        ?>

    </section>
</body>
</html>