<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Find a Tutor</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <script>
    $(document).ready(function() {
        $('#search').on('input', function() {
            var searchData = $(this).val().trim();

            if (searchData.length > 0) {
                // Send an AJAX request to the processing page
                $.ajax({
                    type: 'POST',
                    url: 'TutorSearch_proc.php',
                    data: { search: searchData },
                    success: function(response) {

                        console.log(response);

                        // Clear previous search results
                        $('#searchResults').empty();


                        console.log(response.length);
                        
                        if (response.length > 0) {
                            // Display the search results
                            var table = '<table>';
                            table += '<tr><td colspan="2"><strong>First Name</strong></td>';
                            table += '<td colspan="2"><strong>Last Name</strong></td>';
                            table += '<td colspan="2"><strong>Course Name</strong></td>';
                            table += '<td colspan="2"><strong>Availability</strong></td></tr>';

                            
                            
                            $.each(response, function(index, pair) {

                               var tutor = pair[0];
                               var course = pair[1];


                                table += '<tr>';
                                table += '<td colspan="2">' + tutor.firstName + '</td>';
                                table += '<td colspan="2">' + tutor.lastName + '</td>';
                                table += '<td colspan="2">' + course.courseName + '</td>';
                                table += '<td colspan="2">'+'<a href=ViewAllSessions.php?Id=' + tutor.tutorId +'&Course=' + course.courseId + '>View Sessions</a></td>';
                                table += '</tr>';
                            });
                            
                            table += '</table>';
                            $('#searchResults').html(table);
                        } else {
                            // Display message for no results
                            $('#searchResults').html('<p>No results found.</p>');
                        }
                    },
                    error: function(response) {
                        console.log(response);
                    }


                });
            } else {
                // Clear search results if search input is empty
                $('#searchResults').empty();
            }
        });
    });
    </script>
</head>
<body>
    <!--nav bar-->
    <div id="container">
        <nav>
            <a href="index.php">Home</a>
            <a href="TutorSearch.php">Find a Tutor</a>
            <a href="Login.php">Sign In</a>
            <a href="studentSignUp.php">Create an Account</a>
        </nav>
    </div>

    <!--wrapper for main content-->
    <div class="wrapper">
        <main>
            <!--banner photo-->
            <img src="images/nbccLogo.png" class="nbccBanner" alt="" />

            <form id="tutorSearch" method="post" action="#">
                <label for="search">Name or Subject</label>
                <input type="text" id="search" name="search" placeholder="Enter search">
                <button type="button">Search</button>
            </form><BR><BR>

            <!-- Container to display search results -->
            <div id="searchResults"></div>

            
        </main>
    </div>
</body>
</html>
