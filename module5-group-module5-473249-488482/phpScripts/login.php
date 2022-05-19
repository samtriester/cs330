<?php
// login_ajax.php
require 'database.php';

header("Content-Type: application/json"); // Since we are sending a JSON response here (not an HTML document), set the MIME Type to application/json

//Because you are posting the data via fetch(), php has to retrieve it elsewhere.
$json_str = file_get_contents('php://input');
//This will store the data into an associative array
$json_obj = json_decode($json_str, true);

//Variables can be accessed as such:
//TODO escape this input
$username = $json_obj['username'];
$password = $json_obj['password'];
//This is equivalent to what you previously did with $_POST['username'] and $_POST['password']

// Check to see if the username and password are valid.  (You learned how to do this in Module 3.)
    //run query with post variables
    $stmt = $mysqli->prepare("SELECT * FROM users WHERE username=?;");

    //$stmt = $mysqli->prepare("SELECT * FROM users WHERE username=? && password=?");
    if(!$stmt){
        echo json_encode(array(
            "success" => false,
            "message" => "Query Prep #0 Failed"
          ));
          exit;
    }
    $stmt->bind_param('s', $username);
    $stmt->execute();
    

    // Bind the results
    $stmt->bind_result($user_id, $username_result, $pwd_hash);
    $stmt->fetch();

    // Compare the submitted password to the actual password hash
    if(password_verify($password, $pwd_hash)){
    //if(1 == 1){
        ini_set("session.cookie_httponly", 1);
        session_start();
        $_SESSION['user_id'] = $user_id;
        $_SESSION['token'] = bin2hex(openssl_random_pseudo_bytes(32)); 

        echo json_encode(array(
            "success" => true
        ));
        exit;
    }else{
        //$message = "ivalid combo " . $cnt . " + " . $username_result . " + " . $pwd_hash;
        echo json_encode(array(
            "success" => false,
            "message" => "Incorrect Username or Password"
            //"message" => $message

        ));
        exit;
    }
?>
