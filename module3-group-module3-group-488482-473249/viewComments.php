<!DOCTYPE html>
<html lang="en">
<head><title>Home Page</title></head>
<body>
	
<?php
echo "Comments:";
session_start();
require 'database.php';
	$id = $mysqli->real_escape_string(htmlentities($_POST['id']));


	$stmt = $mysqli->prepare("select body, users.username as username, comments.user_id as user_id, unique_id from comments join users on (comments.user_id=users.user_id) where post_id=".htmlentities($id)." order by unique_id desc");
	if(!$stmt){
		printf("Query Prep Failed: %s\n", $mysqli->error);
		exit;
	}

	$stmt->execute();

	$result = $stmt->get_result();
	if($result->num_rows == 0) {
		echo "\n\nno comments yet!";
	}
	else {
		echo "<ul>\n";
		while($row = $result->fetch_assoc()){
			printf("\t<li>%s --%s</li>\n",
				htmlspecialchars( $row["body"] ),
				htmlspecialchars( $row["username"] )
			);
			if(isset($_SESSION['loggedInUser'])){
				$csrf=$_SESSION['token'];
				$editButton = "<form action=\"editCommentPage.php\" method=\"POST\" >
				<input type=\"hidden\" name=\"token\" value=\"".$csrf."\" />
				\n\t\t<button id=\"edit\" name=\"id\" type=\"submit\" value=\"".htmlentities($row['unique_id'])."\">EDIT</button></form>";
				$deleteButton = "<form action=\"deletePostScript.php\" method=\"POST\" >
				<input type=\"hidden\" name=\"token\" value=\"".$csrf."\" />
				<input type=\"hidden\" name=\"isComment\" value=True />
				\n\t\t<button id=\"edit\" name=\"id\" type=\"submit\" value=\"".htmlentities($row['unique_id'])."\">DELETE</button></form>";

				if($row['user_id']==$_SESSION['loggedInUser']or $_SESSION['loggedInUser']==112){
					echo $editButton;
					echo $deleteButton;
				}
			}
		}
	}
	echo "</ul>\n";

	$stmt->close();
?>
</body>
</html>