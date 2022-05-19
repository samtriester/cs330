<?php
 require 'database.php';
 header("Content-Type: application/json"); // Since we are sending a JSON response here (not an HTML document), set the MIME Type to application/json

//Because you are posting the data via fetch(), php has to retrieve it elsewhere.
$json_str = file_get_contents('php://input');
//This will store the data into an associative array
$json_obj = json_decode($json_str, true);
if(!hash_equals($_SESSION['token'], $json_obj['token'])){
  die("Request forgery detected");
}
//Variables can be accessed as such:
//TODO escape this input
// $event_id = $json_obj['event_id'];
// $title = $json_obj['title'];
// $body = $json_obj['body'];
// $date = $json_obj['date'];
// $tag = $json_obj['tag'];
// $group = $json_obj['group_id'];

$title = $mysqli->real_escape_string(htmlentities($json_obj['title']));
$body = $mysqli->real_escape_string(htmlentities($json_obj['body']));
$event_id = $mysqli->real_escape_string(htmlentities($json_obj['event_id']));
$tag = $mysqli->real_escape_string(htmlentities($json_obj['tag']));
$date = $mysqli->real_escape_string(htmlentities($json_obj['date']));
$group = $mysqli->real_escape_string(htmlentities($json_obj['group']));

session_start();
$user_id = $_SESSION['user_id'];

//$password = $json_obj['password'];
  //edit events
 
  header("Content-Type: application/json");
  //Because you are posting the data via fetch(), php has to retrieve it elsewhere.
  $json_str = file_get_contents('php://input');
  //This will store the data into an associative array
  $json_obj = json_decode($json_str, true);
    // if(!hash_equals($_SESSION['token'], $json_obj['token'])){
    //     die("Request forgery detected");
    // }

      $title = $mysqli->real_escape_string(htmlentities($json_obj['title']));
      $body = $mysqli->real_escape_string(htmlentities($json_obj['body']));
      $event_id = $mysqli->real_escape_string(htmlentities($json_obj['id']));
      $tag = $mysqli->real_escape_string(htmlentities($json_obj['tag']));
      $date = $mysqli->real_escape_string(htmlentities($json_obj['date']));
      $group = $mysqli->real_escape_string(htmlentities($json_obj['group_id']));
      if($group!=null){
      $stmt = $mysqli->prepare("select name from groups where group_id=".$group);
      if(!$stmt){
        echo json_encode(array(
          "success" => false,
          "message" => "Query Prep #0 Failed"
        ));
        exit;
      }
      $stmt->execute();
      $result=$stmt->get_result();
      $stmt->close();
      while($item = $result->fetch_assoc()){
        $group_id = $item['group_id'];
      }
    }
      //check if empty strings can be used to update

      $stmt = $mysqli->prepare("update events set title=?, body=?, tag=?, date=?, group_id=? where event_id=?");
      if(!$stmt){
        echo json_encode(array(
          "success" => false,
          "message" => "Query Prep #1 Failed"
        ));
        exit;
      }
      $stmt->bind_param('ssssii', $title, $body, $tag, $date, $group_id, $post_id); //check if date is s
      $stmt->execute();
      $stmt->close();
      echo json_encode(array(
        "success" => true,
        "message" => "Updated event!"
      ));

 ?>
