<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Search Results</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

<div class="wrapper">
    <main>

        <?php
        // Check if the form is submitted
        if ($_SERVER["REQUEST_METHOD"] == "POST") {

            $searchSubmit = $_POST["search"];

            // Add database connection details
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

            // TODO: Adjust the query based on database structure
            //need a stored procedure to get the user info
            $sql = "SELECT tutors.*, users.FirstName, users.LastName, courses.CourseName 
        FROM tutors 
        JOIN users ON tutors.UserId = users.UserId
        JOIN courses ON tutors.CourseId = courses.CourseId
        WHERE TRIM(LOWER(users.FirstName)) LIKE TRIM(LOWER('%$searchSubmit%')) 
           OR TRIM(LOWER(users.LastName)) LIKE TRIM(LOWER('%$searchSubmit%')) 
           OR TRIM(LOWER(courses.CourseName)) LIKE TRIM(LOWER('%$searchSubmit%'))";


$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Store the results in an array
    $rows = [];
    while ($row = $result->fetch_assoc()) {
        $rows[] = $row;
    }

    // Display the results
    echo "<table>";
    echo "<tr><td colspan='2'><strong>First Name</strong></td>";
    echo "<td colspan='2'><strong>Last Name</strong></td>";
    echo "<td colspan='2'><strong>Course Name</strong></td>";
    echo "<td colspan='2'><strong>Availability</strong></td></tr>";
    foreach ($rows as $row) {
        echo "<tr><td colspan='2'>" . $row["FirstName"] . "</td>";
        echo "<td colspan='2'>" . $row["LastName"] . "</td>";
        echo "<td colspan='2'>" . $row["CourseName"] . "</td>";
        echo "<td colspan='2'><button>View Sessions</button></td></tr>";
    }
    echo "</table>";
} else {
    echo "No results found.";
}

// Close the database connection
$conn->close();

        }
        ?>
    </main>
</div>

</body>
</html>
