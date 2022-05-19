<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP Calculator Results</title>
</head>
<body>
    <p>
        <?php
        $num1 = (float) $_GET["firstNum"];
        $num2 = (float) $_GET["secondNum"];
        $operation = (int) $_GET["operation"];
		$total =0;
		switch ($operation) {
   		case 0:
        		$total = $num1*$num2;
        		echo htmlentities("$num1 * $num2 = $total");
				break;
   		case 1:
        		if($num2==0){
         		   echo htmlentities("Invalid input: divide by 0!");
        		}
        		else{
            		$total=$num1/$num2;
            		echo htmlentities("$num1 / $num2 = $total");
       		 }
				break;
    	case 2:
       			$total =$num1+$num2;
        		echo htmlentities("$num1 + $num2 = $total");
				break;
    	case 3:
        		$total = $num1 -$num2;
        		echo htmlentities("$num1 - $num2 = $total");
				
		}
		?>
		</p>
	</body>
</html>