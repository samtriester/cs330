<?php
 
require 'database.php';
  session_start();
  //TODO how do we prevent cross requests
  // if(!hash_equals($_SESSION['token'], $_POST['token'])){
  //     die("Request forgery detected");
  // }
  //load individual Events
  $stmt = $mysqli->prepare("select event_id, title, date, body, tag from events where user_id=?;");
  if(!$stmt){
    printf("Query Prep Failed: %s\n", $mysqli->error);
    exit;
  }  
  $stmt->bind_param('s', $_SESSION['user_id']);

  $stmt->execute();
    $result=$stmt->get_result();
    $stmt->close();
    $i=0;
    $events = array();
    while($item = $result->fetch_assoc()){
      $id = htmlentities($item['event_id']);
      $title = htmlentities($item['title']);
      $date = htmlentities($item['date']);
      $body = htmlentities($item['body']);
      $tag = htmlentities($item['tag']);
      $events[$i]=array('id'=>$id, 'title'=>$title, 'date'=>$date, 'body'=>$body, 'tag'=>$tag);
      $i=$i+1;
    }
  //Load Group events
  //Grab list of all groups a user belongs too
  $stmt = $mysqli->prepare("select group_id from group_content where user=?");
  if(!$stmt){
    printf("Query Prep Failed: %s\n", $mysqli->error);
    exit;
  }  
  $stmt->bind_param('s', $_SESSION['user_id']);
  $stmt->execute();
  $result=$stmt->get_result();
  $stmt->close();
  //Query all events for each group a user belongs to
  while($item = $result->fetch_assoc()){
    $stmt = $mysqli->prepare("select event_id, title, date, body from events where group_id=? AND user_id != ?");
    if(!$stmt){
      printf("Query Prep Failed: %s\n", $mysqli->error);
      exit;
    }  
    $stmt->bind_param('ss', $item['group_id'], $_SESSION['user_id']);
    $stmt->execute();
    $events_query_result=$stmt->get_result();
    $stmt->close();
    while($event = $events_query_result->fetch_assoc()){
      $id = htmlentities($event['event_id']);
      $title = htmlentities($event['title']); //add group name to tittle
      $date = htmlentities($event['date']);
      $body = htmlentities($event['body']);
      $events[$i]=array('id'=>$id,'title'=>$title, 'date'=>$date, 'body'=>$body);
      $i=$i+1;
    }
  }
    //load shared calendar Events
    $stmt = $mysqli->prepare("select sharer from shareds where receiver=".$_SESSION['user_id']);
    $stmt->execute();
    $result=$stmt->get_result();
    $stmt->close();
    while($item = $result->fetch_assoc()){
      $stmt = $mysqli->prepare("select event_id, title, date, body from events where user=".$item['sharer']);
      $stmt->execute();
      $events_query_result=$stmt->get_result();
    $stmt->close();
    while($event = $events_query_result->fetch_assoc()){
      $id = htmlentities($event['event_id']);
      $title = htmlentities($event['title']); //add group name to tittle
      $date = htmlentities($event['date']);
      $body = htmlentities($event['body']);
      $events[$i]=array('id'=>$id,'title'=>$title, 'date'=>$date, 'body'=>$body);
      $i=$i+1;
    }
    }
  $events['token']=$_SESSION['token'];
  echo(json_encode($events));


 ?>
