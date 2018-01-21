<?php
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {		
		require 'connection.php';
		
		if (isset($_POST['message'])) {
			$message = $_POST['message'];
			
			if ($message == 'Create') {
				createFaculty();
			} else if ($message == 'Search') {
				readFaculty();
			} else if ($message == 'Update') {
				updateFaculty();
			} else if ($message == 'Delete') {
				deleteFaculty();
			}
		}
	}
	
	function createFaculty() {
		global $connect;
		date_default_timezone_set('Asia/Manila');
		$date = date('Y-m-d H:i:s');
		
		$employeeNumber = null;
		$firstName = null;
		$middleName = null;
		$lastName = null;
		$fullName = null;
		$dateOfBirth = null;
	
		if (isset($_POST['employeeNumber']) && isset($_POST['firstName']) && isset($_POST['middleName']) && isset($_POST['lastName']) && isset($_POST['dateOfBirth'])) {
			$facultyId = guidv4(openssl_random_pseudo_bytes(16));
			$employeeNumber = $_POST['employeeNumber'];
			$firstName = $_POST['firstName'];
			$middleName = $_POST['middleName'];
			$lastName = $_POST['lastName'];
			$dateOfBirth = $_POST['dateOfBirth'];
			$fullName = $firstName[0].$middleName[0]." ".$lastName;
		}
		
		$sqlFaculty = "SELECT * FROM faculty WHERE employeeNumber='".$employeeNumber."'";					
		$fetch = mysqli_query($connect, $sqlFaculty) or die (mysqli_error($connect));
		$row_count = mysqli_num_rows($fetch);
		
		if ($row_count > 0) {
			echo 'Faculty already exists!';			
		} else {
			$sqlFaculty = "INSERT INTO faculty (facultyId, employeeNumber, firstName, middleName, lastName, fullName, dateOfBirth, createdOn)
				VALUES ('".$facultyId."', '".$employeeNumber."', '".$firstName."', '".$middleName."', '".$lastName."', '".$fullName."', '".$dateOfBirth."', '".$date."')";
		
			$sqlUser = "INSERT INTO user (userId, username, password, studentId, facultyId, createdOn)
				VALUES ('".guidv4(openssl_random_pseudo_bytes(16))."', '".$employeeNumber."', '".$dateOfBirth."', '', '".$facultyId."', '".$date."')";
					
			mysqli_query($connect, $sqlFaculty) or die (mysqli_error($connect));
			mysqli_query($connect, $sqlUser) or die (mysqli_error($connect));
			mysqli_close($connect);
			echo 'Faculty record created!';
		}
	}
	
	function readFaculty() {
		global $connect;
		
		$employeeNumber = null;
		$firstName = null;
		$middleName = null;
		$lastName = null;
		$fullName = null;
		$dateOfBirth = null;
	
		if (isset($_POST['employeeNumber'])) {
			$employeeNumber = $_POST['employeeNumber'];
		}
		
		$return_arr = array();
		$sqlFaculty = "SELECT * FROM faculty WHERE employeeNumber='".$employeeNumber."'";					
		$fetch = mysqli_query($connect, $sqlFaculty) or die (mysqli_error($connect));
		
		while ($row = mysqli_fetch_array($fetch, MYSQL_ASSOC)) {
			$row_array['facultyId'] = $row['facultyId'];
			$row_array['employeeNumber'] = $row['employeeNumber'];
			$row_array['firstName'] = $row['firstName'];
			$row_array['middleName'] = $row['middleName'];
			$row_array['lastName'] = $row['lastName'];
			$row_array['fullName'] = $row['fullName'];
			$row_array['dateOfBirth'] = $row['dateOfBirth'];
			array_push($return_arr,$row_array);
		}

		if (!empty($return_arr)) {
			echo '{"faculty":'.json_encode($return_arr).'}';
		}
		
		mysqli_close($connect);
	}
	
	function updateFaculty() {
		global $connect;
		
		$employeeNumber = null;
		$firstName = null;
		$middleName = null;
		$lastName = null;
		$fullName = null;
		$dateOfBirth = null;
		
		if (isset($_POST['employeeNumber']) && isset($_POST['firstName']) && isset($_POST['middleName']) && isset($_POST['lastName']) && isset($_POST['dateOfBirth'])) {
			$employeeNumber = $_POST['employeeNumber'];
			$firstName = $_POST['firstName'];
			$middleName = $_POST['middleName'];
			$lastName = $_POST['lastName'];
			$dateOfBirth = $_POST['dateOfBirth'];
			$fullName = $firstName[0].$middleName[0]." ".$lastName;
		}
		
		$sqlFaculty = "SELECT * FROM faculty WHERE employeeNumber='".$employeeNumber."'";					
		$fetch = mysqli_query($connect, $sqlFaculty) or die (mysqli_error($connect));
		$row_count = mysqli_num_rows($fetch);
		
		if ($row_count > 0) {
			$row = mysqli_fetch_array($fetch, MYSQL_ASSOC);
			
			$sqlUpdateFaculty = "UPDATE faculty SET firstName='".$firstName."', middleName='".$middleName."', lastName='".$lastName."', fullName='".$fullName."', dateOfBirth='".$dateOfBirth."' WHERE employeeNumber='".$employeeNumber."'";
			$sqlUpdateUser = "UPDATE user SET password='".$dateOfBirth."' WHERE username='".$employeeNumber."'";
			
			mysqli_query($connect, $sqlUpdateFaculty) or die (mysqli_error($connect));
			mysqli_query($connect, $sqlUpdateUser) or die (mysqli_error($connect));
			
			echo 'Faculty updated!';			
		} else {
			echo 'Faculty not found!';
		}
		
		mysqli_close($connect);
	}
	
	function deleteFaculty() {
		global $connect;
		
		$employeeNumber = null;
		$firstName = null;
		$middleName = null;
		$lastName = null;
		$fullName = null;
		$dateOfBirth = null;
		
		if (isset($_POST['employeeNumber'])) {
			$employeeNumber = $_POST['employeeNumber'];
		}
		
		$sqlFaculty = "SELECT * FROM faculty WHERE employeeNumber='".$employeeNumber."'";					
		$fetch = mysqli_query($connect, $sqlFaculty) or die (mysqli_error($connect));
		$row_count = mysqli_num_rows($fetch);
		
		if ($row_count > 0) {
			$row = mysqli_fetch_array($fetch, MYSQL_ASSOC);
			
			$sqlDeleteFaculty = "DELETE FROM faculty WHERE employeeNumber='".$employeeNumber."'";
			$sqlDeleteUser = "DELETE FROM user WHERE username='".$employeeNumber."'";
			
			mysqli_query($connect, $sqlDeleteFaculty) or die (mysqli_error($connect));
			mysqli_query($connect, $sqlDeleteUser) or die (mysqli_error($connect));
			
			echo 'Faculty deleted!';			
		} else {
			echo 'Faculty not found!';
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