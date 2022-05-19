<!DOCTYPE html>
<html lang="en">
<head><title>Home Page</title></head>
<body>
<b>Upload New Post:</b>
	<form action="postScript.php" method="POST" enctype="multipart/form-data">

		<br>
		<label for="title">
                Title
        </label>
		<input type="text" name="title" id="title">
		<br>
		<label for="link">
                Link
        </label>
		<input type="text" name="link" id="link">
		<br>
		<label for="body">
                Body
        </label>
		<input type="text" name="body" id="body">
		<br>
		<input type="hidden" name="token" value="<?php session_start(); echo htmlspecialchars($_SESSION['token']); ?>">
		<input type="submit" value="Post" name="submit">
	</form>
<?php
require 'database.php';


if(!isset($_SESSION['loggedInUser'])) {
	echo "You must login to post!";
	header("Location: loginPage.php");
}
	//$id = $_SESSION['loggedInUser'];

	//require 'database.php';

	// $stmt = $mysqli->prepare("select body, users.username from comments join users on (comments.user_id=users.user_id) where post_id=".htmlentities($id)." order by unique_id");
	// if(!$stmt){
	// 	printf("Query Prep Failed: %s\n", $mysqli->error);
	// 	exit;
	// }

	// $stmt->execute();

	// $result = $stmt->get_result();

	// echo "<ul>\n";
	// while($row = $result->fetch_assoc()){
	// 	printf("\t<li>%s %s</li>\n",
	// 		htmlspecialchars( $row["body"] ),
	// 		htmlspecialchars( $row["users.username"] )
	// 	);
	// }
	// echo "</ul>\n";

	// $stmt->close();
?>
</body>
</html>