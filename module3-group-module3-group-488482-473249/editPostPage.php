<!DOCTYPE html>
<html lang="en">
<head><title>Home Page</title></head>
<body>
<?php
session_start();
require 'database.php';

$body = "";
$title = "";
$link = "";
if(isset($_POST['id'])) {
    //echo "post id is:";
    //echo $_POST['id'];
    if(!hash_equals($_SESSION['token'], $_POST['token'])){
        die("Request forgery detected");
    }
	$post_id = $_POST['id'];

	require 'database.php';
    
	$stmt = $mysqli->prepare("select * from posts where unique_id=?");
	if(!$stmt){
		printf("Query Prep Failed: %s\n", $mysqli->error);
		exit;
	}
    $stmt->bind_param('s', $post_id);

	$stmt->execute();

	$result = $stmt->get_result();

	echo "<ul>\n";
	while($row = $result->fetch_assoc()){
		// printf("\t<li>%s %s</li>\n",
		// 	htmlspecialchars( $row["body"] ),
		// 	htmlspecialchars( $row["title"] )
		// );
        $body = $row["body"];
        $title = $row["title"];
        $link = $row["link"];
	}
	echo "</ul>\n";

	$stmt->close();
    }
    else {
        echo "post not set";
    }
?>
<b>Edit Post:</b>
	<form action="postScript.php" method="POST" enctype="multipart/form-data">

		<br>
		<label for="title">
                Title
        </label>
		<input type="text" value="<?php echo htmlspecialchars($title); ?>" name="title" id="title">
		<br>
		<label for="link">
                Link
        </label>
		<input type="text" value="<?php echo htmlspecialchars($link); ?>" name="link" id="link">
		<br>
		<label for="body">
                Body
        </label>
		<input type="text" value="<?php echo htmlspecialchars($body); ?>" name="body" id="body">
		<br>
        <input type="hidden" value="True" name="isEdit">
		<input type="hidden" name="token" value="<?php session_start(); echo htmlspecialchars($_SESSION['token']); ?>">
        <input type="hidden" value="<?php echo htmlspecialchars($post_id); ?>" name="id">
		<input type="submit" value="Post" name="submit">
	</form>
</body>
</html>