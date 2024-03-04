<?php
session_start();
include 'Database.php';

$db = new Database($servername, $username, $password, $dbname);

$conn = $db->getConnection();

// Ensure the user ID is properly set in the session
$userid = isset($_SESSION['id']) ? $_SESSION['id'] : null;
if (!$userid) {
    // Redirect to login page or show an error if the user ID isn't set
    header("Location: login.php");
    exit;
}
$stmt = $conn->prepare("SELECT * FROM users WHERE UserID = ?");
$stmt->bind_param("i", $userid);
$stmt->execute();
$result = $stmt->get_result();
$userDetails = $result->fetch_assoc();
//get tutorid
$tutor = $conn->prepare("SELECT TutorId FROM tutors WHERE UserID = ?");
$tutor->bind_param("i", $userid);
$tutor->execute();
$tutorId = $tutor->get_result();
$tutorId = $tutorId->fetch_assoc();
$_SESSION['tutorId'] = $tutorId['TutorId'];
$_SESSION['userid'] = $userid;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tutor Dashboard</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <?php include 'Includes/TutorHeader.php'; ?>

    <section>
        <h2>Welcome to the Tutor Dashboard</h2>
        <!-- Users Info -->
        <p>First Name: <?php echo htmlspecialchars($userDetails['FirstName']); ?></p>
        <p>Last Name: <?php echo htmlspecialchars($userDetails['LastName']); ?></p>
        <p>Email: <?php echo htmlspecialchars($userDetails['Email']); ?></p>
        <?php if (!empty($userDetails['image'])): ?>
            <img src="<?php echo htmlspecialchars($userDetails['image']); ?>" alt="Profile Picture">
        <?php endif; ?>
        
    </section>
</body>
</html>
