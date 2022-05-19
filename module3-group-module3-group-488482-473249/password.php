<!DOCTYPE html>
<html lang="en">
<head><title>Password</title></head>
<body>
    <?php
    require 'database.php';
    if(!hash_equals($_SESSION['token'], $_POST['token'])){
        die("Request forgery detected");
    }
        $username = $mysqli->real_escape_string(htmlentities($_POST['username']));
        $saltedpass = $mysqli->real_escape_string(password_hash(htmlentities($_POST['password']),PASSWORD_DEFAULT));
        $stmt = $mysqli->prepare("insert into users (username, password) values (?,?)");

        // Bind the parameter
        
        $stmt->bind_param('ss', $username, $saltedpass);
        $stmt->execute();
        $stmt->close();
    ?>
</body>
</html>