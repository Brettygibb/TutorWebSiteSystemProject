<?php
session_start();
include 'Database.php';

$database = new Database($servername, $username, $password, $dbname);
$conn = $database->getConnection();

$tutorId = isset($_GET['tutorId']) ? intval($_GET['tutorId']) : 0;
$tutorProfile = null;

if ($tutorId > 0) {
    $sql = "SELECT u.UserId, u.FirstName, u.LastName, u.Email, up.academicBackground, up.expertise, up.achievements, up.bio 
            FROM tutors t
            INNER JOIN users u ON t.UserId = u.UserId
            LEFT JOIN users_profiles up ON u.UserId = up.UserId
            WHERE t.TutorId = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $tutorId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $tutorProfile = $result->fetch_assoc();
        }
        $stmt->close();
    }

    $reviewSql = "SELECT AVG(Rating) as AverageRating FROM reviews WHERE TutorId = ?";
    if ($stmt = $conn->prepare($reviewSql)) {
        $stmt->bind_param("i", $tutorId);
        $stmt->execute();
        $result = $stmt->get_result();
        if($row = $result->fetch_assoc()) {
            $averageRating = round($row['AverageRating'], 1);
        }
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Tutor Profile</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <?php include 'Includes/StudentHeader.php'; ?>
    <section>
        <?php if ($tutorProfile): ?>
            <h2>Tutor Profile</h2>
            <p>Name: <?php echo htmlspecialchars($tutorProfile['FirstName'] . ' ' . $tutorProfile['LastName']); ?></p>
            <p>Email: <?php echo htmlspecialchars($tutorProfile['Email']); ?></p>
            <p>Academic Background: <?php echo htmlspecialchars($tutorProfile['academicBackground']); ?></p>
            <p>Expertise: <?php echo htmlspecialchars($tutorProfile['expertise']); ?></p>
            <p>Achievements: <?php echo htmlspecialchars($tutorProfile['achievements']); ?></p>
            <p>Bio: <?php echo htmlspecialchars($tutorProfile['bio']); ?></p>
            <p>Average Rating: <?php echo $averageRating; ?></p>

            <form action="LeaveReview.php" method="post">
                <input type="hidden" name="tutorId" value="<?php echo $tutorId; ?>">
                <input type="hidden" name="studentId" value="<?php echo $_SESSION['studentId']; ?>">
                <input type="hidden" name="sessionId" value="<?php echo $_GET['sessionId']; ?>">
                <input type="submit" value="Leave a Review">
            </form
        <?php else: ?>
            <p>No tutor found.</p>
        <?php endif; ?>


    </section>
</body>
</html>
