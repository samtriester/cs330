<!DOCTYPE html>
<html lang="en">
<head><title>FileServer Upload</title></head>
<body>

    <p>
        <a href="userHome.php">Go Back Home</a>
    </p>
<?php
    //start_session();
    session_start();
    
        $filename = $_POST['sharable'];

        echo "filename: " . $filename;
        // regex to make sure spaces don't throw a whitespace error
        $oldname =$filename;
        $filename = preg_replace('/\s+/', '_', $filename);
        if( !preg_match('/^[\w_\.\-]+$/', $filename) ){
            echo "\nInvalid filename: " . $filename . ", attempt";
            exit;
        }
        // reversing regex
        $filename=$oldname;
        //upload file to users location dir
        if( !preg_match('/^[\w_\-]+$/', $_POST['user']) ){
            echo "Invalid username";
            exit;
        }
        $full_path = sprintf("/home/%s/%s",  $_POST["user"], $filename);
        //echo "Full path: " . $full_path;
        $old_path =sprintf("/home/%s/%s",  $_SESSION['loggedInUser'], $filename); 
        if(copy($old_path, $full_path) ){
            echo "successful file upload!";
            //indicate succesful file upload and naviagte back to userHome.
        	header("Location: userHome.php");
        	
        }else{
        	echo " file upload failure/invalid username";
        	//header("Location: upload_failure.html");
        	exit;
        }


?>

</body>
</html>