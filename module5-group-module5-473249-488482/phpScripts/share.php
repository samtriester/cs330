<?php
require 'databse.php';
$user_id = $_SESSION['user_id'];

//Because you are posting the data via fetch(), php has to retrieve it elsewhere.
$json_str = file_get_contents('php://input');
//This will store the data into an associative array
$json_obj = json_decode($json_str, true);
  // add User to shared
  $user = $mysqli->real_escape_string(htmlentities($json_obj['share']));
  $stmt = $mysqli->prepare("select user_id from users where username=".$user);
  $stmt->execute();
  $result=$stmt->get_result();
  $stmt->close();
  while($item = $result->fetch_assoc()){
    $user = $item['user_id'];
  }
  //check if empty strings can be used to update
  $stmt = $mysqli->prepare("insert into shareds (sharer, receiver) values (?, ?)");
  if(!$stmt){
      printf("Query Prep Failed: %s\n", $mysqli->error);
      exit;
  }
  $stmt->bind_param('ii', $user_id, $user); //check if date is s
  $stmt->execute();
  $stmt->close();
}
 ?>