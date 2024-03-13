<?php
session_start();
//include 'Connect.php';
include 'Database.php';

//Create a new instance of DB class 
$database= new Database($servername, $username, $password, $dbname);

//Get the database connection 
$conn= $database ->getConnection();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Insert user into users table
    $sql_insert_user = "INSERT INTO users (FirstName, LastName, Email, PasswordHash)
                        VALUES ('$firstname', '$lastname', '$email', '$password')";
    mysqli_query($conn, $sql_insert_user);
    
    $user_id = mysqli_insert_id($conn); // Get the ID of the inserted user
    
    // Insert admin into admins table
    $sql_insert_admin = "INSERT INTO admins (UserId)
                         VALUES ($user_id)";
    mysqli_query($conn, $sql_insert_admin);

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
