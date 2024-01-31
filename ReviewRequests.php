<?php
session_start();
include 'Connect.php';

// Check if the user is logged in and is an admin
if (!isset($_SESSION['id']) || $_SESSION['role'] !== 'Admin') {
    header("Location: index.php");
    exit();
}

// Process form submission if any
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['requestId']) && isset($_POST['status'])) {
        $requestId = $_POST['requestId'];
        $status = $_POST['status'];

        // Update the status of the request in the database
        $updateSql = "UPDATE requests SET Status = '$status' WHERE TutorId = $requestId";
        mysqli_query($conn, $updateSql);
    }
}

// Fetch all requests from the database
$sql = "SELECT * FROM requests";
$result = mysqli_query($conn, $sql);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Review Requests</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h1>Review Requests</h1>
        <nav>
            <ul>
                <li><a href="AdminDashboard.php">Home</a></li>
                <li><a href="#">Review Requests</a></li>
                <li><a href="AddAdmin.php">Add another Admin</a></li>
                <li><a href="Logout.php">Logout</a></li>
                <li><a href="AdminEditProfile.php">Edit Profile</a></li>
            </ul>
        </nav>
    </header>

    <section>
        <h2>Requests List</h2>

        <table>
            <tr>
                <th>Tutor ID</th>
                <th>Course ID</th>
                <th>Status</th>
                <th>Action</th>
            </tr>

            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><?php echo $row['TutorId']; ?></td>
                    <td><?php echo $row['CourseId']; ?></td>
                    <td><?php echo $row['Status']; ?></td>
                    <td>
                        <?php if ($row['Status'] === 'Pending'): ?>
                            <!-- Display dropdown for changing the status for pending requests -->
                            <form method="post">
                                <input type="hidden" name="requestId" value="<?php echo $row['TutorId']; ?>">
                                <select name="status">
                                    <option value="Approved">Approved</option>
                                    <option value="Denied">Denied</option>
                                </select>
                                <button type="submit">Update</button>
                            </form>
                        <?php else: ?>
                            <!-- Display 'N/A' for non-pending requests -->
                            N/A
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>
    </section>
</body>
</html>
