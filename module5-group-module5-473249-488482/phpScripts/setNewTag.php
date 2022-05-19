<?php
require 'database.php';
session_start();
$user_id = $_SESSION['user_id'];
$json_str = file_get_contents('php://input');
//This will store the data into an associative array
$json_obj = json_decode($json_str, true);
//Variables can be accessed as such:
$tag_name=  $mysqli->real_escape_string($json_obj['tagName']);
$event_id=  $mysqli->real_escape_string($json_obj['event_id']);
//Get Tag ID from TAg name
$stmt = $mysqli->prepare("select tag_id from tags where tagname=?");
if(!$stmt){
  echo json_encode(array(
    "success" => false,
    "message" => "Query Prep #0 Failed"
  ));
  exit;
}
$stmt->bind_param('ss', $tag_name);
$stmt->execute();
// Bind the results
$stmt->bind_result($tag_id);
$stmt->fetch();

$stmt->close();

$stmt = $mysqli->prepare("UPDATE events SET tag= ? WHERE event_id=?");
");
    if(!$stmt){
      echo json_encode(array(
        "success" => false,
        "message" => "Query Prep #0 Failed"
      ));
      exit;
    }
    $stmt->bind_param('ss', $tag_id, $user_id);
    $stmt->execute();
    
    $stmt->close();

    echo json_encode(array(
      "success" => true,
      "message" => "Added tag!"
    ));

?>