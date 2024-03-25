<?php
session_start();
include 'Database.php'; // Include the file where $servername, $username, $password, $dbname are defined

// Create a new instance of the Database class
$database = new Database($servername, $username, $password, $dbname);

// Get the database connection
$conn = $database->getConnection();

if(isset($_SESSION['id'])) {
    $userid = $_SESSION['id'];

    // Use stored procedure to get the user info
    $sql = "CALL GetUserByUserID(?)";
    $stmt = $conn->prepare($sql);
    if(!$stmt){
        echo "An error occurred while preparing the statement: ".$conn->error;
        exit();
    }
    $stmt->bind_param("i", $userid);
    $stmt->execute();
    $result = $stmt->get_result();
    if($result && $result->num_rows > 0) { // Check if result is not empty
        $row = $result->fetch_assoc();

        // You can now use $row to display user info
    } else {
        echo "No user found with ID: ".$userid;
    }
    $stmt->close();
} else {
    echo "User is not logged in.";
}

$conn->close(); // Close the connection after fetching user info
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
    <?php include 'Includes/AdminHeader.php'; ?>

    <section>
        <h2>Welcome to the Admin Dashboard</h2>
        <!-- Users Info -->
        <?php if (isset($row)): ?>
            <p>First Name: <?php echo $row['FirstName']; ?></p>
            <p>Last Name: <?php echo $row['LastName']; ?></p>
            <p>Email: <?php echo $row['Email']; ?></p>
            <?php if (!empty($row['image'])): ?>
                <img src="<?php echo $row['image']; ?>" alt="Profile Picture">
            <?php endif; ?>
        <?php endif; ?>
    </section>
</body>
</html>
