<?php
session_start();
include('Database.php');

// Create a new instance of the Database class
$database = new Database($servername, $username, $password, $dbname);

// Get the database connection
$conn = $database->getConnection();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve the student ID associated with the session user ID
    $userId = $_SESSION['id'];
    $sql = "CALL BecomeTutorIdFetch(?)";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $userId);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $studentId);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);

    

    // Check if the student ID was successfully retrieved
    if ($studentId) {
        // Check if there is already a record for the student in the becometutor_requests table
        $sql_check = "CALL BecomeTutorRequest(?);";
        
        $stmt_check = mysqli_prepare($conn, $sql_check);
        mysqli_stmt_bind_param($stmt_check, "i", $studentId);
        mysqli_stmt_execute($stmt_check);
        mysqli_stmt_store_result($stmt_check);

        //test
        // Consume result set of the stored procedure call
        while(mysqli_next_result($conn)) {;}

        if (mysqli_stmt_num_rows($stmt_check) > 0) {
            echo "Previously you made an application for becoming a tutor.";
            header("refresh:3; url=StudentDashboard.php");
            exit();
        }

        // If no existing record, proceed with inserting the new request
        $status = 'Pending'; // Default status for new requests
        $sql_insert = "CALL InsertBecomeTutorRequest(?, ?)";
        $stmt_insert = mysqli_prepare($conn, $sql_insert);
        mysqli_stmt_bind_param($stmt_insert, "is", $studentId, $status);
        mysqli_stmt_execute($stmt_insert);

        // Check if the request was successfully inserted
        if (mysqli_stmt_affected_rows($stmt_insert) > 0) {
            echo "Your request to become a tutor has been submitted. You will be notified once it's reviewed.";
        } else {
            echo "Error: Unable to submit your request at this time. Please try again later.";
        }

        mysqli_stmt_close($stmt_insert);
    } else {
        echo "Error: Student ID not found for the current user.";
    }

    mysqli_close($conn);
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
