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

            <form id="tutorSearch" action="TutorSearch_proc.php" method="post">
            <!--<label for="classDropdown">Class:</label>
            
            <select id="classDropdown" name="class">
            <option value="math">Java</option>
            <option value="english">PHP</option>
            <option value="1">1</option>
            change this to pull classes from DB 
            </select>-->

                <label for="search">Name or Subject</label>
                <input type="text" id="search" name="search" placeholder="Enter search">

                <!--<label for="tutorId">tutor id</label>
                <input type="number" id="tutorId" name="tutorId" min="1" max="5" placeholder="Enter rating (1-5)">
                -->
                <button type="submit">Search</button>

            </form>

            <?php
            // put your code here
            include("connect.php");
            ?>
        </main>
</div>
        
</html>
