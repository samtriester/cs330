<!DOCTYPE html>
<html lang="en">
<head><title>FileServer User</title></head>
<body>
	<p>
         
	</p>

<?php

    session_start();

//function deleteFile($filename) {
	// PHP program to delete a file named gfg.txt 
	// using unlike() function 
	

	$file_pointer = "/home//" . $_SESSION['loggedInUser'] . "//" . $_POST["fileToDelete"]; 
	
	// Use unlink() function to delete a file 
	if (!unlink($file_pointer)) { 
		echo ("$file_pointer cannot be deleted due to an error"); 
	} 
	else { 
		echo ("$file_pointer has been deleted"); 
        header("Location: userHome.php"); 
	} 

    
//}

?>
</body>
</html>