<?php
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		require 'connection.php';
		
		if (isset($_POST['message'])) {
			$message = $_POST['message'];
			
			if ($message == 'Create') {
				createGrade();
			} else if ($message == 'Read') {
				readGrade();
			} else if ($message == 'Update') {
				updateGrade();
			} else if ($message == 'Delete') {
				deleteGrade();
			} else if ($message == 'Fetch student name') {
				fetchStudent();
			} else if ($message == 'Read one grade') {
				readOneGrade();
			} else if ($message == 'Update remarks') {
				updateRemarks();
			}
		}
	}
	
	function fetchStudent() {
		global $connect;
		
		$studentNumber;
		
		if (isset($_POST['studentNumber'])) {
			$studentNumber = $_POST['studentNumber'];
		}
		
		$return_arr = array();
		$sqlStudent = "SELECT fullName FROM student WHERE studentNumber = '".$studentNumber."'";
		$fetchStudent = mysqli_query($connect, $sqlStudent) or die (mysqli_error($connect));
		
		$row_count = mysqli_num_rows($fetchStudent);
		
		if ($row_count > 0) {
			while ($rowStudent = mysqli_fetch_array($fetchStudent)) {
				$row_array['fullName'] = $rowStudent['fullName'];
			}
			array_push($return_arr,$row_array);
			
			echo '{"student":'.json_encode($return_arr).'}';
		}
		mysqli_close($connect);
	}
	
	function createGrade() {
		global $connect;
		$date = date('Y-m-d H:i:s');
		
		$gradeId = null;
		$studentId = null;
		$facultyId = null;
		$studentNumber = $_POST['studentNumber'];
		$studentName = $_POST['studentName'];
		$subject = $_POST['subject'];
		$section = $_POST['section'];
		$schoolYear = $_POST['schoolYear'];
		$semester = $_POST['semester'];
		$prelim = $_POST['prelim'];
		$midterm = $_POST['midterm'];
		$preFinals = $_POST['preFinals'];
		$finals = $_POST['finals'];
		$finalGrade = $_POST['finalGrade'];
		$prelim = $_POST['prelim'];
		$midterm = $_POST['midterm'];
		$preFinals = $_POST['preFinals'];
		$finals = $_POST['finals'];
		$finalGrade = $_POST['finalGrade'];
		$facultyId = $_POST['facultyId'];
		
		if ($studentNumber == '' || $studentName == '') {
			exit('Student Number is required.');
		}
		
		if ($subject == '') {
			exit('Subject is required.');
		}
		
		if ($section == '') {
			exit('Section is required.');
		}
		
		if ($schoolYear == '') {
			exit('School Year is required.');
		}
		
		if ($semester == '') {
			exit('Semester is required.');
		}
			
		
		$sqlStudent = "SELECT studentId FROM student WHERE studentNumber = '".$studentNumber."'";
		$fetchStudent = mysqli_query($connect, $sqlStudent) or die (mysqli_error($connect));
		$rowStudent = mysqli_fetch_array($fetchStudent);
		
		$studentId = $rowStudent['studentId'];
		$gradeId = guidv4(openssl_random_pseudo_bytes(16));
		
		$sqlGrade = "INSERT INTO grade (gradeId, schoolYear, semester, section, subject, prelim, midterm, preFinals, finals, finalGrade, studentId, facultyId)
				VALUES ('".$gradeId."', '".$schoolYear."', '".$semester."', '".$section."', '".$subject."', '".$prelim."', '".$midterm."', '".$preFinals."', '".$finals."', '".$finalGrade."', '".$studentId."', '".$facultyId."')";
				
		mysqli_query($connect, $sqlGrade) or die (mysqli_error($connect));
		mysqli_close($connect);
		
		echo 'Grade created!';
	}
	
	function readGrade() {
		global $connect;
		
		$facultyId = null;
		
		if (isset($_POST['facultyId'])) {
			$facultyId = $_POST['facultyId'];
		}
		
		$return_arr = array();
		$sqlGrade = "SELECT * FROM faculty a JOIN grade b ON a.facultyId = b.facultyId WHERE a.facultyId = '".$facultyId."'";					
		$fetchGrade = mysqli_query($connect, $sqlGrade) or die (mysqli_error($connect));
		
		while ($row = mysqli_fetch_array($fetchGrade, MYSQL_ASSOC)) {
			$row_array['gradeId'] = $row['gradeId'];
			$row_array['facultyId'] = $row['facultyId'];
			$row_array['employeeNumber'] = $row['employeeNumber'];
			$row_array['fullName'] = $row['fullName'];
			$row_array['dateOfBirth'] = $row['dateOfBirth'];
			$row_array['schoolYear'] = $row['schoolYear'];
			$row_array['semester'] = $row['semester'];
			$row_array['section'] = $row['section'];
			$row_array['subject'] = $row['subject'];
			$row_array['midterm'] = $row['midterm'];
			$row_array['finals'] = $row['finals'];
			$row_array['studentRemarks'] = $row['studentRemarks'];					
			
			$sqlStudent = "SELECT fullName, studentNumber FROM student WHERE studentId = '".$row['studentId']."'";
			$fetchStudent = mysqli_query($connect, $sqlStudent);
			
			while ($rowFaculty = mysqli_fetch_array($fetchStudent)) {
				$row_array['studentName'] = $rowFaculty['fullName'];
			}
			array_push($return_arr,$row_array);
		}

		if (!empty($return_arr)) {
			echo '{"grade":'.json_encode($return_arr).'}';
		}
		
		mysqli_close($connect);
	}
	
	function readOneGrade() {
		global $connect;
		
		$gradeId = $_POST['gradeId'];
		
		$return_arr = array();
		$sqlGrade = "SELECT * FROM grade WHERE gradeId = '".$gradeId."'";					
		$fetchGrade = mysqli_query($connect, $sqlGrade) or die (mysqli_error($connect));
		
		while ($row = mysqli_fetch_array($fetchGrade, MYSQL_ASSOC)) {
			$row_array['gradeId'] = $row['gradeId'];
			$row_array['schoolYear'] = $row['schoolYear'];
			$row_array['semester'] = $row['semester'];
			$row_array['section'] = $row['section'];
			$row_array['subject'] = $row['subject'];
			$row_array['prelim'] = $row['prelim'];
			$row_array['midterm'] = $row['midterm'];
			$row_array['preFinals'] = $row['preFinals'];
			$row_array['finals'] = $row['finals'];
			$row_array['finalGrade'] = $row['finalGrade'];
			$row_array['studentRemarks'] = $row['studentRemarks'];
			
			$sqlStudent = "SELECT fullName, studentNumber FROM student WHERE studentId = '".$row['studentId']."'";
			$fetchStudent = mysqli_query($connect, $sqlStudent);
			
			while ($rowFaculty = mysqli_fetch_array($fetchStudent)) {
				$row_array['studentNumber'] = $rowFaculty['studentNumber'];
				$row_array['studentName'] = $rowFaculty['fullName'];
			}
			array_push($return_arr,$row_array);
		}

		if (!empty($return_arr)) {
			echo '{"grade":'.json_encode($return_arr).'}';
		}
		
		mysqli_close($connect);
	}
	
	function updateGrade() {
		global $connect;
		
		$gradeId = $_POST['gradeId'];
		$studentNumber = $_POST['studentNumber'];
		$studentName = $_POST['studentName'];
		$subject = $_POST['subject'];
		$section = $_POST['section'];
		$schoolYear = $_POST['schoolYear'];
		$semester = $_POST['semester'];
		$prelim = $_POST['prelim'];
		$midterm = $_POST['midterm'];
		$preFinals = $_POST['preFinals'];
		$finals = $_POST['finals'];
		$finalGrade = $_POST['finalGrade'];
		
		if ($studentNumber == '' || $studentName == '') {
			exit('Student Number is required.');
		}
		
		if ($subject == '') {
			exit('Subject is required.');
		}
		
		if ($section == '') {
			exit('Section is required.');
		}
		
		if ($schoolYear == '') {
			exit('School Year is required.');
		}
		
		if ($semester == '') {
			exit('Semester is required.');
		}
		
		$sqlStudent = "SELECT studentId FROM student WHERE studentNumber = '".$studentNumber."'";
		$fetchStudent = mysqli_query($connect, $sqlStudent) or die (mysqli_error($connect));
		$rowStudent = mysqli_fetch_array($fetchStudent);
		
		$studentId = $rowStudent['studentId'];
		
		$sqlUpdateGrade = "UPDATE grade SET schoolYear='".$schoolYear."', semester='".$semester."', section='".$section."', subject='".$subject."', prelim='".$prelim."', midterm='".$midterm."', preFinals='".$preFinals."', finals='".$finals."', finalGrade='".$finalGrade."', studentId='".$studentId."'
			WHERE gradeId='".$gradeId."'";
		
		mysqli_query($connect, $sqlUpdateGrade) or die (mysqli_error($connect));
		mysqli_close($connect);
		
		echo 'Record updated!';
	}
	
	function updateRemarks() {
		global $connect;
		
		$gradeId = $_POST['gradeId'];
		$studentRemarks = $_POST['studentRemarks'];
		
		$sqlUpdateGrade = "UPDATE grade SET studentRemarks='".$studentRemarks."' WHERE gradeId='".$gradeId."'";
		
		mysqli_query($connect, $sqlUpdateGrade) or die (mysqli_error($connect));
		mysqli_close($connect);
		
		echo 'Remarks saved!';
	}
	
	function deleteGrade() {
		global $connect;
		
		$gradeId = null;
		
		if (isset($_POST['gradeId'])) {
			$gradeId = $_POST['gradeId'];
		}
		
		$sqlGrade = "SELECT * FROM grade WHERE gradeId='".$gradeId."'";					
		$fetch = mysqli_query($connect, $sqlGrade) or die (mysqli_error($connect));
		$row_count = mysqli_num_rows($fetch);
		
		if ($row_count > 0) {
			$row = mysqli_fetch_array($fetch, MYSQL_ASSOC);
			
			$sqlDeleteStudent = "DELETE FROM grade WHERE gradeId='".$gradeId."'";
			
			mysqli_query($connect, $sqlDeleteStudent) or die (mysqli_error($connect));
			
			echo 'Grade deleted!';			
		} else {
			echo 'Grade not found!';
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