<!DOCTYPE html>
<html lang="en">
<head><title>Post Script Page</title></head>
<body>
<b>Login Script page:</b>
    <!-- <form method="POST" id="badLogin" action="loginPage.php">
        <input type="hidden" name="invalidLogin" value="bad">
    </form>
    <form method="post" id="validLogin" action="homePage.php">
        <input type="hidden" name="loginResult" value="bad"> -->
</form>
<?php
require 'database.php';

session_start();
if(isset($_POST['submit'])){
    //echo "post is set";
    // $username = $_POST['username'];
    $password = $_POST['password'];
    $username = $mysqli->real_escape_string(htmlentities($_POST['username']));
    //$password = $mysqli->real_escape_string(password_hash(htmlentities($_POST['password']),PASSWORD_DEFAULT));
    // $username = 't';
    // $password = 'r';
    // echo $_POST['username'];
    // echo $_POST['password'];

    //run query with post variables
    $stmt = $mysqli->prepare("SELECT * FROM users WHERE username=?;");

    // $stmt = $mysqli->prepare("SELECT * FROM users WHERE username=? && password=?");
    if(!$stmt){
        printf("Query Prep Failed: %s\n", $mysqli->error);
        exit;
    }
    $stmt->bind_param('s', $username);
    $stmt->execute();
    

    // Bind the results
    // $stmt->bind_result($user_id, $username, $pwd_hash);
    // $stmt->fetch();
    $result = $stmt->get_result();
    echo "num rows:";
    echo $result->num_rows;
    echo $password;
   // echo password_verify()
    //TODO, how to tell if results returns a valid response?
    if($result->num_rows > 0) {
        echo "<ul>\n";
        while($row = $result->fetch_assoc()){
            printf("\t<li>%s %s</li>\n",
                htmlspecialchars( $row["username"]),
               htmlspecialchars( $row["password"] ),
                htmlspecialchars( $row["user_id"] )

            );
            if(password_verify($password, $row['password'])) {
                $_SESSION['loggedInUser'] = htmlspecialchars( $row["user_id"] );
                $_SESSION['badLogin'] = False;
                header("Location: homePage.php");
                echo "valid user";
            }
            else {
                $_SESSION['badLogin'] = True;
                header("Location: loginPage.php");    
            }
        }
        //$_SESSION['loggedInUser'] = $user_id;
    } 
    else {
            echo "\nno user exists with that apssword\n";
            $_SESSION['badLogin'] = True;
            header("Location: loginPage.php");

    }

    $stmt->close();
}
$_SESSION['token']=bin2hex(openssl_random_pseudo_bytes(32));
?>
</body>
</html>