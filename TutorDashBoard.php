<?php
session_start();
//include 'Connect.php';

include 'Database.php';

//Create a new instance of DB class 
$database= new Database($servername, $username, $password, $dbname);

//Get the database connection 
$conn= $database ->getConnection();

if(isset($_SESSION['id'])) {
    $userid = $_SESSION['id'];

    // Use stored procedure to get the user info
    $sql = "CALL GetUserByUserID(?)";
    $stmt = $conn->prepare($sql);
    if(!$stmt){
        echo "Error: ".$conn->error;
        exit();
    }
    $stmt->bind_param("i", $userid);
    $stmt->execute();
    $result = $stmt->get_result();
    if($result) {
        $row = $result->fetch_assoc();

        while($conn->more_results()&&$conn->next_result()){
            if($l_result = $conn->use_result()){
                while($l_row = $l_result->fetch_assoc()){}
            }
        }
        //need a stored procedure to get the tutor ID
        $sqlTutor = "SELECT TutorId FROM tutors WHERE UserId = ?";
        $stmtTutor = $conn->prepare($sqlTutor);
        if(!$stmtTutor){
            echo "Error fetching tutor ID: ".$conn->error;
            exit();
        }
        $stmtTutor->bind_param("i", $userid);
        $stmtTutor->execute();
        $resultTutor = $stmtTutor->get_result();
        if($resultTutor && $resultTutor->num_rows > 0) {
            $rowTutor = $resultTutor->fetch_assoc();
            $tutorId = $rowTutor['TutorId']; 

            $_SESSION['tutorID'] = $tutorId;// this might screw things up
        } else {
            echo "No tutor found for user ID: ".$userid;
        }
        $stmtTutor->close();
    } else {
        echo "No user found with ID: ".$userid;
    }
    $stmt->close();
    $conn->close();
} else {
    echo "User is not logged in.";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tutor Dashboard</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <?php include 'Includes/TutorHeader.php'; ?>

    <section>
        <h2>Welcome to the Tutor Dashboard</h2>
        <!-- Users Info -->
        <p>First Name: <?php echo $row['FirstName']; ?></p>
        <p>Last Name: <?php echo $row['LastName']; ?></p>
        <p>Email: <?php echo $row['Email']; ?></p>
        <p>Tutor ID: <?php echo $tutorId; ?></p> <!-- Display TutorId -->
        <?php if (!empty($row['image'])): ?>
            <img src="<?php echo $row['image']; ?>" alt="Profile Picture">
        <?php endif; ?>
    </section>
</body>
</html>
