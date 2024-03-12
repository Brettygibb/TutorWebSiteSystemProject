<?php
class Sessions {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn; 
    }

    public function createSessionRequest($tutorId, $studentId, $firstName, $lastName, $email, $course, $date, $startTime, $endTime, $message, $status) {
        $sql = "INSERT INTO session_request (TutorId, StudentId, FirstName, LastName, Email, Course, RequestDate, StartTime, EndTime, Message, Status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);

        if ($stmt === false) {
            return false;
        }

        $stmt->bind_param("iisssssssss", $tutorId, $studentId, $firstName, $lastName, $email, $course, $date, $startTime, $endTime, $message, $status);
        
        $result = $stmt->execute();
        $stmt->close();
        
        return $result;
    }

    public function updateSessionRequest($requestId,$newStatus){
        $stmt = $this->conn->prepare("UPDATE session_request SET Status = ? WHERE RequestId = ? AND Status = 'Pending'");
        $stmt->bind_param("si", $newStatus, $requestId);
        if($stmt->execute()){
            echo "Session request updated successfully";
        } else {
            echo "Error: " . $stmt->error;
        }
        $stmt->close();
    }
    public function getSessionDetails($sessionId) {
        $stmt = $this->conn->prepare("SELECT * FROM session_request WHERE RequestId = ?");
        $stmt->bind_param("i", $sessionId);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
    
        if ($result->num_rows > 0) {
            return [
                'found' => true,
                'data' => $result->fetch_assoc() // Get the first row as an associative array
            ];
        } else {
            return [
                'found' => false,
                'data' => null
            ];
        }
    }
    

    public function deleteTutorAvailability($tutorId,$date,$startTime){
        $stmt = $this->conn->prepare("DELETE FROM tutor_availability WHERE TutorId = ? AND AvailableDate = ? AND StartTime = ?");
        if($stmt === false){
            return false;
        }
        $stmt->bind_param("iss", $tutorId, $date, $startTime);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public function createSession($tutorId,$studentId,$date,$startTime,$message){
        $sql = "INSERT INTO sessions (TutorId, StudentId, DateAndTime, StartTime, Notes) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        if($stmt === false){
            return false;
        }
        $stmt->bind_param("iisss", $tutorId, $studentId, $date, $startTime, $message);
        $result = $stmt->execute();
        $stmt->close();
        return $result;

    }

    public function updateSessionRequestStatus($requestId, $newStatus) {
        $sql = "UPDATE session_request SET Status = ? WHERE RequestId = ?";
        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            // Handle error here, e.g., throw an exception or return false
            echo "Error preparing statement: " . $this->conn->error;
            return false;
        }
        $stmt->bind_param("si", $newStatus, $requestId);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }
    

    public function beginTransaction() {
        $this->conn->begin_transaction();
    }
    
    public function commit() {
        $this->conn->commit();
    }
    
    public function rollback() {
        $this->conn->rollback();
    }
    

}