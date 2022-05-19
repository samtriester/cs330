<?php
// Content of database.php


$mysqli = new mysqli('localhost', 'reddit', 'reddit', 'reddit');
// $mysqli = new mysqli('localhost', 'root', 'samtriester', 'reddit');

if($mysqli->connect_errno) {
	printf("Connection Failed: %s\n", $mysqli->connect_error);
	exit;
}
?>