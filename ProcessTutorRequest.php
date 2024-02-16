<?php
session_start();
include 'Connect.php';

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && isset($_POST['student_id'])) {
    $action = $_POST['action'];
    $studentId = $_POST['student_id'];

    // Update the status of the tutor request
    if ($action === 'approve') {
        $status = 'Approved';

        // SQL to update status
        $updateSql = "UPDATE becometutor_requests SET Status = ? WHERE StudentId = ?";
        $stmt = mysqli_prepare($conn, $updateSql);
        if (!$stmt) {
            echo "Error preparing statement: " . mysqli_error($conn);
            exit();
        }
        mysqli_stmt_bind_param($stmt, "si", $status, $studentId);
        if (!mysqli_stmt_execute($stmt)) {
            echo "Error updating tutor request: " . mysqli_stmt_error($stmt);
            exit();
        }
        mysqli_stmt_close($stmt);

        // Get the UserId associated with the StudentId
        $userIdSql = "SELECT UserId FROM students WHERE StudentId = ?";
        $stmt = mysqli_prepare($conn, $userIdSql);
        if (!$stmt) {
            echo "Error preparing statement: " . mysqli_error($conn);
            exit();
        }
        mysqli_stmt_bind_param($stmt, "i", $studentId);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $userId);
        mysqli_stmt_fetch($stmt);
        mysqli_stmt_close($stmt);

        // Insert into tutors table
        $insertTutorSql = "INSERT INTO tutors (UserId) VALUES (?)";
        $stmt = mysqli_prepare($conn, $insertTutorSql);
        if (!$stmt) {
            echo "Error preparing statement: " . mysqli_error($conn);
            exit();
        }
        mysqli_stmt_bind_param($stmt, "i", $userId);
        if (!mysqli_stmt_execute($stmt)) {
            echo "Error adding tutor record: " . mysqli_stmt_error($stmt);
            exit();
        }
        mysqli_stmt_close($stmt);

        // Add tutor role to the user in user_roles table
        $insertUserRoleSql = "INSERT INTO user_roles (UserId, RoleId) VALUES (?, (SELECT RoleId FROM roles WHERE RoleName = 'Tutor'))";
        $stmt = mysqli_prepare($conn, $insertUserRoleSql);
        if (!$stmt) {
            echo "Error preparing statement: " . mysqli_error($conn);
            exit();
        }
        mysqli_stmt_bind_param($stmt, "i", $userId);
        if (!mysqli_stmt_execute($stmt)) {
            echo "Error adding tutor role to the user: " . mysqli_stmt_error($stmt);
            exit();
        }
        mysqli_stmt_close($stmt);
    } elseif ($action === 'deny') {
        $status = 'Denied';

        // SQL to update status
        $updateSql = "UPDATE becometutor_requests SET Status = ? WHERE StudentId = ?";
        $stmt = mysqli_prepare($conn, $updateSql);
        if (!$stmt) {
            echo "Error preparing statement: " . mysqli_error($conn);
            exit();
        }
        mysqli_stmt_bind_param($stmt, "si", $status, $studentId);
        if (!mysqli_stmt_execute($stmt)) {
            echo "Error updating tutor request: " . mysqli_stmt_error($stmt);
            exit();
        }
        mysqli_stmt_close($stmt);
    } else {
        echo "Invalid action!";
        exit();
    }

    header("Location: ReviewBecomingTutorRequests.php"); // Redirect back to review page after processing
    exit();
} else {
    echo "Invalid request!";
    exit();
}
?>

