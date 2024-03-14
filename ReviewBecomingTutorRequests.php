<?php
session_start();
include 'Database.php';
require_once 'Admin.php'; // Include the Admin class definition

// Create a new instance of the Database class 
$database = new Database($servername, $username, $password, $dbname);

// Get the database connection 
$conn = $database->getConnection();

// Create a new instance of the Admin class
$admin = new Admin();

// Fetch pending tutor requests along with student details using the obtainBecomeTutor method
$result = $admin->obtainWhoWantBecomeTutor($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Review Becoming a Tutor Requests</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <?php include 'Includes/AdminHeader.php'; ?>

    <section>
        <h1>Review Becoming a Tutor Requests</h1>
        <table>
            <tr>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Action</th>
            </tr>
            <?php while($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><?php echo $row['FirstName']; ?></td>
                    <td><?php echo $row['LastName']; ?></td>
                    <td>
                        <form action="ProcessTutorRequest.php" method="post">
                            <input type="hidden" name="student_id" value="<?php echo $row['StudentId']; ?>">
                            <select name="action">
                                <option value="approve">Approve</option>
                                <option value="deny">Deny</option>
                            </select>
                            <input type="submit" value="Submit">
                        </form>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>
    </section>
</body>
</html>
