<?php
	$hostname = getenv('MYSQL_SERVICE_HOST');
	$port = getenv('MYSQL_SERVICE_PORT');
	$username = getenv('username');
	$password = getenv('password');
        $database = getenv('database');
	
	$connect = mysqli_connect($hostname, $username, $password, $database);
?>
