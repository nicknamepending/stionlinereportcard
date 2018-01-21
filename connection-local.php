<?php
	define('hostname', 'localhost');
	define('user', 'root');
	define('password', '');
	define('databaseName', 'androiddb');
	
	$connect = mysqli_connect(hostname, user, password, databaseName);
?>