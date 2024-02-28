<?php
session_start();
include 'Connect.php';

if(isset($_SESSION['id'])) {
    $userid = $_SESSION['id'];

    // Use stored procedure to get the user info
    $sql = "CALL GetUserByUserID(?)";
    $stmt = $conn->prepare($sql);
    if(!$stmt){
        echo "Error: ".$conn->error;
        exit();
    }
    $stmt->bind_param("i", $userid);
    $stmt->execute();
    $result = $stmt->get_result();
    if($result) {
        $row = $result->fetch_assoc();

        // You can now use $row to display user info
    } else {
        echo "No user found with ID: ".$userid;
    }
    $stmt->close();
    $conn->close();
} else {
    echo "User is not logged in.";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h1>Admin Dashboard</h1>
        <nav>
            <ul>
                <li><a href="#">Home</a></li>
                <li><a href="ReviewBecomingTutorRequests.php">Review Becoming a Tutor</a></li>
                <li><a href="ReviewRequests.php">Approve New Courses for Tutors</a></li>
                <li><a href="AddAdmin.php">Add another Admin</a></li>
                <li><a href="AdminEditProfile.php">Edit Profile</a></li>
                <li><a href="#">Logout</a></li>
            </ul>
        </nav>
    </header>

    <section>
        <h2>Welcome to the Admin Dashboard</h2>
        <!-- Users Info -->
        <p>First Name: <?php echo $row['FirstName']; ?></p>
        <p>Last Name: <?php echo $row['LastName']; ?></p>
        <p>Email: <?php echo $row['Email']; ?></p>
        <?php if (!empty($row['image'])): ?>
            <img src="<?php echo $row['image']; ?>" alt="Profile Picture">
        <?php endif; ?>
    </section>
</body>
</html>