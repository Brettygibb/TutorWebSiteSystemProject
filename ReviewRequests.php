<?php
session_start();

include 'Database.php';
include 'Admin.php';

$database = new Database($servername, $username, $password, $dbname);
$conn = $database->getConnection();

// Process form submission if any
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['requestId']) && isset($_POST['status']) && isset($_POST['courseId'])) {
        $requestId = $_POST['requestId'];
        $status = $_POST['status'];
        $courseId = $_POST['courseId'];

        $admin = new Admin();
        $admin->processRequestedCourses($requestId, $courseId, $status, $conn);
    }
}
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
    <?php include 'Includes/AdminHeader.php'; ?>
    <?php
    // Fetch only pending requests with tutor names from the database
    $sql2 = "CALL GetPendingTutorRequests()";
    $result2 = mysqli_query($conn, $sql2);

    //$conn->close();
    ?>
    <section>
        <h1>Review New Courses for Tutors</h1>

        <table>
            <tr>
                <th>Tutor Name</th>
                <th>Course Name</th>
                <th>Status</th>
                <th>Action</th>
            </tr>

            <?php while ($row = mysqli_fetch_assoc($result2)): ?>
                <tr>
                    <td><?php echo $row['TutorName']; ?></td>
                    <td><?php echo $row['CourseName']; ?></td>
                    <td><?php echo $row['Status']; ?></td>
                    <td>
                        <?php if ($row['Status'] === 'Pending'): ?>
                            <!-- Display dropdown for changing the status for pending requests -->
                            <form method="post">
                                <input type="hidden" name="requestId" value="<?php echo $row['TutorId']; ?>">
                                <input type="hidden" name="courseId" value="<?php echo $row['CourseId']; ?>">
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
