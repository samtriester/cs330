<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>file table</title>
</head>
<body>
<table>
	<tr>
		<th>Image</th>
		<th>File</th>
        <th>Add Tag</th>
        <th>Open</th>
	</tr>
<?php
$files = $_POST['files'];
foreach($files as $file){
    $imgSrc = "";
    //get file extension and use to map image to filename
    switch(pathinfo($file,PATHINFO_EXTENSION)){
        case "jpeg":
            $imgSrc ="JPEG.png"
            break;
        case "txt":
            $imgSrc ="TXT.png"
            break;
        case "html":
            $imgSrc ="HTML.png"
            break;
        case "mov":
            $imgSrc ="MOV.png"
            break;
        case "docx":
        case "doc":
            $imgSrc ="DOC.jpg"
            break;
        case "xlsx":
        case "xls":
            $imgSrc ="XLS.png"
            break;
        default:
            $imgSrc ="FILE.png"
        
    }
    //make table line for image, filename, add tag, and open
	echo  "\t<tr>\n\t\t<td><img src=\""$imgSrc"\" alt=\"File Image\">\n\t\t<td>".htmlentities($file)."</td>\n\t\t<td><form name =\"input\" action=\"PHPFUNCTION\" method=\"POST\" >\n\t\t<input id=\"tag\" type=\"submit\" name=\"tag\" value=\""$file"\" />\n\t\t</form>\n\t\t<td><form name =\"input\" action=\"PHPFUNCTION\" method=\"POST\" >\n\t\t<input id=\"open\" type=\"submit\" name=\"open\" value=\""$file"\" />\n\t\t</form>\n\t</tr>\n";
}
//TODO make function for opening file and for adding tag

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
 
</table>
</body>
</html>