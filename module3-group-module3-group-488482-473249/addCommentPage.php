<!DOCTYPE html>
<html lang="en">
<head><title>Home Page</title></head>
<body>
<b>Upload New Comment:</b>

	<form action="postScript.php" method="POST" enctype="multipart/form-data">
		<label for="body">
                Body
        </label>
		<input type="text" name="body" id="body">
		<br>
        <input type="hidden" name="isComment" value="True">
		
		<input type="hidden" name="token" value="<?php session_start(); echo htmlspecialchars($_SESSION['token']); ?>">
        <input type="hidden" value="<?php echo htmlspecialchars($_POST['id']); ?>" name="id">
		<input type="submit" value="Post" name="submit">
	</form>
<?php

require 'database.php';

//Check for loggd in user
if(!isset($_SESSION['loggedInUser'])) {
	echo "You must login to add comments!";
	header("Location: loginPage.php");
}

?>
</body>
</html>