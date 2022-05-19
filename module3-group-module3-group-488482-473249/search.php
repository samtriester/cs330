<!DOCTYPE html>
<html lang="en">
<head><title>Home Page</title></head>
<body>

<?php 
//very functionally similar to homePage
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
 session_start();
 


 $query = $mysqli->real_escape_string($_POST['searchString']);
 //searches for the exact string somewhere in the title or body of posts
 //then fetches and posts just like homePage
 $stmt = $mysqli->prepare("select users.username as username, title, link, body from posts join users on (posts.user_id=users.user_id) where body like '%".htmlentities($query)."%' or title like '%".htmlentities($query)."%' order by unique_id desc");
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
    $deleteButton="<form action=\"deletePostPage.php\" method=\"POST\" >
    <input type=\"hidden\" name=\"token\" value=\"".$csrf."\" />
    \n\t\t<button id=\"delete\" name=\"id\" type=\"submit\" value=\"".htmlentities($item['unique_id'])."\">DELETE</button></form>";
    $commentButton="<form action=\"addCommentPage.php\" method=\"POST\" >
    <input type=\"hidden\" name=\"token\" value=\"".$csrf."\" />
    \n\t\t<button id=\"comment\" name=\"id\" type=\"submit\" value=\"".htmlentities($item['unique_id'])."\">COMMENT</button></form>";
    $editButton ="<form action=\"editPostPage.php\" method=\"POST\" >
    <input type=\"hidden\" name=\"token\" value=\"".$csrf."\" />
    \n\t\t<button id=\"edit\" name=\"id\" type=\"submit\" value=\"".htmlentities($item['unique_id'])."\">EDIT</button></form>";
    $followButton="<form action=\"follow.php\" method=\"POST\" >
    <input type=\"hidden\" name=\"token\" value=\"".$csrf."\" />
    \n\t\t<button id=\"follow\" name=\"id\" type=\"submit\" value=\"".htmlentities($item['user_id'])."\">FOLLOW POSTER</button></form>";
echo "<li><a href=\"".htmlentities($item['link'])."\">".htmlentities($item['title'])."</a>
<br>"
.htmlentities($item['body'])."<br>--".htmlentities($item['username'])."
<form action=\"viewComments.php\" method=\"POST\" >
<input type=\"hidden\" name=\"token\" value=\"".$csrf."\" />
\n\t\t<button id=\"comments\" name=\"id\" type=\"submit\" value=\"".htmlentities($item['unique_id'])."\">SEE COMMENTS</button></form></li>";
//112 is the id value of the admin user
if(isset($_SESSION['loggedInUser'])) {
    
    echo $commentButton;
   
    if($_SESSION['loggedInUser']!=$item['user_id']){
        echo $followButton;
    }
}

if((isset($_SESSION['loggedInUser']) and $_SESSION['loggedInUser']==$item['user_id'])or(isset($_SESSION['loggedInUser']) and $_SESSION['loggedInUser']==112)) {
    echo $editButton;
    echo $deleteButton;
}

} 
echo "</ul>\n";

$stmt->close();



?>
</body>
</html>