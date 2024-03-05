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

        // Update the status of the request in the database with specific TutorId and CourseId
        $updateSql = "CALL UpdateRequestStatus(?, ?, ?)";
        $stmt = $conn->prepare($updateSql);
        $stmt->bind_param("iss", $requestId, $courseId, $status);
        $stmt->execute();
        $stmt->close();
        $result->free();
        while($conn->more_results()&&$conn->next_result()){
            if($result =$conn->use_result()){
                $result->free();
            }
        }


        // Check if the status is 'Approved' and insert into tutor_courses table
        if ($status === 'Approved') {
            $insertSql = "CALL InsertTutorCourse(?, ?)";
            $stmt = $conn->prepare($insertSql);
            $stmt->bind_param("is", $requestId, $courseId);
            $stmt->execute();
            
            $stmt->close();
            $result->free();
            while($conn->more_results()&&$conn->next_result()){
                if($result =$conn->use_result()){
                    $result->free();
                }
            }

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
    <header>
        <h1>Approve New Courses for Tutors</h1>
        <nav>
            <ul>
                <li><a href="AdminDashboard.php">Back to Dashboard</a></li>
                <li><a href="#">Logout</a></li>
            </ul>
        </nav>
    </header>

    <section>
        <h2>Pending Requests List</h2>

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
