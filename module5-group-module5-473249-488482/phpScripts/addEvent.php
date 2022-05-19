<?php
require 'database.php';
session_start();
//if(isset($_POST['title'])){

header("Content-Type: application/json");
//Because you are posting the data via fetch(), php has to retrieve it elsewhere.
$json_str = file_get_contents('php://input');
//This will store the data into an associative array
$json_obj = json_decode($json_str, true);
if(!hash_equals($_SESSION['token'], $json_obj['token'])){
  die("Request forgery detected");
}
  //Add event to individual Calendar
  $user=$_SESSION['user_id'];
  $title = $mysqli->real_escape_string(htmlentities($json_obj['title']));
  $body = $mysqli->real_escape_string(htmlentities($json_obj['body']));
  $tag = $mysqli->real_escape_string(htmlentities($json_obj['tag']));
  $date = $mysqli->real_escape_string(htmlentities($json_obj['date']));
  $group = $mysqli->real_escape_string(htmlentities($json_obj['group']));
  if($group!=null){
  $stmt = $mysqli->prepare("select group_id from groups where name=".$group."and user_id=".$user);
  $stmt->execute();
  $result=$stmt->get_result();
  $stmt->close();
  while($item = $result->fetch_assoc()){
    $group = $item['group_id'];
  }
}
  //check if empty strings can be used to update
  $stmt = $mysqli->prepare("insert into events (title, body, tag, date, group_id) values (?, ?, ?, ?, ?)");
  if(!$stmt){
      printf("Query Prep Failed: %s\n", $mysqli->error);
      exit;
  }
  $stmt->bind_param('ssssi', $title, $body, $tag, $date, $group); //check if date is s
  $stmt->execute();
  $stmt->close();
//}
 ?>
