<!DOCTYPE html>
<html lang="en">
<head><title>Home Page</title></head>
<body>
<?php
session_start();
require 'database.php';

$body = "";
if(isset($_POST['id'])) {
    //CHeck that valid CSRF tokens are passed
    if(!hash_equals($_SESSION['token'], $_POST['token'])){
        die("Request forgery detected");
    }
    //Get the id of the post being edited
	$post_id = $_POST['id'];
    
    //Begin Query
	$stmt = $mysqli->prepare("select * from comments where unique_id=?");
	if(!$stmt){
		printf("Query Prep Failed: %s\n", $mysqli->error);
		exit;
	}
    $stmt->bind_param('i', $post_id);
	$stmt->execute();
	$result = $stmt->get_result();

	echo "<ul>\n";
	while($row = $result->fetch_assoc()){
        $body = $row["body"];
	}
	echo "</ul>\n";

	$stmt->close();
    }
    else {
        echo "post not set";
    }
?>
<b>Edit Comment:</b>
	<form action="postScript.php" method="POST" enctype="multipart/form-data">
		<label for="body">
                Body
        </label>
		<input type="text" value="<?php echo htmlspecialchars($body); ?>" name="body" id="body">
		<br>
        <input type="hidden" value="True" name="isEditComment">
		<input type="hidden" name="token" value="<?php session_start(); echo htmlspecialchars($_SESSION['token']); ?>">

        <input type="hidden" value="<?php echo htmlspecialchars($post_id); ?>" name="id">
		<input type="submit" value="Post" name="submit">
	</form>
</body>
</html>