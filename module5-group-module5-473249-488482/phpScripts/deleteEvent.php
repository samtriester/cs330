<?php
require 'database.php';
  //delete event
  
  header("Content-Type: application/json");
  //Because you are posting the data via fetch(), php has to retrieve it elsewhere.
  $json_str = file_get_contents('php://input');
  //This will store the data into an associative array
  $json_obj = json_decode($json_str, true);
    if(!hash_equals($_SESSION['token'], $json_obj['token'])){
        die("Request forgery detected");
    }
    $event = $json_obj['event_id'];
    $stmt = $mysqli->prepare("DELETE from events where event_id=?;");
        if(!$stmt){
            printf("Query Prep Failed: %s\n", $mysqli->error);
            exit;
        }

    $stmt->bind_param('i', $event);
	$stmt->execute();


	$stmt->close();
  
 ?>
