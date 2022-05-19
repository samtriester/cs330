<!DOCTYPE html>
<html lang="en">
<head><title>FileServer User</title></head>
<body>
	<p>
        You have been logged out. 
	</p>
    <?php
     session_start(); 
     session_destroy();
     header("Location: loginPage.php"); 
     ?>
</body>
</html>