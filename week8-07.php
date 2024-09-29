<!DOCTYPE html>
<html>
<head>
    <title>Week 8-06 Arrays</title>
</head>
<body>

<?php
	$students = array(
						'WIN01'=>'Aadesh',
						'WIN02'=>'Anmol',
						'WIN03'=>'Sarita',
						'WIN04'=>'Utsab'
					);
	
	// find the name using Key	
	echo "<br>".$students['WIN03']."<br>";
	
	/* for loop to print the values
	
	foreach($students as $value){
		echo "<br>". $value;
	}*/
?>

</body>
</html>