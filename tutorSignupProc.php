<?php
include("connect.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $firstname = isset($_POST["firstname"]) ? $_POST["firstname"] : "";
    $lastname = isset($_POST["lastname"]) ? $_POST["lastname"] : "";
    $email = isset($_POST["email"]) ? $_POST["email"] : "";
    $phone = isset($_POST["phone"]) ? $_POST["phone"] : "";
    $studentid = isset($_POST["studentid"]) ? $_POST["studentid"] : "";

    // Add validation and sanitization here (for example, if the account already exists)

    $password = password_hash(isset($_POST["password"]) ? $_POST["password"] : "", PASSWORD_DEFAULT);

    // Insert data into the users table
    $sql = "INSERT INTO users (FirstName, LastName, Email, PasswordHash, Role) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);

    // Set the role for the tutor
    $role = 'Tutor';

    $stmt->bind_param("sssss", $firstname, $lastname, $email, $password, $role);
    $stmt->execute();

    if ($stmt->affected_rows == 1) {
        // Get the UserId of the newly inserted user
        $userId = $stmt->insert_id;

        // Insert data into the tutors table
        $sqlTutor = "INSERT INTO tutors (UserId) VALUES (?)";
        $stmtTutor = $conn->prepare($sqlTutor);
        $stmtTutor->bind_param("i", $userId);
        $stmtTutor->execute();

        if ($stmtTutor->affected_rows == 1) {
            // Redirect to a success page or handle success
            header("Location: Login.php");
        } else {
            // Redirect to an error page or handle errors
            header("Location: tutorSignup.php");
        }

        $stmtTutor->close();
    } else {
        // Redirect to an error page or handle errors
        header("Location: tutorSignup.php");
    }

    $stmt->close();
    $conn->close();

    exit();
}
?>

