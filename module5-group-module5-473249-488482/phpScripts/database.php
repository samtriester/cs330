<?php
// Content of database.php

ini_set("session.cookie_httponly", 1);
session_start();
$mysqli = new mysqli('localhost', 'calendar', 'calendar', 'calendar');
// $mysqli = new mysqli('localhost', 'root', 'samtriester', 'reddit');

if($mysqli->connect_errno) {
	printf("Connection Failed: %s\n", $mysqli->connect_error);
	exit;
}
?>