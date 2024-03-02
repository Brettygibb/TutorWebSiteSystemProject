<?php
session_start();
//include 'Connect.php';

include 'Database.php';

//Create a new instance of DB class 
$database= new Database($servername, $username, $password, $dbname);

//Get the database connection 
$conn= $database ->getConnection();

// Fetch pending tutor requests along with student details
$sql = "SELECT u.FirstName, u.LastName, bt.StudentId
        FROM becometutor_requests bt
        INNER JOIN students s ON bt.StudentId = s.StudentId
        INNER JOIN users u ON s.UserId = u.UserId
        WHERE bt.Status = 'Pending'";
$result = mysqli_query($conn, $sql);
if (!$result) {
    echo "Error fetching tutor requests: " . mysqli_error($conn);
    exit();
}
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
    <header>
        <h1>Review Becoming a Tutor Requests</h1>
        <nav>
            <ul>
                <li><a href="AdminDashboard.php">Back to Dashboard</a></li>
                <li><a href="#">Logout</a></li>
            </ul>
        </nav>
    </header>

    <section>
        <h2>Pending Tutor Requests</h2>
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

