<?php
require 'database.php';
session_start();
$user_id = $_SESSION['user_id'];
$json_str = file_get_contents('php://input');
//This will store the data into an associative array
$json_obj = json_decode($json_str, true);
//Variables can be accessed as such:
$tag_name=  $mysqli->real_escape_string($json_obj['tagName']);
$stmt = $mysqli->prepare("insert into tags (tagname, user_id) values (?,?)");
    if(!$stmt){
      echo json_encode(array(
        "success" => false,
        "message" => "Query Prep #0 Failed"
      ));
      exit;
    }
    $stmt->bind_param('ss', $tag_name, $user_id);
    $stmt->execute();
    
    $stmt->close();

    echo json_encode(array(
      "success" => true,
      "message" => "Added tag!"
    ));

?>