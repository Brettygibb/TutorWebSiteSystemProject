<?php


class Review {
    protected $reviewId;
    protected $sessionId;
    protected $tutorId;
    protected $studentId;
    protected $rating;
    protected $feedback;
    
    public function __construct($reviewId = null, $sessionId = null, $tutorId = null, $studentId = null, $rating = null, $feedback = null) {
        $this->reviewId = $reviewId;
        $this->sessionId = $sessionId;
        $this->tutorId = $tutorId;
        $this->studentId = $studentId;
        $this->rating = $rating;
        $this->feedback = $feedback;
    }
    
    // Getter methods
    public function getReviewId() {
        return $this->reviewId;
    }
    
    public function getSessionId() {
        return $this->sessionId;
    }
    
    public function getTutorId() {
        return $this->tutorId;
    }
    
    public function getStudentId() {
        return $this->studentId;
    }
    
    public function getRating() {
        return $this->rating;
    }
    
    public function getFeedback() {
        return $this->feedback;
    }
    
    // Setter methods if needed
    
    // Example setter for rating
    public function setRating($rating) {
        $this->rating = $rating;
    }

    // Method to calculate the average rating for a tutor
    /* public static function getAverageRatingForTutor($tutorId) {
        try {
            // Add database connection details
            $servername = "localhost";
            $username = "root";
            $password = "";
            $dbname = "nbcctutordb";

            // Create a new instance of the Database class
            $database = new Database($servername, $username, $password, $dbname);

            // Get the database connection
            $conn = $database->getConnection();

            // Prepare and execute the SQL query to get average rating
            $stmt = $conn->prepare("SELECT AVG(rating) AS average_rating FROM reviews WHERE tutorId = ?");
            $stmt->bind_param("i", $tutorId);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $averageRating = $row['average_rating'];
                return $averageRating;
            } else {
                return 0; // Return 0 if no reviews found for the tutor
            }

            // Close the statement
            $stmt->close();

            // Close the database connection
            $database->closeConnection();
        } catch (Exception $ex) {
            // Log or handle the exception
            return 0; // Return 0 in case of any error
        }
    } */
}
