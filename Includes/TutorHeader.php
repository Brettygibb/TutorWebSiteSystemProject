<header>
        <h1>Tutor Dashboard</h1>
        <nav>
            <ul class="horizontal-nav">
                <li><a href="TutorDashBoard.php">Home</a></li>
               <?php
                // Check if the user is logged in
                if (isset($_SESSION['id'])) {
                    $userId = $_SESSION['id'];
                    // Fetch unread notifications for the current user
                    $sql = "CALL Notifications(?)";
                    $stmt = $conn->prepare($sql);
                    if (!$stmt) {
                        echo "Error: " . $conn->error;
                        exit();
                    }
                    $stmt->bind_param("i", $userId);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    $unreadNotificationsCount = $result->num_rows;
                    $stmt->close();

                    // If there are unread notifications, apply a CSS style to change the color of the "Notifications" link
                    if ($unreadNotificationsCount > 0) {
                        echo '<li><a href="NotificationsTutor.php" style="color: red;">Notifications (' . $unreadNotificationsCount . ')</a></li>';
                    } else {
                        echo '<li><a href="NotificationsTutor.php">Notifications</a></li>';
                    }
                } else {
                    // If the user is not logged in, display the link without any special styling
                    echo '<li><a href="NotificationsTutor.php">Notifications</a></li>';
                }
                ?>

                 
                
                
                
                
                
                
                
                
                
                <li><a href="TutorSubscribedCourses.php">Subscribed Courses</a></li>
                <li><a href="TutorSubscribeCourse.php">Subscribe a New Course</a></li>
                <li><a href="SwitchStudent.php">Student View</a></li>
                <li><a href="TutorEditProfile.php">Edit Profile</a></li>
                <li><a href="UpcomingTutorSessions.php">Upcoming Sessions - To Confirm</a></li>
                <li><a href="UpcomingTutorSessionsConfirmed.php">Upcoming Sessions - Confirmed</a></li>
                <li><a href="TutorAvailability.php">Enter Availability</a></li>
                <li><a href="Procs/LogoutProc.php">Logout</a></li>
            </ul>
        </nav>
    </header>