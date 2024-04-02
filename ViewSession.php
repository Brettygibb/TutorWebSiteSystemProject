<?php 
session_start();
//include 'Connect.php';

include 'Database.php';

//Create a new instance of DB class 
$database= new Database($servername, $username, $password, $dbname);

//Get the database connection 
$conn= $database ->getConnection();

$userid = isset($_SESSION['id']) ? $_SESSION['id'] : null;
if (!$userid) {
    // Redirect to login or show an error because the user is not logged in
    header("Location: Login.php");
    exit();
}
if ($stmt = $conn->prepare("CALL GetUserByUserId(?)")) {
    $stmt->bind_param("i", $userid);
    $stmt->execute();
    $result = $stmt->get_result();
    $userRow = $result->fetch_assoc();
    $stmt->close();
}

$tutorId = isset($_GET['tutorId']) ? $_GET['tutorId'] : 0;


$sessions = [];
if ($tutorId > 0) {
    if ($stmt = $conn->prepare("CALL GetSessionsByTutor(?)")) {
        $stmt->bind_param("i", $tutorId);
        $stmt->execute();
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            $sessions[] = $row;
        }
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student Dashboard</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <?php include 'Includes/StudentHeader.php'; ?>
    <section>
        <h2>Welcome to the Student Dashboard</h2>
        <p>First Name: <?php echo htmlspecialchars($userRow['FirstName'] ?? 'N/A'); ?></p>
        <p>Last Name: <?php echo htmlspecialchars($userRow['LastName'] ?? 'N/A'); ?></p>
        <p>Email: <?php echo htmlspecialchars($userRow['Email'] ?? 'N/A'); ?></p>
    </section>
    
    <section>
        <h2>Available Sessions</h2>
        <?php foreach ($sessions as $session): ?>
            <p>Tutor First Name: <?php echo htmlspecialchars($session['TutorFirstName']); ?></p> 
            <p>Tutor Last Name: <?php echo htmlspecialchars($session['TutorLastName']); ?></p> 
            <p>Email: <?php echo htmlspecialchars($session['TutorEmail']); ?></p> 

            <p>Course: <?php echo htmlspecialchars($session['CourseName']); ?></p> 
            <p>Date and Time: <?php echo htmlspecialchars($session['SessionDate']); ?></p> 
            <p>Start Time: <?php echo date('h:i A', strtotime($session['StartTime'])); ?></p>
            <hr>
        <?php endforeach; ?>
    </section>
</body>
</html>
