<?php
require_once 'TutorSearchDAO.php';

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Handle search request
    $searchSubmit = $_POST["search"];
    $searchResults = searchTutors($searchSubmit);

    // Return the results as JSON
    header('Content-Type: application/json');
    echo json_encode($searchResults);


}
?>
