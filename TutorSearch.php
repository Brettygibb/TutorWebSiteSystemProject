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

                            //testing
                            console.log(response);

                            

                            // Display the search results
                            var table = '<table>';
                            table += '<thead>';
                            table += '<tr><th>First Name<br><button class="filterBtn" data-column="0">Filter</button></th>';
                            table += '<th>Last Name<br><button class="filterBtn" data-column="1">Filter</button></th>';
                            table += '<th>Course Name<br><button class="filterBtn" data-column="2">Filter</button></th>';
                            table += '<th>Reviews<br><button class="filterBtn" data-column="2">Filter</button></th>';

                            table += '<th>Availability</th>';
                            
                            table += '</thead>';
                            
                            table += '<tbody>';
                           
                            

                            $.each(response, function(index, pair) {
                               var tutor = pair[0];
                               var course = pair[1];
                               var review = pair[2];

                                table += '<tr>';
                                table += '<td>' + tutor.firstName + '</td>';
                                table += '<td>' + tutor.lastName + '</td>';
                                table += '<td>' + course.courseName + '</td>';
                                table += '<td>' + tutor.rating + '</td>';
                                table += '<td>' + '<a href=ViewAllSessions.php?Id=' + tutor.tutorId +'&Course=' + course.courseId + '>View Sessions</a>' + '</td>';
                                table += '</tr>';
                            });
                            
                            table += '</tbody>';
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

        // Add event listener for column filtering buttons
        $(document).on('click', '.filterBtn', function() {
            var column = $(this).data('column');
            sortTable(column);
        });

        function sortTable(column) {
            var table, rows, switching, i, x, y, shouldSwitch;
            table = document.querySelector('table');
            switching = true;
            
            while (switching) {
                switching = false;
                rows = table.rows;
                
                for (i = 1; i < (rows.length - 1); i++) {
                    shouldSwitch = false;
                    x = rows[i].getElementsByTagName("TD")[column];
                    y = rows[i + 1].getElementsByTagName("TD")[column];
                    
                    if (x.innerHTML.toLowerCase() > y.innerHTML.toLowerCase()) {
                        shouldSwitch = true;
                        break;
                    }
                }
                if (shouldSwitch) {
                    rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
                    switching = true;
                }
            }
        }
    });
    </script>
</head>
<body>
    <!--wrapper for main content-->
    <div class="wrapper">
        <main>
            <!--banner photo-->
            <img src="images/nbccLogo.png" class="nbccBanner" alt="" />

            <form id="tutorSearch" method="post" action="#">
                <label for="search">Enter a subject or name</label>
                <input type="text" id="search" name="search" placeholder="Enter search">
            </form><br><br>








            <!-- Container to display search results -->
            <div id="searchResults"></div>
        </main>
    </div>
    
</body>
</html>
