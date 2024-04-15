<?php
require_once 'User.php'; // Include the parent class definition

class Admin extends User {
    private $adminId;

    public function __construct($firstName= null, $lastName= null, $email= null, $password= null, $adminId = null) {
        parent::__construct($firstName, $lastName, $email, $password);
        $this->adminId = $adminId;
    }
    

    public function getAdminId() {
        return $this->adminId;
    }

    public function setAdminId($adminId) {
        $this->adminId = $adminId;
    }

    public function insertAdminDatabase($conn) {
        // Prepare the SQL statement
        $sql_insert_user = "CALL InsertUserOptimized(?, ?, ?, ?)";
        $stmt = $conn->prepare($sql_insert_user);
        
        // Bind parameters
        $stmt->bind_param("ssss", $this->getFirstName(), $this->getLastName(), $this->getEmail(), $this->getPassword());

        // Execute the statement
        $stmt->execute();

        // Get the ID of the inserted user
        $user_id = $conn->insert_id;

        // Close the statement
        $stmt->close();

        // Insert admin into admins table
        $sql_insert_admin = "CALL InsertAdmin(?)";
        $stmt = $conn->prepare($sql_insert_admin);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();

        // Get the autogenerated adminId from the admins table
        $this->adminId = $conn->insert_id;

        // Close the statement
        $stmt->close();
    }
    
    
    public function obtainWhoWantBecomeTutor($conn) {
        $sql = "CALL GetWhoWantBecomeTutor()";
        $result = mysqli_query($conn, $sql);
        if (!$result) {
            echo "Error fetching tutor requests: " . mysqli_error($conn);
            exit();
        }
        return $result;
    }
    
    
    public function processBecomeTutor($action, $studentId, $conn) {
        if ($action === 'approve') {
            $status = 'Approved';

            // SQL to update status
            $updateSql = "CALL UpdateTutorRequestStatus(?, ?)";
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
            $userIdSql = "CALL GetUserIdByStudentId(?)";
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
            $insertTutorSql = "CALL InsertTutor(?)";
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

            // Add a notification for tutor request approval
            $notificationMessage = "Tutor request was approved.";
            $insertNotificationSql = "INSERT INTO notifications (user_id, message, is_read) VALUES (?, ?, 0)";
            $stmt = mysqli_prepare($conn, $insertNotificationSql);
            if (!$stmt) {
                echo "Error preparing statement: " . mysqli_error($conn);
                exit();
            }
            mysqli_stmt_bind_param($stmt, "is", $userId, $notificationMessage);
            if (!mysqli_stmt_execute($stmt)) {
                echo "Error adding notification: " . mysqli_stmt_error($stmt);
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

            // Add a notification for tutor request denial
            $notificationMessage = "Tutor request was denied.";
            $insertNotificationSql = "INSERT INTO notifications (user_id, message, is_read) VALUES (?, ?, 0)";
            $stmt = mysqli_prepare($conn, $insertNotificationSql);
            if (!$stmt) {
                echo "Error preparing statement: " . mysqli_error($conn);
                exit();
            }
            mysqli_stmt_bind_param($stmt, "is", $userId, $notificationMessage);
            if (!mysqli_stmt_execute($stmt)) {
                echo "Error adding notification: " . mysqli_stmt_error($stmt);
                exit();
            }
            mysqli_stmt_close($stmt);
        } else {
            echo "Invalid action!";
            exit();
        }

        header("Location: ReviewBecomingTutorRequests.php"); // Redirect back to review page after processing
        exit();
    }
    
    
    public function processRequestedCourses($requestId, $courseId, $status, $conn) {
        // Check if the status is 'Approved' and insert into tutor_courses table
        if ($status === 'Approved') {
            // Update the status in the requests table
            $updateStatusSql = "UPDATE requests SET Status = 'Approved' WHERE TutorId = ? AND CourseId = ?";
            $stmt = $conn->prepare($updateStatusSql);
            $stmt->bind_param("ii", $requestId, $courseId);
            $stmt->execute();
            $stmt->close();            

            $insertSql = "CALL InsertTutorCourse(?, ?)";
            $stmt = $conn->prepare($insertSql);
            $stmt->bind_param("is", $requestId, $courseId);
            $stmt->execute();
            $stmt->close();

            // Fetch the userId of the tutor associated with the request
            $userIdSql = "SELECT UserId FROM tutors WHERE TutorId = ?";
            $stmt = $conn->prepare($userIdSql);
            $stmt->bind_param("i", $requestId);
            $stmt->execute();
            $stmt->bind_result($userId);
            $stmt->fetch();
            $stmt->close();

            // Fetch the course name
            $getCourseNameSql = "SELECT CourseName FROM courses WHERE CourseId = ?";
            $stmt = $conn->prepare($getCourseNameSql);
            $stmt->bind_param("i", $courseId);
            $stmt->execute();
            $stmt->bind_result($courseName);
            $stmt->fetch();
            $stmt->close();

            // Add a notification for course request approval
            $notificationMessage = "Course request for $courseName was approved.";
            $insertNotificationSql = "INSERT INTO notifications (user_id, message, is_read) VALUES (?, ?, 0)";
            $stmt = $conn->prepare($insertNotificationSql);
            $stmt->bind_param("is", $userId, $notificationMessage);
            $stmt->execute();
            $stmt->close();

        } elseif ($status === 'Denied') {
            // Update the status in the requests table
            $updateStatusSql = "UPDATE requests SET Status = 'Denied' WHERE TutorId = ? AND CourseId = ?";
            $stmt = $conn->prepare($updateStatusSql);
            $stmt->bind_param("ii", $requestId, $courseId);
            $stmt->execute();
            $stmt->close(); 

            // Fetch the userId of the tutor associated with the request
            $userIdSql = "SELECT UserId FROM tutors WHERE TutorId = ?";
            $stmt = $conn->prepare($userIdSql);
            $stmt->bind_param("i", $requestId);
            $stmt->execute();
            $stmt->bind_result($userId);
            $stmt->fetch();
            $stmt->close();

            // Fetch the course name
            $getCourseNameSql = "SELECT CourseName FROM courses WHERE CourseId = ?";
            $stmt = $conn->prepare($getCourseNameSql);
            $stmt->bind_param("i", $courseId);
            $stmt->execute();
            $stmt->bind_result($courseName);
            $stmt->fetch();
            $stmt->close();

            // Add a notification for course request denial
            $notificationMessage = "Course request for $courseName was denied.";
            $insertNotificationSql = "INSERT INTO notifications (user_id, message, is_read) VALUES (?, ?, 0)";
            $stmt = $conn->prepare($insertNotificationSql);
            $stmt->bind_param("is", $userId, $notificationMessage);
            $stmt->execute();
            $stmt->close();
        }
    }

    
}
?>
