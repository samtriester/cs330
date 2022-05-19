<?php
require 'database.php';
session_start();
$user_id = $_SESSION['user_id'];

//Because you are posting the data via fetch(), php has to retrieve it elsewhere.
$json_str = file_get_contents('php://input');
//This will store the data into an associative array
$json_obj = json_decode($json_str, true);

//Variables can be accessed as such:
$group_name =  $mysqli->real_escape_string($json_obj['group_name']);
$isCreate =  $mysqli->real_escape_string($json_obj['isCreate']);
$isAdd =  $mysqli->real_escape_string($json_obj['isAdd']);
$isRemove =  $mysqli->real_escape_string($json_obj['isRemove']);

  //create group
  //if(isset($_POST['group_name'])){
    // if(!hash_equals($_SESSION['token'], $_POST['token'])){
    //     die("Request forgery detected");
    // }
  if($isCreate == "True"){
  
   //$group_name =  $mysqli->real_escape_string($_POST['group_name']);
    //Make sure group doesn't exist
    $stmt = $mysqli->prepare("select group_id from groups where name=? and user_id=?");
    if(!$stmt){
      echo json_encode(array(
        "success" => false,
        "message" => "Query Prep #0 Failed"
      ));
      exit;
    }
    $stmt->bind_param('ss', $group_name, $user_id);
    $stmt->execute();
    $result=$stmt->get_result();
    if($result->num_rows > 0) {
      echo json_encode(array(
        "success" => false,
        "message" => "group already exists"
      ));
      exit;
    }
    $stmt->close();


    //create group (change group column values)
    $stmt = $mysqli->prepare("insert into groups (name, user_id) values (?,?)");
    if(!$stmt){
      //printf("Query Prep Failed: %s\n", $mysqli->error);
      echo json_encode(array(
        "success" => false,
        "message" => "Query Prep Failed"
      ));
      exit;
    }
    // Bind the parameter
    $stmt->bind_param('si', $group_name, $user_id);
    $stmt->execute();
    $stmt->close();


    //get group_id
    $stmt = $mysqli->prepare("select group_id from groups where name=? and user_id=?");
    if(!$stmt){
      echo json_encode(array(
        "success" => false,
        "message" => "Query Prep #2 Failed"
      ));
      exit;
    }
    $stmt->bind_param('ss', $group_name, $user_id);
    $stmt->execute();
    $result=$stmt->get_result();
    while($item = $result->fetch_assoc()){
      $group_id = $item['group_id'];
    }
    $stmt->close();

    //add group creator to group
    $stmt = $mysqli->prepare("insert into group_content (group_id,user) values (?,?)");
    // Bind the parameter
    $stmt->bind_param('ii', $group_id, $user_id);
    $stmt->execute();
    $stmt->close();
    echo json_encode(array(
        "success" => true,
        "message" => "succesfully added group"
    ));

  }
  else if($isAdd  == "True"){
    $usernameToAdd = $mysqli->real_escape_string($json_obj['usernameToAdd']);

    //Get Group of ID of group name
    $stmt = $mysqli->prepare("select group_id from groups where name=? and user_id=?");
    if(!$stmt){
      echo json_encode(array(
        "success" => false,
        "message" => "Query Prep #1 Failed"
      ));
      exit;
    }
    $stmt->bind_param('ss', $group_name, $user_id);
    $stmt->execute();
    $result=$stmt->get_result();
    //Check to make sure user owns this group
    if($result->num_rows != 1) {
      echo json_encode(array(
        "success" => false,
        "message" => "User does not own group"
      ));
      exit;
    }
    $stmt->close();
    while($item = $result->fetch_assoc()){
      $group_id = $item['group_id'];
    }


    //Get user id of usernameToAdd
    $stmt = $mysqli->prepare("select user_id from users where username=?");
    if(!$stmt){
      echo json_encode(array(
        "success" => false,
        "message" => "Query Prep #2 Failed"
      ));
      exit;
    }
    $stmt->bind_param('s', $usernameToAdd);
    $stmt->execute();
    $result=$stmt->get_result();
    $stmt->close();
    while($item = $result->fetch_assoc()){
      $userIDToAdd = $item['user_id'];
    }

    //add group member to group
    $stmt = $mysqli->prepare("insert into group_content (group_id,user) values (?,?)");

    // Bind the parameter
    $stmt->bind_param('ii', $group_id, $userIDToAdd);
    $stmt->execute();
    $stmt->close();
    echo json_encode(array(
      "success" => true,
      "message" => "Added user to Group!"
    ));
}
  //remove user from group
else if($isRemove  == "True"){
  $usernameToRemove = $mysqli->real_escape_string($json_obj['usernameToRemove']);
  //Get Group Id from group name
  $stmt = $mysqli->prepare("select group_id from groups where name=? and user_id=?");
  if(!$stmt){
    echo json_encode(array(
      "success" => false,
      "message" => "Query Prep #1 Failed"
    ));
    exit;
  }
  $stmt->bind_param('ss', $group_name, $user_id);
  $stmt->execute();
  $result=$stmt->get_result();
  //Check to make sure user owns this group
  if($result->num_rows != 1) {
    echo json_encode(array(
      "success" => false,
      "message" => "User does not own group"
    ));
    exit;
  }
  $stmt->close();

  while($item = $result->fetch_assoc()){
    $group_id = $item['group_id'];
  }

    //Get user id of usernameToRemove
    $stmt = $mysqli->prepare("select user_id from users where username=?");
    if(!$stmt){
      echo json_encode(array(
        "success" => false,
        "message" => "Query Prep #2 Failed"
      ));
      exit;
    }
    $stmt->bind_param('s', $usernameToRemove);
    $stmt->execute();
    $result=$stmt->get_result();
    $stmt->close();
    while($item = $result->fetch_assoc()){
      $userIDToRemove = $item['user_id'];
    }

  //Delete group content relatinship with userIdtoRemove
  $stmt = $mysqli->prepare("delete from group_content where group_id=? and user=?");
  if(!$stmt) {
    echo json_encode(array(
      "success" => false,
      "message" => "Query Prep #2 Failed"
    ));
    exit;
  }
    // Bind the parameter
  $stmt->bind_param('ss', $group_id, $userIDToRemove);
  
  
  $stmt->execute();
  $stmt->close();
  echo json_encode(array(
    "success" => true,
    "message" => "Removed User from group!",
    "group_id" => $group_id,
    "userIDToRemove" => $userIDToRemove,
  ));
  }
//}
 ?>
