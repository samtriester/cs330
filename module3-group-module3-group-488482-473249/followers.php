<!DOCTYPE html>
<html lang="en">
<head><title>Followers Page</title></head>
<body>

<?php 
//page is almost identical to homePage but the queries function differently
session_start();
$newPostButton = "<form action=\"postPage.php\" method=\"POST\">
                    \n\t<input type=\"submit\" value=\"Create New Post\" name=\"submit\">
                    </form>\n";
$logoutButton = "<form action=\"logoutScript.php\" method=\"POST\">
                    \n\t<input type=\"submit\" value=\"Logout\" name=\"submit\">
                </form>\n";
$followerTabButton="<form action=\"homePage.php\" method=\"POST\">
\n\t<input type=\"submit\" value=\"HOMEPAGE\" name=\"submit\">
</form>\n";
    echo $newPostButton;
    echo $logoutButton;
    echo $followerTabButton;
  
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
 $csrf=$_SESSION['token'];
//get list of users that loggedInUser follows
 $stmt = $mysqli->prepare("select follows from followers where user_id=".$_SESSION['loggedInUser']);
 if(!$stmt){
	printf("Query Prep Failed: %s\n", $mysqli->error);
	exit;
}

$stmt->execute();

$result = $stmt->get_result();
$follows;
$i=0;
//put all followed users into array
while($user=$result->fetch_assoc()){
    $follows[$i] = $user['follows'];
    $i=$i+1;
    
}
$stmt->close();
if(sizeof($follows)>0){
    echo "<ul>\n";
    //get all news posts from followed users
foreach($follows as $id){
$newstmt = $mysqli->prepare("select users.username as username, title, link, body, unique_id, posts.user_id as user_id from posts join users on (posts.user_id=users.user_id)
    where posts.user_id=".$id." order by unique_id desc");
    if(!$newstmt){
        printf("Query Prep Failed: %s\n", $mysqli->error);
        exit;
    }
    
    $newstmt->execute();
    
    $result = $newstmt->get_result();
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
   
    while($item = $result->fetch_assoc()){
    $deleteButton="<form action=\"deletePostPage.php\" method=\"POST\" >
    <input type=\"hidden\" name=\"token\" value=\"".$csrf."\" />
    \n\t\t<button name=\"id\" type=\"submit\" value=\"".htmlentities($item['unique_id'])."\">DELETE</button></form>";
        $commentButton="<form action=\"addCommentPage.php\" method=\"POST\" >
        <input type=\"hidden\" name=\"token\" value=\"".$csrf."\" />
        \n\t\t<button name=\"id\" type=\"submit\" value=\"".htmlentities($item['unique_id'])."\">COMMENT</button></form>";
        $editButton ="<form action=\"editPostPage
         .php\" method=\"POST\" >
         <input type=\"hidden\" name=\"token\" value=\"".$csrf."\" />
         \n\t\t<button name=\"id\" type=\"submit\" value=\"".htmlentities($item['unique_id'])."\">EDIT</button></form>";
        $followButton="<form action=\"unfollow.php\" method=\"POST\" >
        <input type=\"hidden\" name=\"token\" value=\"".$csrf."\" />
        \n\t\t<button name=\"user_id\" type=\"submit\" value=\"".htmlentities($item['user_id'])."\">UNFOLLOW</button></form>";
    echo "<li><a href=\"".htmlentities($item['link'])."\">".htmlentities($item['title'])."</a>
    <br>"
    .htmlentities($item['body'])."<br>--".htmlentities($item['username'])."
    <form action=\"viewComments.php\" method=\"POST\" >
    <input type=\"hidden\" name=\"token\" value=\"".$csrf."\" />
    \n\t\t<button name=\"id\" type=\"submit\" value=\"".htmlentities($item['unique_id'])."\">SEE COMMENTS</button></form>";
    
    if(isset($_SESSION['loggedInUser'])) {
    
        echo $commentButton;
       
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
   
    
    $newstmt->close();
}
echo "</li></ul>\n";
}
?>
</body>
</html>