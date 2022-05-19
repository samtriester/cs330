
<?php
    require 'database.php';
    //Because you are posting the data via fetch(), php has to retrieve it elsewhere.
    $json_str = file_get_contents('php://input');
    //This will store the data into an associative array
    $json_obj = json_decode($json_str, true);
    //Variables can be accessed as such:
    $username = $mysqli->real_escape_string(htmlentities($json_obj['username']));
    $password = $mysqli->real_escape_string(password_hash(htmlentities($json_obj['password']), PASSWORD_DEFAULT));
    
    if(strlen($username) < 1 or strlen($json_obj['password']) < 1) {
        echo json_encode(array(
            "success" => false,
            "message" => "Invalid Username or Password"
        ));
        exit;
    }
    else {
        //run query with post variables
        $stmt = $mysqli->prepare("SELECT * FROM users WHERE username=?;");
        if(!$stmt){
            printf("Query Prep Failed: %s\n", $mysqli->error);
            exit;
        }
        $stmt->bind_param('s', $username);
        $stmt->execute();
        

        //view result to see if user already exists
        $result = $stmt->get_result();
        //if so, return session error
        if($result->num_rows > 0) {
            echo json_encode(array(
                "success" => false,
                "message" => "User Already Exists"
            ));
        } 
        //otherwise return to login page with message to login
        else {
                //echo "\nno user exists with that apssword\n";
                $stmt = $mysqli->prepare("INSERT INTO users (username, password) values (?, ?);");
                if(!$stmt){
                    printf("Query Prep Failed: %s\n", $mysqli->error);
                    exit;
                }
                $stmt->bind_param('ss', $username, $password);
                $stmt->execute();
                $result = $stmt->get_result();
                //session_start();
                //$_SESSION['username'] = $username;
                //$_SESSION['token'] = bin2hex(openssl_random_pseudo_bytes(32)); 
                
                echo json_encode(array(
                    "success" => true,
                    "message" => "User Added"
                ));
        }
        $stmt->close();
    }

?>
