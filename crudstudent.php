<?php
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {		
		require 'connection.php';
		
		if (isset($_POST['message'])) {
			$message = $_POST['message'];
			
			if ($message == 'Create') {
				createStudent();
			} else if ($message == 'Search') {
				readStudent();
			} else if ($message == 'Update') {
				updateStudent();
			} else if ($message == 'Delete') {
				deleteStudent();
			}
		}
	}
	
	function createStudent() {
		global $connect;
		date_default_timezone_set('Asia/Manila');
		$date = date('Y-m-d H:i:s');
		
		$studentNumber = null;
		$firstName = null;
		$middleName = null;
		$lastName = null;
		$fullName = null;
		$dateOfBirth = null;
	
		if (isset($_POST['studentNumber']) && isset($_POST['firstName']) && isset($_POST['middleName']) && isset($_POST['lastName']) && isset($_POST['dateOfBirth'])) {
			$studentId = guidv4(openssl_random_pseudo_bytes(16));
			$studentNumber = $_POST['studentNumber'];
			$firstName = $_POST['firstName'];
			$middleName = $_POST['middleName'];
			$lastName = $_POST['lastName'];
			$dateOfBirth = $_POST['dateOfBirth'];
			$fullName = $firstName[0]."".$middleName[0]." ".$lastName;
		}
		
		$sqlStudent = "SELECT * FROM student WHERE studentNumber='".$studentNumber."'";					
		$fetch = mysqli_query($connect, $sqlStudent) or die (mysqli_error($connect));
		$row_count = mysqli_num_rows($fetch);
		
		if ($row_count > 0) {
			echo 'Student already exists!';			
		} else {
			$sqlStudent = "INSERT INTO student (studentId, studentNumber, firstName, middleName, lastName, fullName, dateOfBirth, createdOn)
				VALUES ('".$studentId."', '".$studentNumber."', '".$firstName."', '".$middleName."', '".$lastName."', '".$fullName."', '".$dateOfBirth."', '".$date."')";
		
			$sqlUser = "INSERT INTO user (userId, username, password, facultyId, studentId, createdOn)
				VALUES ('".guidv4(openssl_random_pseudo_bytes(16))."', '".$studentNumber."', '".$dateOfBirth."', '', '".$studentId."', '".$date."')";
					
			mysqli_query($connect, $sqlStudent) or die (mysqli_error($connect));
			mysqli_query($connect, $sqlUser) or die (mysqli_error($connect));
			mysqli_close($connect);
			echo 'Student record created!';
		}
	}
	
	function readStudent() {
		global $connect;
		
		$studentNumber = null;
		$firstName = null;
		$middleName = null;
		$lastName = null;
		$fullName = null;
		$dateOfBirth = null;
	
		if (isset($_POST['studentNumber'])) {
			$studentNumber = $_POST['studentNumber'];
		}
		
		$return_arr = array();
		$sqlStudent = "SELECT * FROM student WHERE studentNumber='".$studentNumber."'";					
		$fetch = mysqli_query($connect, $sqlStudent) or die (mysqli_error($connect));
		
		while ($row = mysqli_fetch_array($fetch, MYSQL_ASSOC)) {
			$row_array['studentId'] = $row['studentId'];
			$row_array['studentNumber'] = $row['studentNumber'];
			$row_array['firstName'] = $row['firstName'];
			$row_array['middleName'] = $row['middleName'];
			$row_array['lastName'] = $row['lastName'];
			$row_array['fullName'] = $row['fullName'];
			$row_array['dateOfBirth'] = $row['dateOfBirth'];
			array_push($return_arr,$row_array);
		}

		if (!empty($return_arr)) {
			echo '{"student":'.json_encode($return_arr).'}';
		}
		
		mysqli_close($connect);
	}
	
	function updateStudent() {
		global $connect;
		
		$studentNumber = null;
		$firstName = null;
		$middleName = null;
		$lastName = null;
		$fullName = null;
		$dateOfBirth = null;
		
		if (isset($_POST['studentNumber']) && isset($_POST['firstName']) && isset($_POST['middleName']) && isset($_POST['lastName']) && isset($_POST['dateOfBirth'])) {
			$studentNumber = $_POST['studentNumber'];
			$firstName = $_POST['firstName'];
			$middleName = $_POST['middleName'];
			$lastName = $_POST['lastName'];
			$dateOfBirth = $_POST['dateOfBirth'];
			$fullName = $firstName[0].$middleName[0]." ".$lastName;
		}
		
		$sqlStudent = "SELECT * FROM student WHERE studentNumber='".$studentNumber."'";					
		$fetch = mysqli_query($connect, $sqlStudent) or die (mysqli_error($connect));
		$row_count = mysqli_num_rows($fetch);
		
		if ($row_count > 0) {
			$row = mysqli_fetch_array($fetch, MYSQL_ASSOC);
			
			$sqlUpdateStudent = "UPDATE student SET firstName='".$firstName."', middleName='".$middleName."', lastName='".$lastName."', fullName='".$fullName."', dateOfBirth='".$dateOfBirth."' WHERE studentNumber='".$studentNumber."'";
			$sqlUpdateUser = "UPDATE user SET password='".$dateOfBirth."' WHERE username='".$studentNumber."'";
			
			mysqli_query($connect, $sqlUpdateStudent) or die (mysqli_error($connect));
			mysqli_query($connect, $sqlUpdateUser) or die (mysqli_error($connect));
			
			echo 'Student updated!';			
		} else {
			echo 'Student not found!';
		}
		
		mysqli_close($connect);
	}
	
	function deleteStudent() {
		global $connect;
		
		$studentNumber = null;
		$firstName = null;
		$middleName = null;
		$lastName = null;
		$fullName = null;
		$dateOfBirth = null;
		
		if (isset($_POST['studentNumber'])) {
			$studentNumber = $_POST['studentNumber'];
		}
		
		$sqlStudent = "SELECT * FROM student WHERE studentNumber='".$studentNumber."'";					
		$fetch = mysqli_query($connect, $sqlStudent) or die (mysqli_error($connect));
		$row_count = mysqli_num_rows($fetch);
		
		if ($row_count > 0) {
			$row = mysqli_fetch_array($fetch, MYSQL_ASSOC);
			
			$sqlDeleteStudent = "DELETE FROM student WHERE studentNumber='".$studentNumber."'";
			$sqlDeleteUser = "DELETE FROM user WHERE username='".$studentNumber."'";
			
			mysqli_query($connect, $sqlDeleteStudent) or die (mysqli_error($connect));
			mysqli_query($connect, $sqlDeleteUser) or die (mysqli_error($connect));
			
			echo 'Student deleted!';			
		} else {
			echo 'Student not found!';
		}
		
		mysqli_close($connect);
	}
	
	function guidv4($data) {
		assert(strlen($data) == 16);	
		$data[6] = chr(ord($data[6]) & 0x0f | 0x40); // set version to 0100
		$data[8] = chr(ord($data[8]) & 0x3f | 0x80); // set bits 6-7 to 10
	
		return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
	}
?>