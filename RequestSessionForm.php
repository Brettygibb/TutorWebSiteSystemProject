<?php
    session_start();
    if (!isset($_SESSION['studentId'])) {
        header('Location: StudentLogin.php');
        exit();
    }
    if(isset($_GET['course'])){
        echo "Course ID is set";
    }
    else{
        echo "Course ID is not set";
    }

    echo "<pre>";
print_r($_GET);
echo "</pre>";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Request Session</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1>Request Session</h1>
    <form action="Procs/RequestSessionFormProc.php" method="post">
        <!-- Ensure these hidden fields are correctly assigned -->
        <input type="hidden" name="tutorId" value="<?php echo isset($_GET['tutorId']) ? htmlspecialchars($_GET['tutorId']) : ''; ?>">
        <input type="hidden" name="date" value="<?php echo isset($_GET['date']) ? htmlspecialchars($_GET['date']) : ''; ?>">
        <input type="hidden" name="startTime" value="<?php echo isset($_GET['startTime']) ? htmlspecialchars($_GET['startTime']) : ''; ?>">
        <input type="hidden" name="endTime" value="<?php echo isset($_GET['endTime']) ? htmlspecialchars($_GET['endTime']) : ''; ?>">
        <input type="hidden" name="studentId" value="<?php echo isset($_SESSION['studentId']) ? htmlspecialchars($_SESSION['studentId']) : ''; ?>">
        <!-- Correct Hidden Input for CourseId -->
        <input type="hidden" name="courseId" value="<?php echo isset($_GET['course']) ? htmlspecialchars($_GET['course']) : ''; ?>">


        
        <label for="FirstName">First Name:</label>
        <input type="text" id="FirstName" name="FirstName" required><br>

        <label for="LastName">Last Name:</label>
        <input type="text" id="LastName" name="LastName" required><br>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required><br>

        <label for="Subject">Subject:</label>
        <input type="text" id="Subject" name="Subject" required><br>

        <label for="message">Message:</label>
        <textarea id="message" name="message" required></textarea><br>
        
        <button type="submit">Send Request</button>
    </form>
    <h1>Student ID: <?php echo $_SESSION['studentId']; ?></h1>
<h1>Course ID: <?php echo $_GET['course']; ?></h1>
</body>
</html>
