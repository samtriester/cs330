<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP Calculator</title>
</head>
<body>
    <p>Pick an operation and type in your first and second numbers.</p>
    <form name ="input" action="calc.php" method="GET" >
        <label for="firstNum">First Number:</label>
        <input type="number" name="firstNum" id="firstNum" step="any" required>
        <label for="secondNum">Second Number:</label>
        <input type="number" name="secondNum" id="secondNum" step="any" required>
        <br>
        <input type="radio" name="operation" value="0" required>
        <label>x</label><br>
        <input type="radio" name="operation" value="1">
        <label>/</label><br>
        <input type="radio" name="operation" value="2">
        <label>+</label><br>
        <input type="radio" name="operation" value="3">
        <label>-</label><br>
        <br>
        <input type="submit" value="Submit">
    </form>
</body>
</html>