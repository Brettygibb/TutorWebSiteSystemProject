<?php
session_start();
include 'Database.php';

$db = new Database($servername, $username, $password, $dbname);
$conn = $db->getConnection();

if(!isset($_SESSION['tutorId'])){
    header("Location: Login.php");
    exit;
}
$tutorId = $_SESSION['tutorId'];

// Adjust the SQL query to fetch all sessions for the tutor
$sql = "SELECT * FROM sessions WHERE TutorId = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $tutorId);
$stmt->execute();
$result = $stmt->get_result();

$sessions = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmed Tutor Sessions</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<?php include 'Includes/TutorHeader.php'; ?>
    <h1>Confirmed Tutor Sessions</h1>
    <?php if (count($sessions) > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>Session ID</th>
                    <th>Student ID</th>
                    <th>Date and Time</th>
                    <th>Start Time</th>
                    <th>Notes</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($sessions as $session): ?>
                    <tr>
                        <td><?= htmlspecialchars($session['SessionId']) ?></td> 
                        <td><?= htmlspecialchars($session['StudentId']) ?></td>
                        <td><?= htmlspecialchars($session['DateAndTime']) ?></td>
                        <td><?= htmlspecialchars($session['StartTime']) ?></td>
                        <td><?= htmlspecialchars($session['Notes']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No confirmed sessions.</p>
    <?php endif; ?>
</body>
</html>
