<?php
session_start();
include 'Database.php';

//jays test////////////////////////////////////////////////////////////////////// WORKED
// Create a new instance of the Database class
$database = new Database($servername, $username, $password, $dbname);

// Get the database connection
$conn = $database->getConnection();
/////////////////////////////////////////////////////////////////////////////////


$userid = $_SESSION['id'];
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
        <h2>Upcoming Sessions</h2>
        <?php
        $sql = "SELECT * FROM sessions WHERE StudentId = 5031242";

        $results = mysqli_query($conn,$sql);
        //$row = mysqli_fetch_assoc($result);

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