<?php
session_start();
include 'Database.php';
require_once 'Admin.php'; // Include the Admin class definition

// Create a new instance of the Database class 
$database = new Database($servername, $username, $password, $dbname);

// Get the database connection 
$conn = $database->getConnection();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Create an Admin object
    $admin = new Admin($firstname, $lastname, $email, $password);

    // Insert admin into admins table
    $admin->insertIntoDatabase($conn);

    // Redirect to Admin Dashboard or any other page after successful submission
    header("Location: AdminDashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Admin</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <?php include 'Includes/AdminHeader.php'; ?>
    <section>
        <h1>Add another Admin</h1>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <label for="firstname">First Name:</label>
            <input type="text" id="firstname" name="firstname" required><br><br>

            <label for="lastname">Last Name:</label>
            <input type="text" id="lastname" name="lastname" required><br><br>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required><br><br>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required><br><br>

            <input type="submit" value="Submit">
        </form>
    </section>
</body>
</html>
