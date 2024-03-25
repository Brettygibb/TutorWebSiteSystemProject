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

// Fetch user details
$stmt = $conn->prepare("SELECT * FROM users WHERE UserID = ?");
$stmt->bind_param("i", $userid);
$stmt->execute();
$result = $stmt->get_result();
$userDetails = $result->fetch_assoc();

// Fetch tutor ID
$tutorStmt = $conn->prepare("SELECT TutorId FROM tutors WHERE UserId = ?");
$tutorStmt->bind_param("i", $userid);
$tutorStmt->execute();
$tutorResult = $tutorStmt->get_result();
if ($tutorRow = $tutorResult->fetch_assoc()) {
    // Correctly fetching and storing the TutorId in the session
    $_SESSION['tutorId'] = $tutorRow['TutorId'];
} else {
    echo "Tutor ID not found for user.";
    exit; // Or handle this scenario appropriately
}
$tutorStmt->close();
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
        <p>Tutor ID: <?php echo htmlspecialchars($_SESSION['tutorId']); ?></p> <!-- Displaying TutorId -->
        <?php if (!empty($userDetails['image'])): ?>
            <img src="<?php echo htmlspecialchars($userDetails['image']); ?>" alt="Profile Picture">
        <?php endif; ?>
        
    </section>
</body>
</html>