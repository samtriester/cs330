<?php
require 'database.php';
session_start();
$user_id = $_SESSION['user_id'];

$stmt = $mysqli->prepare("select * from tags where user_id=?");
    if(!$stmt){
      echo json_encode(array(
        "success" => false,
        "message" => "Query Prep #0 Failed"
      ));
      exit;
    }
    $stmt->bind_param('s', $user_id);
    $stmt->execute();
    $result=$stmt->get_result();
    if($result->num_rows == 0) {
      echo json_encode(array(
        "success" => false,
        "message" => "user has no tags"
      ));
      exit;
    }
    $tags = array();
    $i = 0;
    while($item = $result->fetch_assoc()){
        $tag_id = $item['tag_id'];
        $tag_name = $item['tagname'];
        $tags[$i] = array('tag_id'=>$tag_id,'tagname'=>$tag_name);
        $i=$i+1;
    }
    $stmt->close();

    echo(json_encode($tags));

?>