<header>
        <h1>Student Dashboard</h1>
        <nav>
            <ul class="horizontal-nav">
                <li><a href="StudentDashBoard.php">Home</a></li>
                <li><a href="NotificationsStudent.php">Notifications</a></li>
                
               <?php
                // Check if the user is logged in
                if (isset($_SESSION['id'])) {
                    $userId = $_SESSION['id'];
                    // Fetch unread notifications for the current user
                    $sql = "SELECT * FROM notifications WHERE user_id = ? AND is_read = 0";
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

                
                <li><a href="TutorSearch.php">Search Tutors</a></li>
                <li><a href="BecomeTutor.php">Become a Tutor</a></li>
                <li><a href="SwitchTutor.php">Tutor View</a></li>
                <li><a href="StudentEditProfile.php">Edit Profile</a></li>
                <li><a href="Procs/LogoutProc.php">Logout</a></li>
            </ul>
        </nav>
</header>