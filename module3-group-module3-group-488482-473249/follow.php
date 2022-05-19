<!DOCTYPE html>
<html lang="en">
<head><title>Password</title></head>
<body>
    <?php
    session_start();
    require 'database.php';
    if(isset($_POST['id'])){
        if(!hash_equals($_SESSION['token'], $_POST['token'])){
            die("Request forgery detected");
        }

       $user_id = $_SESSION['loggedInUser'];
      
       $follows = $_POST['id'];
      

        $stmt = $mysqli->prepare("insert into followers (user_id, follows) values (?,?)");

        // Bind the parameter
        
        $stmt->bind_param('ii', $user_id, $follows);
        $stmt->execute();
        $stmt->close();
    }
        header("Location: homePage.php"); 
    ?>
</body>
</html>