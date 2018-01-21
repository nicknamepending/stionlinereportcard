<?php
	require 'connection.php';
	
	global $connect;
	
	$studentId = null;
	
	if (isset($_GET['studentId'])) {
		$studentId = $_GET['studentId'];
	}
	
	$return_arr = array();	
	$sqlGrade = "SELECT * FROM grade WHERE studentId = '".$studentId."'";
	$fetchGrade = mysqli_query($connect, $sqlGrade) or die (mysqli_error($connect));
	
	while ($row = mysqli_fetch_array($fetchGrade)) {
		$row_array['gradeId'] = $row['gradeId'];
		$row_array['studentId'] = $row['studentId'];
		$row_array['schoolYear'] = $row['schoolYear'];
		$row_array['semester'] = $row['semester'];
		$row_array['section'] = $row['section'];
		$row_array['subject'] = $row['subject'];
		$row_array['prelim'] = $row['prelim'];
		$row_array['midterm'] = $row['midterm'];
		$row_array['preFinals'] = $row['preFinals'];
		$row_array['finals'] = $row['finals'];
		$row_array['finalGrade'] = $row['finalGrade'];
		
		$sqlFaculty = "SELECT fullName FROM faculty WHERE facultyId = '".$row['facultyId']."'";
		$fetchFaculty = mysqli_query($connect, $sqlFaculty);
		
		while ($rowFaculty = mysqli_fetch_array($fetchFaculty)) {
			$row_array['facultyName'] = $rowFaculty['fullName'];
		}
		array_push($return_arr,$row_array);
	}
	
	if (!empty($return_arr)) {
		echo '{"grade":'.json_encode($return_arr).'}';
	}
	mysqli_close($connect);
?>