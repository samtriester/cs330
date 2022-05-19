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
    $filename = $_POST['fileToOpen'];
    $full_path = sprintf("/home/%s/%s", $_SESSION['loggedInUser'], $filename);

	// Now we need to get the MIME type (e.g., image/jpeg).  PHP provides a neat little interface to do this called finfo.
	$finfo = new finfo(FILEINFO_MIME_TYPE);
	$mime = $finfo->file($full_path);

	// Finally, set the Content-Type header to the MIME type of the file, and display the file.
	// header("Content-Type: ".$mime);
	// header('content-disposition: inline; filename="'.$filename.'";');
	// readfile($full_path);
// header('Content-Description: File Transfer');
// header('Content-Type: '.$mime);
// header('Content-Disposition: attachment; filename="'.basename($file).'"' );
// header('Expires: 0');
// header('Cache-Control: must-revalidate');
// header('Pragma: public');
// header('Content-Length: ' . filesize($filename));
// readfile($full_path);
// exit;
if(file_exists($full_path)) {
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename='.basename($full_path));
    header('Content-Transfer-Encoding: binary');
    header('Expires: 0');
    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
    header('Pragma: public');
    header('Content-Length: ' . filesize($full_path));
    ob_clean();
    flush();
    readfile($full_path);
    exit;
}
?>

</body>
</html>
