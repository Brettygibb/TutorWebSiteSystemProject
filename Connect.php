<?php
$database="nbcctutordb";
$user = "root";
$pass = "";
$host = "localhost";

$conn = mysqli_connect($host, $user, $pass, $database);
if (!$conn) {
    die("Unable to connect to the database server " . mysqli_connect_error());
}

?>