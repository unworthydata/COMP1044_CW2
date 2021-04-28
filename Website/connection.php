<?php

$host = "localhost";
$username = "root";
$password = "";
$database = "entertainment";
$conn = mysqli_connect($host, $username, $password, $database);

if (!$conn)
	echo 'Connection Error:' . mysqli_connect_error();
