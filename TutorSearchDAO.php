<?php
require_once 'Database.php';
require_once 'tutor.php'; // Include the Tutor class definition
require_once 'course.php'; 
require_once 'review.php'; 

function searchTutors($searchQuery) {
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

        // Prepare and execute the stored procedure
        $stmt = $conn->prepare("CALL SearchTutors(?)");
        $stmt->bind_param("s", $searchQuery);
        $stmt->execute();
        $result = $stmt->get_result();

        $tutors = [];

        if ($result->num_rows > 0) {
            // Fetch data from the database and populate Tutor objects
            while ($row = $result->fetch_assoc()) {

                // Create a Tutor object and add it to the $tutors array
                $tutor = new Tutor($row['FirstName'], $row['LastName'], $row['TutorId'], $row['Rating']);
                $course = new Course($row['CourseId'], $row['CourseName']);
               // $review = new Review($row['Rating']);
                

                $pair = [$tutor, $course];


                $tutors[] = $pair;

                
            }
        }

        // Close the statement
        $stmt->close();

        // Close the database connection
        $database->closeConnection();

        return $tutors;
    } catch (Exception $ex) {
        // Log or handle the exception
        return [];
    }
}
?>
