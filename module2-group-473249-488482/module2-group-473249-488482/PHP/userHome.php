<!DOCTYPE html>
<head><title>FileServer User</title></head>
<body>
	
	<?php session_start();
	echo "Hi, ".$_SESSION["loggedInUser"]; ?>
	<p>

	</p>
	<br>
	<br>
	<b>Upload File:</b>
	<form action="upload.php" method="POST" enctype="multipart/form-data">
		Select File to upload:
		<input type="file" name="fileToUpload" id="fileToUpload">
		<input type="submit" value="Upload File" name="submit">
	</form>
	<br>
	<br>
	<b>Share File from Upload:</b>
	<form action="fileshare.php" method="POST" enctype="multipart/form-data">
		Select File to upload: 
		<input type="file" name="fileToShare" id="fileToShare">
		Type User to Share With:
		<input type="text" name="userToShare" id="userToShare">
		<input type="submit" value="Share File" name="submit">
	</form>
	<br>
	<br>
	<table>
		<tr>
			<th>Image</th>
			<th>File</th>
			<th>Share(with Username)</th>
			<th>Delete</th>
			<th>Open</th>
		</tr>
		<?php getAllFiles(); ?>
	</table>
	<p>
		<a href="./logout.php"> logout</a>
	</p>
	<!-- <form action="delete.php" method="POST" >
		<input type="input" name="fileToDelete" id="fileToDelete">
		<input type="submit" value="Delete File" name="submit">
	</form> -->
<?php



//this will contain all user files after getAllFiles() is called
$user_files = array();

function deleteFile($filename) {
	// PHP program to delete a file named gfg.txt 
	// using unlike() function 
	
	$file_pointer = "/home//" . $_SESSION['loggedInUser'] . "//" . $filename; 
	
	// Use unlink() function to delete a file 
	if (!unlink($file_pointer)) { 
		echo ("$file_pointer cannot be deleted due to an error"); 
	} 
	else { 
		echo ("$file_pointer has been deleted"); 
	} 
	

}


//Retrieves all files under logged in user directory
function getAllFiles() {
	$dir = "/home//" . $_SESSION['loggedInUser'] . "//";
	//$dir = "/home/ada/";
	//print_r("dir: " . $dir);
	$user_files = scandir($dir);
	//print_r($_SESSION['loggedInUser']);
	//TODO remove .. and . file from the list of files
	// for($i=0; $i < count($user_files); $i++) {
	// 	echo "filename:" . $user_files[$i] . "<br>";
	// }

	//e

	foreach($user_files as $file){
		//echo "substring: " . substr($file, 0, 1);
		if(substr($file, 0, 1) === ".") {
				unset($user_files[array_search($file,$user_files)]);
		}
	}
	foreach($user_files as $file){
		$imgSrc = "";
		//get file extension and use to map image to filename
		switch(pathinfo($file,PATHINFO_EXTENSION)){
			case "jpeg":
				$imgSrc ="JPEG.png";
				break;
			case "txt":
				$imgSrc ="TXT.png";
				break;
			case "html":
				$imgSrc ="HTML.png";
				break;
			case "mov":
				$imgSrc ="MOV.png";
				break;
			case "docx":
			case "doc":
				$imgSrc ="DOC.jpg";
				break;
			case "xlsx":
			case "xls":
				$imgSrc ="XLS.png";
				break;
			default:
				$imgSrc ="FILE.png";
			
		}
		//make table line for image, filename, fileshare, open and delete
		echo  "\t<tr>\n\t\t<td><img src=\"".$imgSrc."\" alt=\"File Image\"></td>
		\n\t\t<td>".htmlentities($file)."</td>
		\n\t\t<td><form action=\"libraryshare.php\" method=\"POST\" >
			\n\t\t<input id=\"fileshare\" type=\"text\" name=\"user\">
			\n\t\t<input id=\"fileshare\" type=\"submit\" name=\"sharable\" value=\"".htmlentities($file)."\" />\n\t\t</form></td>
		\n\t\t<td><form action=\"delete.php\" method=\"POST\" >
			\n\t\t<input id=\"delete\" type=\"submit\" name=\"fileToDelete\" value=\"".htmlentities($file)."\" />\n\t\t</form></td>
		
		\n\t\t<td><form action=\"open.php\" method=\"POST\" >
			\n\t\t<input id=\"open\" type=\"submit\" name=\"fileToOpen\" value=\"".htmlentities($file)."\" />\n\t\t</form></td>
		
		\n\t</tr>\n";
	}
}

//displayFile("adaTest.txt");

// $full_path = sprintf("/srv/uploads/%s/%s", $username, $filename);
function displayFile($filename) {

	$full_path = sprintf("/home/%s/%s", $_SESSION['loggedInUser'], $filename);

	// Now we need to get the MIME type (e.g., image/jpeg).  PHP provides a neat little interface to do this called finfo.
	$finfo = new finfo(FILEINFO_MIME_TYPE);
	$mime = $finfo->file($full_path);

	// Finally, set the Content-Type header to the MIME type of the file, and display the file.
	header("Content-Type: ".$mime);
	header('content-disposition: inline; filename="'.$filename.'";');
	readfile($full_path);
}

// function addFile() {
// 	// Get the filename and make sure it is valid
// 	$filename = basename($_FILES['uploadedfile']['name']);
// 	if( !preg_match('/^[\w_\.\-]+$/', $fileName) ){
// 		echo "Invalid filename";
// 		exit;
// 	}
// 	//upload file to users location dir
// 	$full_path = sprintf("/home/%s/%s",  $_SESSION['loggedInUser'], $filename);

// 	if( move_uploaded_file($_FILES['uploadedfile']['tmp_name'], $full_path) ){
// 		echo "successful file upload";
// 		//header("Location: upload_success.html");
// 		exit;
// 	}else{
// 		echo "file upload failure";
// 		//header("Location: upload_failure.html");
// 		exit;
// 	}
// }

// function logout() {
// 	session_destroy();
// }
function openFile($filePath){
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mime = $finfo->file($filePath);
    $filename = pathinfo($filePath, PATHINFO_BASENAME);
    // Finally, set the Content-Type header to the MIME type of the file, and display the file.
    header("Content-Type: ".$mime);
    header('content-disposition: inline; filename="'.$filename.'";');
    readfile($filePath);
}
?>

</body>
</html>