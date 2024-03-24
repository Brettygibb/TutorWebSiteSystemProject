<?php
session_start();
// Check if the user is logged in and the required parameters are present
if (!isset($_SESSION['studentId']) || !isset($_GET['sessionId']) || !isset($_GET['tutorId'])) {
    die("Unauthorized access or missing information.");
}

// Sanitize GET parameters
$sessionId = htmlspecialchars($_GET['sessionId']);
$tutorId = htmlspecialchars($_GET['tutorId']);
$studentId = $_SESSION['studentId']; // Already sanitized during login

include 'Database.php'; // Adjust the path as needed

// Database connection
$db = new Database($servername, $username, $password, $dbname); // Add parameters as required by your constructor
$conn = $db->getConnection();

echo $studentId;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leave a Review</title>
    <link rel="stylesheet" href="styles.css"> <!-- Adjust as needed -->
</head>
<body>
    <h2>Leave a Review</h2>
    <form action="Procs/LeaveReviewProc.php" method="POST">
        <input type="hidden" name="sessionId" value="<?php echo $sessionId; ?>">
        <input type="hidden" name="tutorId" value="<?php echo $tutorId; ?>">
        <input type="hidden" name="studentId" value="<?php echo $studentId; ?>">
        <label for="rating">Rating:</label>
        <select name="rating" id="rating">
            <option value="1">1</option>
            <option value="2">2</option>
            <option value="3">3</option>
            <option value="4">4</option>
            <option value="5">5</option>
        </select><br>
        <label for="reviewText">Review:</label><br>
        <textarea id="reviewText" name="reviewText" rows="4" cols="50"></textarea><br>
        <input type="submit" value="Submit Review">
    </form>
</body>
</html>
