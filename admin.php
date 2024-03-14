<?php
require_once 'User.php'; // Include the parent class definition

class Admin extends User {
    private $adminId;

    public function __construct($firstName, $lastName, $email, $password) {
        parent::__construct($firstName, $lastName, $email, $password);
    }

    public function getAdminId() {
        return $this->adminId;
    }

    public function setAdminId($adminId) {
        $this->adminId = $adminId;
    }

    public function insertAdminDatabase($conn) {
        // Prepare the SQL statement
        $sql_insert_user = "INSERT INTO users (FirstName, LastName, Email, PasswordHash)
                            VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql_insert_user);
        
        // Bind parameters
        $stmt->bind_param("ssss", $this->getFirstName(), $this->getLastName(), $this->getEmail(), $this->getPassword());

        // Execute the statement
        $stmt->execute();

        // Close the statement
        $stmt->close();

        // Get the ID of the inserted user
        $user_id = $conn->insert_id;

        // Insert admin into admins table
        $sql_insert_admin = "INSERT INTO admins (UserId)
                             VALUES (?)";
        $stmt = $conn->prepare($sql_insert_admin);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $stmt->close();
    }
}
?>
