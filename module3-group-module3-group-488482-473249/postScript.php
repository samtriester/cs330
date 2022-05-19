<!DOCTYPE html>
<html lang="en">
<head><title>Post Script Page</title></head>
<body>
<b>Post Script page:</b>

<?php
require 'database.php';
session_start();
if(isset($_POST['submit']) && isset($_SESSION['loggedInUser'])){
    //CHeck that valid CSRF tokens are passed
    if(!hash_equals($_SESSION['token'], $_POST['token'])){
        die("Request forgery detected");
    }
    //Filter all post variables to avoid malicious injections
    $title = $mysqli->real_escape_string(htmlentities($_POST['title']));
    $body = $mysqli->real_escape_string(htmlentities($_POST['body']));
    $post_id = $mysqli->real_escape_string(htmlentities($_POST['id']));
    $link = $mysqli->real_escape_string(htmlentities($_POST['link']));
    $userid =$mysqli->real_escape_string(htmlentities($_SESSION['loggedInUser']));

    //Run different query depending on origin of post request
    if($_POST['isEdit']) {
       
        echo "is edit";
        $stmt = $mysqli->prepare("update posts set title=?, body=?, link=? where unique_id=?");
        if(!$stmt){
            printf("Query Prep Failed: %s\n", $mysqli->error);
            exit;
        }
        $stmt->bind_param('sssi', $title, $body, $link, $post_id);
        
    }
    else if($_POST['isComment']) {
        echo "is comment";
        $stmt = $mysqli->prepare("INSERT INTO comments (user_id, post_id, body) values (?, ?, ?);");
        if(!$stmt){
            printf("Query Prep Failed: %s\n", $mysqli->error);
            exit;
        }
        $stmt->bind_param('iis', $userid, $post_id, $body);
    }
    else if($_POST['isEditComment']) {
        echo "is edit comment";
        $stmt = $mysqli->prepare("update comments set body=? where unique_id=?;");
        if(!$stmt){
            printf("Query Prep Failed: %s\n", $mysqli->error);
            exit;
        }
        $stmt->bind_param('si', $body, $post_id);
    }
    //Otherwise add new post
    else {
        $stmt = $mysqli->prepare("INSERT INTO posts (user_id, link, title, body) values (?, ?, ?, ?);");
        if(!$stmt){
            printf("Query Prep Failed: %s\n", $mysqli->error);
            exit;
        }
        $stmt->bind_param('isss', $userid, $link, $title, $body);
        echo "is post\n";

    }


    
    $stmt->execute();
    $result = $stmt->get_result();

    //Return to home page after post or edit
    header("Location: homePage.php");
    $stmt->close();
}
else {
    echo "Post variable not set or user is not logged in";
}


?>
</body>
</html>