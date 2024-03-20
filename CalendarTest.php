<?php
include 'Calendar.php';

?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>session Calendar</title>
		
		<link href="Calendar.css" rel="stylesheet" type="text/css">
	</head>
	<?php
	$calendar = new SimpleCalendar();
	$calendar->addSession("PHP", "2024-03-13", "blue");
	$calendar->addSession("Java", "2024-03-20", "green");
	echo $calendar->render();
	?>
</html>
