<?php
session_start();
include 'Connect.php';

// Set a test value for 'id' session variable
$_SESSION['id'] = 1; // Replace 123 with the desired test value

// Now you can use $_SESSION['id'] in your code as if it were set during a login process
$userid = $_SESSION['id'];

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

    <section>
        <h2>Welcome to the Student Dashboard</h2>
        <!-- Users Info -->
        <p>First Name: <?php echo $row['FirstName']; ?></p>
        <p>Last Name: <?php echo $row['LastName']; ?></p>
        <p>Email: <?php echo $row['Email']; ?></p>
        <p>Gender: <?php echo $row['Gender']; ?></p>
        <?php if (!empty($row['image'])): ?>
            <img src="<?php echo $row['image']; ?>" alt="Profile Picture">
        <?php endif; ?>
    </section>
</body>
</html>