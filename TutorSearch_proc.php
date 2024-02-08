<?php
// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Add database connection details

    //move to its own file for security reasons? ask Bruce
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "nbcctutordb";

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare and execute the stored procedure
    $searchSubmit = $_POST["search"];
    $stmt = $conn->prepare("CALL SearchTutors(?)");
    $stmt->bind_param("s", $searchSubmit);
    $stmt->execute();
    $result = $stmt->get_result();

    $rows = [];

    if ($result->num_rows > 0) {
        // Store the results in an array
        while ($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }
    }

    // Close the database connection
    $conn->close();
    $stmt->close();

    // Return the results as JSON
    header('Content-Type: application/json');
    echo json_encode($rows);
}
?>
