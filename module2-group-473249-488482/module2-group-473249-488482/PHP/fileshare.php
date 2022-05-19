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
    if(isset($_POST['submit'])){
        $filename = basename($_FILES['fileToShare']['name']);

        echo "filename: " . basename($_FILES['fileToShare']['name']);
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
        //CHECKOUT FULLPATH thats the issue

        $full_path = sprintf("/home/%s/%s",  $_POST["userToShare"], basename($_FILES["fileToShare"]["name"]));
        //echo "Full path: " . $full_path;
        if( move_uploaded_file($_FILES['fileToShare']['tmp_name'], $full_path) ){
            echo "successful file upload!";
            //indicate succesful file upload and naviagte back to userHome.
        	header("Location: userHome.php");
        	
        }else{
        	echo " file upload failure/invalid username";
        	//header("Location: upload_failure.html");
        	exit;
        }

}
else {
    echo "no post variableset";
}
?>

</body>
</html>