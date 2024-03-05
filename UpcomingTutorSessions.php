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



$sql = "select * from session_request where tutorId = ?";
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
    <title>Upcoming Tutor Sessions</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1>Upcoming Sessions</h1>
    <?php if (count($sessions) > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>Session ID</th>
                    <th>Date and Time</th>
                    <th>Course Name</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($sessions as $session): ?>
                    <tr>
                        <td><?= htmlspecialchars($session['RequestId']) ?></td>
                        <td><?= htmlspecialchars($session['RequestDate']) ?></td>
                        <td><?= htmlspecialchars($session['StartTime']) ?></td>
                        <td><?= htmlspecialchars($session['Message']) ?></td>
                        <td><?= htmlspecialchars($session['Status']) ?></td>
                        <td>
                            <a href="Procs\UpcomingTutorSessionProc.php?sessionId=<?= $session['RequestId'] ?>&action=accept">Accept</a> | 
                            <a href="Procs\UpcomingTutorSessionProc.php?sessionId=<?= $session['RequestId'] ?>&action=deny">Deny</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No upcoming sessions.</p>
    <?php endif; ?>
</body>
</html>