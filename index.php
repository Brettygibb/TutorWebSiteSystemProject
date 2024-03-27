<!DOCTYPE html>
<!--
Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
Click nbfs://nbhost/SystemFileSystem/Templates/Project/PHP/PHPProject.php to edit this template
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title>NBCC Tutoring</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="styles.css">
    </head>
    <body>
            <!--nav bar-->
            <div id="container">
            <nav class="navbar">
    <div class="nav-logo">
        <img src="images/nbccLogo.png" alt="NBCC Logo" onclick="window.location.href='index.php'">
       <!-- <a href="TutorSearch.php" class="nav-item nav-item--find-tutor">Find a Tutor</a> -->
    </div>
    <div class="auth-buttons">
        <a href="Login.php" class="nav-item auth-button">Sign In</a>
        <a href="studentSignup.php" class="nav-item auth-button">Create an Account</a>
    </div>
</nav>
            </div>

        <!--wrapper fiv for main content-->
        <div class="wrapper">
        <div class="wrapper-content">
        <main>          

            <h1>Welcome to the NBCC Tutoring Hub!</h1>

            <p id="indexParagraph">Where academic excellence meets personalized support! 
            Whether you're a student seeking a helping hand on your educational journey or a passionate individual eager to share your knowledge, 
            our tutoring service is here to connect you. At NBCC, we believe in the power of collaboration and the transformative impact of one-on-one learning experiences.
            <br>
            <br>
            Students, sign up today to unlock the door to academic success with our dedicated and qualified tutors, ready to guide you through your courses and boost your confidence.
            Tutors, join our community of educators, share your expertise, and make a positive impact on the academic lives of your peers. Together, 
            let's embark on a journey of knowledge, growth, and achievement. 
            <br>
            <br>
            Welcome to a place where learning knows no bounds – Welcome to the NBCC Tutoring Hub!
            </p>
            </div>
            <img src="images/students-on-steps.png" class="nbccPhoto" alt="" />
            </div>
            <?php
            // put your code here
            ?>
        </main>
</div>

        <!-- Footer content -->
        <footer class="footer">
            <p>Tutoring System Project &copy; <?php echo date('Y'); ?></p>
            <br>
            <p> New Brunswick Community College</p>
        </footer>

    </body>
</html>
