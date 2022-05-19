<!DOCTYPE html>
<html lang="en">
<head><title>Home Page</title></head>
<body>

<?php 
session_start();
$newPostButton = "<form action=\"postPage.php\" method=\"POST\">
                    \n\t<input type=\"submit\" value=\"Create New Post\" name=\"submit\">
                    </form>\n";
$logoutButton = "<form action=\"logoutScript.php\" method=\"POST\">
                    \n\t<input type=\"submit\" value=\"Logout\" name=\"submit\">
                </form>\n";
$loginButton = "<form action=\"loginPage.php\" method=\"POST\">
                    \n\t<input type=\"submit\" value=\"Login\" name=\"submit\">
                </form>\n";
$followerTabButton ="<form action=\"followers.php\" method=\"POST\">
\n\t<input type=\"submit\" value=\"FOLLOWER TAB\" name=\"submit\">
</form>\n";
//similar to below
//create buttons and echo when applicable
if(isset($_SESSION['loggedInUser'])) {
    $csrf=$_SESSION['token'];
    echo $newPostButton;
    echo $logoutButton;
    echo $followerTabButton;
}
else {
    echo $loginButton;
}    
?>
<br>
<form action= "search.php" method="POST">
    <input type="text" name="searchString" id="searchString">
	<input type="submit" value="SEARCH" name="submit">
</form>
<br>
<?php
require 'database.php';

$stmt = $mysqli->prepare("select users.username as username, title, link, body, unique_id, posts.user_id as user_id from posts join users on (posts.user_id=users.user_id) order by unique_id desc");
if(!$stmt){
	printf("Query Prep Failed: %s\n", $mysqli->error);
	exit;
}

$stmt->execute();

$result = $stmt->get_result();
//Create html for all items with links
// foreach($news as $item){
//     $image = "";
//     if(htmlentities($item['image'])!=null){
//         $image = "<img src=\"".htmlentities($item['image'])."alt text=\"".htmlentities($item['title']).">";
//     }
// echo "<li><a href=\"".htmlentities($item['link'])."\">".htmlentities($item['title'])."</a>
// <br>".$image.
// "<br>"
// .htmlentities($item['body'])."</li>"
// } 
echo "<ul>\n";
while($item = $result->fetch_assoc()){
    //create form buttons for each post
    $deleteButton="<form action=\"deletePostScript.php\" method=\"POST\" >
    <input type=\"hidden\" name=\"token\" value=\"".$csrf."\" />
    \n\t\t<button name=\"id\" type=\"submit\" value=\"".htmlentities($item['unique_id'])."\">DELETE</button></form>";
    $commentButton="<form action=\"addCommentPage.php\" method=\"POST\" >
    <input type=\"hidden\" name=\"token\" value=\"".$csrf."\" />
    \n\t\t<button name=\"id\" type=\"submit\" value=\"".htmlentities($item['unique_id'])."\">COMMENT</button></form>";
    $editButton ="<form action=\"editPostPage.php\" method=\"POST\" >
    <input type=\"hidden\" name=\"token\" value=\"".$csrf."\" />
    \n\t\t<button name=\"id\" type=\"submit\" value=\"".htmlentities($item['unique_id'])."\">EDIT</button></form>";
    $followButton="<form action=\"follow.php\" method=\"POST\" >
    <input type=\"hidden\" name=\"token\" value=\"".$csrf."\" />
    \n\t\t<button name=\"id\" type=\"submit\" value=\"".htmlentities($item['user_id'])."\">FOLLOW ".htmlentities($item['username'])."</button></form>";
echo "<li><a href=\"".htmlentities($item['link'])."\">".htmlentities($item['title'])."</a>
<br>"
.htmlentities($item['body'])."<br>--".htmlentities($item['username'])."
<form action=\"viewComments.php\" method=\"POST\" >
<input type=\"hidden\" name=\"token\" value=\"".$csrf."\" />
\n\t\t<button name=\"id\" type=\"submit\" value=\"".htmlentities($item['unique_id'])."\">SEE COMMENTS</button></form>";
//echo buttons when applicable
if(isset($_SESSION['loggedInUser'])) {
    
    echo $commentButton;
   //user can't follow herself
    if($_SESSION['loggedInUser']!=$item['user_id']){
        echo $followButton;
    }
}
//112 is the id value of the admin user
if((isset($_SESSION['loggedInUser']) and $_SESSION['loggedInUser']==$item['user_id'])or(isset($_SESSION['loggedInUser']) and $_SESSION['loggedInUser']==112)) {
    echo $editButton;
    echo $deleteButton;
}

} 
echo "</li></ul>\n";

$stmt->close();


?>
</body>
</html>