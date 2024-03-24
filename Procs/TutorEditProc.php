<?php
session_start();
include '../Database.php';

$database = new Database($servername, $username, $password, $dbname);
$conn = $database->getConnection();

if (!isset($_SESSION['id'])) {
    header("Location: ../Login.php");
    exit;
}

$userID = $_SESSION['id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $first_name = htmlspecialchars($_POST['FirstName']);
    $last_name = htmlspecialchars($_POST['LastName']);
    $email = htmlspecialchars($_POST['email']);
    $academic_background = htmlspecialchars($_POST['academic_background']);
    $expertise = htmlspecialchars($_POST['expertise']);
    $achievements = htmlspecialchars($_POST['achievements']);
    $bio = htmlspecialchars($_POST['bio']);

    // Start transaction
    $conn->begin_transaction();

    try {
        // Update user details
        $userSql = "UPDATE users SET FirstName = ?, LastName = ?, Email = ? WHERE UserId = ?";
        $userStmt = $conn->prepare($userSql);
        $userStmt->bind_param("sssi", $first_name, $last_name, $email, $userID);
        $userStmt->execute();

        // Check if the user already has a profile
        $checkQuery = "SELECT * FROM users_profiles WHERE UserId = ?";
        $checkStmt = $conn->prepare($checkQuery);
        $checkStmt->bind_param("i", $userID);
        $checkStmt->execute();
        $result = $checkStmt->get_result();

        if ($result->num_rows > 0) {
            // User already has a profile, so update it
            $profileSql = "UPDATE users_profiles SET AcademicBackground = ?, Expertise = ?, Achievements = ?, Bio = ? WHERE UserId = ?";
        } else {
            // No profile exists, insert a new one
            $profileSql = "INSERT INTO users_profiles (UserId, AcademicBackground, Expertise, Achievements, Bio) VALUES (?, ?, ?, ?, ?)";
        }

        $profileStmt = $conn->prepare($profileSql);
        if ($result->num_rows > 0) {
            $profileStmt->bind_param("ssssi", $academic_background, $expertise, $achievements, $bio, $userID);
        } else {
            $profileStmt->bind_param("issss", $userID, $academic_background, $expertise, $achievements, $bio);
        }
        $profileStmt->execute();

        // Commit transaction
        $conn->commit();

        header("Location: ../TutorDashboard.php?profile=success");
        exit;
    } catch (Exception $e) {
        $conn->rollback();
        header("Location: ../TutorEditProfile.php?profile=error");
        exit;
    }
}

mysqli_close($conn);
?>
