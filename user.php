<?php
	require 'connection.php';
	
	global $connect;
	
	$username = null;
	$password = null;
	
	if (isset($_GET['username']) && isset($_GET['password'])) {
		$username = $_GET['username'];
		$password = $_GET['password'];
	}
	
	$return_arr = array();	
	$sqlUser = "SELECT * FROM user WHERE username = '".$username."' AND password = '".$password."'";
	$fetchUser = mysqli_query($connect, $sqlUser) or die (mysqli_error($connect));

	while ($row = mysqli_fetch_array($fetchUser)) {
		$row_array['username'] = $row['username'];
		$row_array['password'] = $row['password'];
		$row_array['studentId'] = $row['studentId'];
		$row_array['facultyId'] = $row['facultyId'];
	
		array_push($return_arr,$row_array);
	}

	if (!empty($return_arr)) {
		echo '{"user":'.json_encode($return_arr).'}';
	}
	mysqli_close($connect);
?>