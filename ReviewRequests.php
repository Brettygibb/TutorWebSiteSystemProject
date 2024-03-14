<?php
session_start();
//include 'Connect.php';

// Check if the user is logged in and is an admin
/*
if (!isset($_SESSION['id']) || $_SESSION['role'] !== 'Admin') {
    header("Location: index.php");
    exit();
}
*/

include 'Database.php';
include 'Admin.php';

//Create a new instance of DB class 
$database= new Database($servername, $username, $password, $dbname);

//Get the database connection 
$conn= $database ->getConnection();


// Process form submission if any
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['requestId']) && isset($_POST['status']) && isset($_POST['courseId'])) {
        $requestId = $_POST['requestId'];
        $status = $_POST['status'];
        $courseId = $_POST['courseId'];

        // Create a new instance of Admin class
        $admin = new Admin();

        // Call the obtainRequestedCourses method
        $courseName = $admin->obtainRequestedCourses($requestId, $courseId, $status, $conn);


        // Check if the status is 'Approved' and insert into tutor_courses table
        if ($status === 'Approved') {
            // Update the status in the requests table
            $updateStatusSql = "UPDATE requests SET Status = 'Approved' WHERE TutorId = ? AND CourseId = ?";
            $stmt = $conn->prepare($updateStatusSql);
            $stmt->bind_param("ii", $requestId, $courseId);
            $stmt->execute();
            $stmt->close();            
            
            $insertSql = "CALL InsertTutorCourse(?, ?)";
            $stmt = $conn->prepare($insertSql);
            $stmt->bind_param("is", $requestId, $courseId);
            $stmt->execute();
            $stmt->close();

            // Fetch the userId of the tutor associated with the request
            $userIdSql = "SELECT UserId FROM tutors WHERE TutorId = ?";
            $stmt = $conn->prepare($userIdSql);
            $stmt->bind_param("i", $requestId);
            $stmt->execute();
            $stmt->bind_result($userId);
            $stmt->fetch();
            $stmt->close();

            // Add a notification for course request approval
            $notificationMessage = "Course request for $courseName was approved.";
            $insertNotificationSql = "INSERT INTO notifications (user_id, message, is_read) VALUES (?, ?, 0)";
            $stmt = $conn->prepare($insertNotificationSql);
            $stmt->bind_param("is", $userId, $notificationMessage);
            $stmt->execute();
            $stmt->close();

        } elseif ($status === 'Denied') {
            // Update the status in the requests table
            $updateStatusSql = "UPDATE requests SET Status = 'Denied' WHERE TutorId = ? AND CourseId = ?";
            $stmt = $conn->prepare($updateStatusSql);
            $stmt->bind_param("ii", $requestId, $courseId);
            $stmt->execute();
            $stmt->close(); 


            // Fetch the userId of the tutor associated with the request
            $userIdSql = "SELECT UserId FROM tutors WHERE TutorId = ?";
            $stmt = $conn->prepare($userIdSql);
            $stmt->bind_param("i", $requestId);
            $stmt->execute();
            $stmt->bind_result($userId);
            $stmt->fetch();
            $stmt->close();

            // Add a notification for course request denial
            $notificationMessage = "Course request for $courseName was denied.";
            $insertNotificationSql = "INSERT INTO notifications (user_id, message, is_read) VALUES (?, ?, 0)";
            $stmt = $conn->prepare($insertNotificationSql);
            $stmt->bind_param("is", $userId, $notificationMessage);
            $stmt->execute();
            $stmt->close();
        }
    }
}

// Fetch only pending requests with tutor names from the database
$sql = "CALL GetPendingTutorRequests()";
$result = mysqli_query($conn, $sql);

$conn->close();
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

    <section>
        <h1>Review New Courses for Tutors</h1>

        <table>
            <tr>
                <th>Tutor Name</th>
                <th>Course Name</th>
                <th>Status</th>
                <th>Action</th>
            </tr>

            <?php while ($row = mysqli_fetch_assoc($result)): ?>
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
