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
		default:
			break;
	}
	$DB_link = "sql.njit.edu";
	$DB_username = "rjb57";
	$DB_password = PASSWORD;
	
	
	function login($username,$password) {
		//Function to log into DB
		$con = mysqli_connect($DB_link,$DB_username, $DB_password);
		$hpwd = hash("sha256", $hpwd);
			if (!$con) {
				die('Could not connect: ' . mysqli_error($con));	
			}
			
			mysqli_select_db($con,"rjb57");
			$sql = "SELECT * FROM rjb57.CS490 WHERE username='".$username."';";
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
		$con = mysqli_connect($DB_link,$DB_username, $DB_password);
		if (!$con) {
			die('Could not connect: ' . mysqli_error($con));	
		}
		
		mysqli_select_db($con,"rjb57");
		$sql = "SELECT * FROM rjb57.CS490 WHERE username='".$username."';";
		
		
		mysqli_close($con);
	}
	
	function questionBank() {
		//Function to retrieve question bank from DB
		$con = mysqli_connect($DB_link,$DB_username, $DB_password);
		if (!$con) {
			die('Could not connect: ' . mysqli_error($con));	
		}
		
		mysqli_select_db($con,"rjb57");
		$sql = "SELECT * FROM rjb57.CS490 WHERE username='".$username."';";
		
		mysqli_close($con);
	}
	
	function createExam($examName,$examQuestions) {
		//Function to add an exam to the DB
	}
	
?>
