<!DOCTYPE html>
<html>
<head>
    <title>Week 8-04 Multiplication</title>
</head>
<body>

<?php
	function DoMultiplication($x,$y=5) {
			return $x * $y;
	}
	echo "Lets pass only one variable 6. The output will be ". DoMultiplication(6);
	// Another example
	echo"<br>";
	echo "Lets pass two variables 6 and 8. 6X8=". DoMultiplication(6,8);
?>

</body>
</html>