<?php 

$db_server = "localhost";
$db_name = "admission";
$db_username = "root";
$db_password = "";

$con = mysqli_connect($db_server, $db_username, $db_password, $db_name);

if ($con === false) {
	die("Error: could not connect " . mysqli_connect_error());
}

?>