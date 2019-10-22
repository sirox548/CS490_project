<?php
	$postType = $_POST["postType"];
	
	switch ($postType) {
		case "login":
			//Login to database
			$username = $_POST['ucid'];
			$password = $_POST['pwd'];
			login($username, $password);
			break;
		case "addQuestion":
			//Add question to test
			$question = $_POST['question'];//full question
			$funcname = $_POST['funcName'];
			$params = $_POST['params'];
			$input = $_POST['input'];
			$output = $_POST['output'];
			$difficulty = $_POST['difficulty'];
			$category = $_POST['category'];
			addQuestion($question,$funcname,$params,$input,$output,$difficulty,$category);
			break;
		case "questionBank":
			//Request for Bank
			questionBank();
			break;
		case "createExam":
			//Create an exam
			$examName = $_POST['examName'];
			$examQuestions = $_POST['examQuestions'];
			createExam($examName,$examQuestions);
			break;
		case "scores":
			//Returns student name, exam name, score for saved exam score
			scores();
			break;
		case "exams":
			//Returns exam names and ids
			exams();
			break;
		case "studentScores":
			//Returns all exams that student has taken with score
			$ucid = $_POST['ucid'];
			studentScores($ucid);
		default:
			echo "{\"database\":false,\"error\":\"No postType specified\"}";
			break;
	}
	
	
	function login($user,$pass) {
		//Function to log into DB
		
		$con = mysqli_connect("sql.njit.edu","rjb57", PASSWORD);
		$hpwd = hash("sha256", $pass);
			if (!$con) {
				die('Could not connect: ' . mysqli_error($con));
			}
			
			mysqli_select_db($con,"rjb57");
			$username = mysqli_real_escape_string($con,$username);
			$sql = "SELECT * FROM rjb57.CS490 WHERE username='".$user."';";
			$result = mysqli_query($con,$sql);
			$rows = mysqli_fetch_array($result);
			if($rows['password']==$hpwd){
				$role = $rows['role'];
				echo "{\"database\":true,\"role\":\"".$role."\"}";
			}
			else {
				echo "{\"database\":false}";
			}
		mysqli_close($con);
	}
	
	function addQuestion($question,$funcname,$params,$input,$output,$difficulty,$category) {
		//Function to add question to test
		$con = mysqli_connect("sql.njit.edu","rjb57", PASSWORD);
		if (!$con) {
			die('Could not connect: ' . mysqli_error($con));	
		}
		
		mysqli_select_db($con,"rjb57");
		$question = mysqli_real_escape_string($con,$question);
		$funcname = mysqli_real_escape_string($con,$funcname);
		$params = mysqli_real_escape_string($con,$params);
		$input = mysqli_real_escape_string($con,$input);
		$output = mysqli_real_escape_string($con,$output);
		$difficulty = mysqli_real_escape_string($con,$difficulty);
		$category = mysqli_real_escape_string($con,$category);
		$sql = "INSERT INTO rjb57.CS490_QuestionBank (fullQuestion,funcName,params,input,output,difficulty,category) VALUES ('";
		$sql = $sql.$question."','".$funcname."','".$params."','".$input."','".$output."','".$difficulty."','".$category."');";
		mysqli_query($con,$sql);
		mysqli_close($con);
	}
	
	function questionBank() {
			//Function to retrieve question bank from DB
			$con = mysqli_connect("sql.njit.edu","rjb57", PASSWORD);
			if (!$con) {
				die('Could not connect: ' . mysqli_error($con));	
			}
			
			mysqli_select_db($con,"rjb57");
			$sql = "SELECT * FROM rjb57.CS490_QuestionBank;";
			$result = mysqli_query($con,$sql);
			$rows = mysqli_fetch_all($result);
			$ret = "[";
			foreach($rows as $row) {
				$ret = $ret."{\"questionID\":".$row[0].",";
				$ret = $ret."\"question\":\"".$row[1]."\",";
				$ret = $ret."\"difficulty\":\"".$row[6]."\",";
				$ret = $ret."\"category\":\"".$row[7]."\"}";
				$ret = $ret.",";
			}
			$ret = $ret."]";
			$ret = str_replace(",]", "]", $ret);
			echo $ret;
			mysqli_close($con);
		}
	
	function createExam($examName,$examQuestions) {
		//Function to add an exam to the DB
		$con = mysqli_connect("sql.njit.edu","rjb57", PASSWORD);
		if (!$con) {
			die('Could not connect: ' . mysqli_error($con));	
		}
		
		mysqli_select_db($con,"rjb57");
		$sql = "INSERT INTO rjb57.CS490_Exams (examName,questions) VALUES ('".$examName."','".$examQuestions."');";
		mysqli_query($con,$sql);
		
		mysqli_close($con);
	}
	
	function scores() {
		//Function to return all exam scores
		$con = mysqli_connect("sql.njit.edu","rjb57", PASSWORD);
		if (!$con) {
			die('Could not connect: ' . mysqli_error($con));	
		}
		
		mysqli_select_db($con,"rjb57");
		$sql = "SELECT * FROM rjb57.CS490 WHERE username='".$username."';";
		
		mysqli_close($con);
	}
	
	function exams() {
		//Function to return exam names
		$con = mysqli_connect("sql.njit.edu","rjb57", PASSWORD);
		if (!$con) {
			die('Could not connect: ' . mysqli_error($con));	
		}
		
		mysqli_select_db($con,"rjb57");
		$sql = "SELECT * FROM rjb57.CS490 WHERE username='".$username."';";
		
		mysqli_close($con);
	}
	
	function studentScores($ucid) {
		//Function to return examName, examQuestions, questionScore, and overall score for the specified student
		$con = mysqli_connect("sql.njit.edu","rjb57", PASSWORD);
		if (!$con) {
			die('Could not connect: ' . mysqli_error($con));	
		}
		
		mysqli_select_db($con,"rjb57");
		$sql = "SELECT * FROM rjb57.CS490 WHERE username='".$username."';";
		
		mysqli_close($con);
	}
?>
