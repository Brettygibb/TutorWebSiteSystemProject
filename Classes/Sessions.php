<?php

class Sessions {
    private $db;

    // Constructor with dependency injection
    public function __construct($db) {
        $this->db = $db;
    }

    // Create a new session request
    public function createSessionRequest($tutorId,$studentId,$courseId,$firstName,$LastName,$email,$subject,$date,$startTime,$endTime,$message,$status) {
        $query = "INSERT INTO session_request (TutorId, StudentId, CourseId,FirstName,LastName,Email,Subject, RequestDate, StartTime, EndTime, Message, Status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $this->db->prepare($query);
        if (!$stmt) {
            // Handle error
            echo "Error preparing statement: " . $this->db->error;
            return false;
        }

        $stmt->bind_param("iissssssssss", $tutorId, $studentId, $courseId, $firstName, $LastName, $email, $subject, $date, $startTime, $endTime, $message, $status);
        $result = $stmt->execute();
        $stmt->close();

        return $result;
    }

    // Update the status of a session request
    public function updateSessionRequestStatus($requestId, $newStatus) {
        $query = "UPDATE session_request SET Status = ? WHERE RequestId = ?";

        $stmt = $this->db->prepare($query);
        if (!$stmt) {
            // Handle error
            echo "Error preparing statement: " . $this->db->error;
            return false;
        }

        $stmt->bind_param("si", $newStatus, $requestId);
        $result = $stmt->execute();
        $stmt->close();

        return $result;
    }

    // Retrieve details of a specific session request
    public function getSessionDetails($requestId) {
        $query = "SELECT * FROM session_request WHERE RequestId = ?";

        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $requestId);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        return $result;
    }
    public function deleteTutorAvailability($tutorId, $date, $startTime) {
        $query = "DELETE FROM tutor_availability WHERE TutorId = ? AND AvailableDate = ? AND StartTime = ?";
        
        $stmt = $this->db->prepare($query);
        if (!$stmt) {
            // Handle error
            echo "Error preparing statement: " . $this->db->error;
            return false;
        }

        $stmt->bind_param("iss", $tutorId, $date, $startTime);
        $result = $stmt->execute();
        $stmt->close();

        return $result;
    }
    public function createSession($tutorId, $studentId,$courseId, $date, $startTime, $message) {
        $query = "INSERT INTO sessions (TutorId, StudentId, CourseId,DateANdTime, StartTime, Notes) VALUES (?, ?, ?, ?, ?, ?)";
        
        $stmt = $this->db->prepare($query);
        if (!$stmt) {
            // Handle error
            echo "Error preparing statement: " . $this->db->error;
            return false;
        }

        $stmt->bind_param("iiisss", $tutorId,$courseId, $studentId, $date, $startTime, $message);
        $result = $stmt->execute();
        $stmt->close();

        return $result;
    }
}
