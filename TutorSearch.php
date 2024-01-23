<!DOCTYPE html>
<!--
Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
Click nbfs://nbhost/SystemFileSystem/Templates/Project/PHP/PHPProject.php to edit this template
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title>Find a Tutor</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="styles.css">
    </head>
    <body>
            <!--nav bar-->
            <div id="container">
                <nav>
                <a href="#">Home</a>
                <a href="#">Find a Tutor</a>
                <a href="#">Sign In</a>
                <a href="#">Create an Account</a>
                </nav>
            </div>

        <!--wrapper fiv for main content-->
        <div class="wrapper">
        <main>
            <!--banner photo-->
            <img src="images/nbccLogo.png" class="nbccBanner" alt="" />

            <form id="tutorSearch" action="search_tutors.php" method="post">
            <label for="classDropdown">Class:</label>
            
            <select id="classDropdown" name="class">
            <option value="math">Math</option>
            <option value="english">English</option>
            <option value="science">Science</option>
            <!-- change this to pull classes from DB -->
            </select>

                <label for="keywords">Keywords:</label>
                <input type="text" id="keywords" name="keywords" placeholder="Enter keywords">

                <label for="rating">Rating:</label>
                <input type="number" id="rating" name="rating" min="1" max="5" placeholder="Enter rating (1-5)">

                <button type="submit">Search</button>

            </form>

            <?php
            // put your code here
            ?>
        </main>
</div>
        
</html>
