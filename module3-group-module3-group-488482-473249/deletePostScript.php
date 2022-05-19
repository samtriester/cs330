<!DOCTYPE html>
<html lang="en">
<head><title>Delete Script Page</title></head>
<body>
<b>Delete Script page:</b>
<?php
require 'database.php';

session_start();
if(isset($_POST['id'])) {
    //CHeck that valid CSRF tokens are passed
    if(!hash_equals($_SESSION['token'], $_POST['token'])){
        die("Request forgery detected");
    }

	$post_id = $_POST['id'];
    echo $post_id;
    
    if($_POST['isComment']) {
        echo "is comment";
        $stmt = $mysqli->prepare("DELETE from comments where unique_id=?;");
        if(!$stmt){
            printf("Query Prep Failed: %s\n", $mysqli->error);
            exit;
        }
        $stmt->bind_param('i', $post_id);
    }
    else {
        //Delete all comments under post first
        $stmt = $mysqli->prepare("DELETE from comments where post_id=?;");
        if(!$stmt){
            printf("Query Prep Failed: %s\n", $mysqli->error);
            exit;
        }
        $stmt->bind_param('i', $post_id);
	    $stmt->execute();

        //Then delete the post itself
        $stmt = $mysqli->prepare("DELETE from posts where unique_id=?;");
        if(!$stmt){
            printf("Query Prep Failed: %s\n", $mysqli->error);
            exit;
        }
    }

    $stmt->bind_param('i', $post_id);
	$stmt->execute();
	$result = $stmt->get_result();


	$stmt->close();
    header("Location: homePage.php");
}
else {
    echo "post not set";
}
?>

</body>
</html>