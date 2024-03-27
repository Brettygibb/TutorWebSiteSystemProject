<?php
session_start();
include '../Database.php'; // Adjust the path as needed

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize POST data
    $sessionId = intval($_POST['sessionId']);
    $tutorId = intval($_POST['tutorId']);
    $studentId = intval($_POST['studentId']);
    $rating = intval($_POST['rating']);
    $reviewText = htmlspecialchars($_POST['reviewText']);

    // Database connection
    $db = new Database($servername, $username, $password, $dbname); // Add parameters as required by your constructor
    $conn = $db->getConnection();

    // Insert review into database
    $stmt = $conn->prepare("INSERT INTO reviews (SessionId, TutorId, StudentId, Rating, Comment) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("iiiss", $sessionId, $tutorId, $studentId, $rating, $reviewText);

    if ($stmt->execute()) {
        $updateStmt = $conn->prepare("UPDATE sessions SET Status = 'Reviewed' WHERE SessionId = ?");
        $updateStmt->bind_param("i", $sessionId);
        $updateStmt->execute();
        header("Location: ../StudentDashBoard.php?success=true");
    } else {
        header("Location: ../StudentDashBoard.php?success=false");
    }
    exit();
} else {
    die("Invalid request method.");
}
?>
