<?php
session_start();
include '../Database.php';

$database = new Database($servername, $username, $password, $dbname);
$conn = $database->getConnection();

if (isset($_SESSION['id'])) {
    $userID = $_SESSION['id'];

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Sanitize input data
        $academic_background = htmlspecialchars($_POST['academic_background']);
        $expertise = htmlspecialchars($_POST['expertise']);
        $achievements = htmlspecialchars($_POST['achievements']);
        $bio = htmlspecialchars($_POST['bio']);

        $uploadOkay = 0;
        $imageData = null;
        
        if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
            // Read the binary file content
            $imageData = file_get_contents($_FILES['image']['tmp_name']);
            $imageType = $_FILES['image']['type'];

            if (substr($imageType, 0, 5) == "image") {
                $uploadOkay = 1;
            } else {
                echo "File is not an image.";
                exit; // Or handle error as appropriate
            }
        } else {
            echo "No file uploaded or an error occurred.";
            exit; // Or handle error as appropriate
        }

        if ($uploadOkay == 1) {
            // Check if the user already has a profile
            $checkQuery = "SELECT * FROM users_profiles WHERE UserId = ?";
            $checkStmt = $conn->prepare($checkQuery);
            $checkStmt->bind_param("i", $userID);
            $checkStmt->execute();
            $result = $checkStmt->get_result();

            if ($result->num_rows > 0) {
                // User already has a profile, so update it
                $sql = "UPDATE users_profiles SET academicBackground = ?, expertise = ?, achievements = ?, bio = ?, profilePicture = ? WHERE UserId = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ssssbi", $academic_background, $expertise, $achievements, $bio, $imageData, $userID);
            } else {
                // No profile exists for this user, insert a new one
                $sql = "INSERT INTO users_profiles (UserId, academicBackground, expertise, achievements, bio, profilePicture) VALUES (?, ?, ?, ?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("issssb", $userID, $academic_background, $expertise, $achievements, $bio, $imageData);
            }

            if ($stmt->execute()) {
                header("Location: ../TutorDashBoard.php?profile=success");
                exit;
            } else {
                // Error handling
                header("Location: ../TutorEditProfile.php?profile=error");
                exit;
            }
        }
    } else {
        header("Location: ../TutorEditProfile.php?profile=error");
        exit;
    }
} else {
    header("Location: ../TutorEditProfile.php?profile=error");
    exit;
}

mysqli_close($conn);
?>