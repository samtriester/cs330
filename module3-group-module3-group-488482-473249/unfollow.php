<!DOCTYPE html>
<html lang="en">
<head><title>Password</title></head>
<body>
    <?php
    session_start();
    require 'database.php';
    if(isset($_POST['user_id'])){
        if(!hash_equals($_SESSION['token'], $_POST['token'])){
            die("Request forgery detected");
        }

       $user_id = $_SESSION['loggedInUser'];
      
       $follows = $_POST['user_id'];
      

        $stmt = $mysqli->prepare("delete from followers where user_id=".$user_id." and follows=".$follows);

        // Bind the parameter
        
        
        $stmt->execute();
        $stmt->close();
    }
       header("Location: followers.php"); 
    ?>
</body>
</html>