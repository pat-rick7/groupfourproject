<!DOCTYPE html>
<html>
<head>
    <title>My page</title>
</head>
<body>
<?php

// This is a comment

### This is also a single line comment

/* multiline comment strted.
	I want to write another comment
Now i am going to end the comment */

    $name = "Sai Lakkaraju";
    echo "My favourite lecturer is " . $name;
	echo "<br> <br> Now another way of adding the variables to the strings <br> <br>";
	
	## You can embed variables in double quoted strings.
	echo "My favourite lecturer is $name ";
?>
</body>
</html>