<!DOCTYPE html>
<html>
<head>
    <title>Week 8-05 Arrays</title>
</head>
<body>

<?php
	$students = array("Aadesh","Anmol","Sarita","Utsab");
	// Append a new student
	$students[4]="Ajay";
	// or
	$students[] = "Felix";
	
	echo "<br>".$students[0]."<br>".$students[1]."<br>".$students[2].
	"<br>".$students[3]."<br>".$students[4]."<br>".$students[5];
	
	/* for loop to print the values
	
	foreach($students as $value){
		echo "<br>". $value;
	}*/
?>

</body>
</html>