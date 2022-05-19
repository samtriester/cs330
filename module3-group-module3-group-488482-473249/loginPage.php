<!DOCTYPE html>
<html lang="en">
<head><title>Home Page</title></head>
<body>
<form action= "loginScript.php" method="POST">
    <h3> Login now </h3>
		<label for="username">
                Username
        </label>
		<input type="text" name="username" id="username">
        <br>
        <label for="password">
                Password
        </label>
		<input type="text" name="password" id="password">
        <br>
	<input type="submit" value="Login" name="submit">
</form>
<?php
session_start();
//Check if coming from bad login attempt
if(($_SESSION['badLogin'])){
    echo "Bad Login attempt, try again";
}
?>
<form action="homePage.php">
    <h3> Continue as Guest </h3>
	<input type="submit" value="Continue" name="submit">
</form>

<br>

<form action= "registerScript.php" method="POST">
    <h3> New User? Register Now </h3>
		<label for="username1">
                Username
        </label>
		<input type="text" name="username" id="username1">
        <br>
        <label for="password1">
                New Password
        </label>
		<input type="text" name="password" id="password1">
        <br>
	<input type="submit" value="Register" name="submit">
</form>
<?php
//check if coming from bad register attempt
if(isset($_SESSION['badRegister'])) {
    if($_SESSION['badRegister']){
        echo "User already exists or invalid username, please try again";
    }
    else if($_SESSION['badRegister'] == False) {
        echo "Succesful registration, please login with new account";
    }
}
?>

</body>
</html>