<!DOCTYPE html>
<html lang="en">
<head><title>FileServer</title></head>
<body>
<form action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" method="POST">
	<p>
		<label for="usernameInput">username:</label>
		<input type="text" name="username" id="usernameInput" />
	</p>
	<p>
		<input type="submit" value="Send" />
		<input type="reset" />
	</p>
</form>



<?php


session_start();

//TODO: create global variable that maps users to thier files
$userFilesMap = array();
global $userFilesMap;
//Get users from a users.txt file on apache
$users = getUsers();


// for($i=0; $i<count($users); $i++){
// 	$GLOBALS['userFilesMap'][$users[$i]] = array('dummy.txt');
// 	printf("\t<li>Line %d: %s</li>\n",
// 		$i,
// 		$GLOBALS['userFilesMap'][$users[$i]][0]
// 	);
	
// }

if(isset($_POST['username'])){
	//$ = preg_replace('/\s+/', '_', $filename);
	if( !preg_match('/^[\w_\-]+$/', $_POST['username']) ){
		echo "Invalid username";
		exit;
	}
	else {
		checkUser($users);
	}
}


//$users = array("ada", "meet", "sam", "lrudolph");   # $arr now contains an array with 4 items
//Create funciton to check that user exists
function checkUser($users){
	$username = $_POST['username'];
	if(in_array($username, $users)) {
		$_SESSION['loggedInUser'] = $username;
		header("Location: userHome.php");
	}
	else {
		printf("<p><strong>Invalid User: %s</strong></p>\n",
			$username
		);
	}
}

function getUsers() {
	$h = fopen("/srv/users.txt", "r");
	$users = array();
	$linenum = 1;
	echo "<ul>\n";
	while( !feof($h) ){

		array_push($users, trim(fgets($h)));
	}
	echo "</ul>\n";
	fclose($h);
	array_pop($users);
	return $users;
}


?> 

</body>
</html>


