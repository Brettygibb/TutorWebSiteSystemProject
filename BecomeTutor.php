<?php
session_start();
include 'Connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve the student ID associated with the session user ID
    $userId = $_SESSION['id'];
    $sql = "SELECT StudentId FROM students WHERE UserId = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $userId);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $studentId);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);

    // Check if the student ID was successfully retrieved
    if ($studentId) {
        $status = 'Pending'; // Default status for new requests

        // Insert the new tutor request into the database
        $sql = "INSERT INTO becometutor_requests (StudentId, Status) VALUES (?, ?)";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "is", $studentId, $status);
        mysqli_stmt_execute($stmt);

        // Check if the request was successfully inserted
        if (mysqli_stmt_affected_rows($stmt) > 0) {
            echo "Your request to become a tutor has been submitted. You will be notified once it's reviewed.";
        } else {
            echo "Error: Unable to submit your request at this time. Please try again later.";
        }

        mysqli_stmt_close($stmt);
    } else {
        echo "Error: Student ID not found for the current user.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Become a Tutor</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h1>Become a Tutor</h1>
        <nav>
            <ul>
                <li><a href="StudentDashboard.php">Back to Dashboard</a></li>
                <li><a href="#">Logout</a></li>
            </ul>
        </nav>
    </header>

    <section>
        <h2>Request to Become a Tutor</h2>
        <form action="becometutor.php" method="post">
            <!-- Any additional fields for the tutor request form can be added here -->
            <input type="submit" name="submit" value="Submit Request">
        </form>
    </section>
</body>
</html>
