<!DOCTYPE html>
<html lang="en">
<head><title>Post Script Page</title></head>
<body>
<b>Register Script page:</b>

</form>
    <div>
        blank
    </div>
<?php
require 'database.php';

session_start();
if(isset($_POST['submit'])){
    $username = $mysqli->real_escape_string(htmlentities($_POST['username']));
    $password = $mysqli->real_escape_string(password_hash(htmlentities($_POST['password']),PASSWORD_DEFAULT));
    if(strlen($username) < 1 or strlen($_POST['password']) < 1) {
        $_SESSION['badRegister'] = True;
        header("Location: loginPage.php");
        echo "invalid username";
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
        echo "num rows:";
        echo $result->num_rows;
        //if so, return session error
        if($result->num_rows > 0) {
            echo "<ul>\n";
            while($row = $result->fetch_assoc()){
                printf("\t<li>%s %s</li>\n",
                    htmlspecialchars( $row["username"]),
                    htmlspecialchars( $row["password"] ),
                    htmlspecialchars( $row["user_id"] )

                );
            }
            $_SESSION['badRegister'] = True;
            header("Location: loginPage.php");
        } 
        //otherwise return to login page with message to login
        else {
                echo "\nno user exists with that apssword\n";
                $stmt = $mysqli->prepare("INSERT INTO users (username, password) values (?, ?);");
                if(!$stmt){
                    printf("Query Prep Failed: %s\n", $mysqli->error);
                    exit;
                }
                $stmt->bind_param('ss', $username, $password);
                $stmt->execute();
                $result = $stmt->get_result();

                
                $_SESSION['badRegister'] = False;
                header("Location: loginPage.php");        
        }
        $stmt->close();
    }
}
?>
</body>
</html>