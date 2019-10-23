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
			echo questionBank();
			break;
		case "createExam":
			//Create an exam
			$examName = $_POST['examName'];
			$examQuestions = $_POST['questions'];
			createExam($examName,$examQuestions);
			break;
		case "scores":
			//Returns student name, exam name, score for saved exam score
			echo scores();
			break;
		case "exams":
			//Returns exam names and ids
			echo exams();
			break;
		case "studentScores":
			//Returns all exams that student has taken with score
			$ucid = $_POST['ucid'];
			echo studentScores($ucid);
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
			mysqli_close($con);
			if ($result){
				$rows = mysqli_fetch_all_alt($result);
			}
			$bank = "[";
			foreach($rows as $row) {
				$bank = $bank."{\"questionID\":".$row['questionID'].",";
				$bank = $bank."\"question\":\"".$row['fullQuestion']."\",";
				$bank = $bank."\"difficulty\":\"".$row['difficulty']."\",";
				$bank = $bank."\"category\":\"".$row['category']."\"}";
				$bank = $bank.",";
			}
			$bank = $bank."]";
			$bank = str_replace(",]", "]", $bank);
			return $bank;
		}
	
	function createExam($examName,$examQuestions) {
		//Function to add an exam to the DB
		$con = mysqli_connect("sql.njit.edu","rjb57", PASSWORD);
		if (!$con) {
			die('Could not connect: ' . mysqli_error($con));	
		}
		
		mysqli_select_db($con,"rjb57");
		$sql = "INSERT INTO rjb57.CS490_Exams (examName,questions) VALUES ('".$examName."','".$examQuestions."');";
		//$result = mysqli_query($con,$sql);
		//if ($result){
			//echo "{\"database\":\"success\",\"log\":\"Successfully created ".$examName."\"}";
			echo "{\"sql\":\"".$sql."\"}";
		//}
		mysqli_close($con);
	}
	
	function exams() {
		//Function to return exam names
		$con = mysqli_connect("sql.njit.edu","rjb57", PASSWORD);
		if (!$con) {
			die('Could not connect: ' . mysqli_error($con));	
		}
		mysqli_select_db($con,"rjb57");
		$sql = "SELECT * FROM rjb57.CS490_Exams;";
		$result = mysqli_query($con,$sql);
		mysqli_close($con);
		if ($result){
			$rows = mysqli_fetch_all_alt($result);
		}
		$examJSON = "[";
		foreach ($rows as $row) {
			$examJSON = $examJSON."{\"examName\":\"".$row['examName']."\",";
			$examJSON = $examJSON."\"questions\":\"".$row['questions']."\"}";
			$examJSON = $examJSON.",";
		}
		$examJSON = $examJSON."]";
		$examJSON = str_replace(",]", "]", $examJSON);
		return $examJSON;
	}
	
	function examQuestions($examName) {
		//Function to return questions from specified exam		
		$con = mysqli_connect("sql.njit.edu","rjb57", PASSWORD);
		if (!$con) {
			die('Could not connect: ' . mysqli_error($con));	
		}
		mysqli_select_db($con,"rjb57");
		$sql = "SELECT * FROM rjb57.CS490_Exams WHERE examName=".$examName.";";
		$result = mysqli_query($con,$sql);
		mysqli_close($con);
		$row = mysqli_fetch_array($result);
		$questions = $row['questions'];
		$examQuestions = "[";
		foreach ($rows as $row) {
			$examQuestions = $examQuestions."{\"examQuestion\":\"".$row['exam']."\"}";
			$examQuestions = $examQuestions.",";
		}
		$examQuestions = $examQuestions."]";
		$examQuestions = str_replace(",]", "]", $examQuestions);
		return $examQuestions;
	}
	
	function scores() {
			//Function to return all exam scores
			$con = mysqli_connect("sql.njit.edu","rjb57", PASSWORD);
			if (!$con) {
				die('Could not connect: ' . mysqli_error($con));	
			}
			
			mysqli_select_db($con,"rjb57");
			$sql = "SELECT * FROM rjb57.CS49;";
			$result = mysqli_query($con,$sql);
			mysqli_close($con);
			if($result){
				$rows = mysqli_fetch_all_alt($result);
			}
			$graded = "[";
			//** Create json array **
			$graded = $graded."]";
			$graded = str_replace(",]", "]", $graded);
			return $graded;
		}
	
	function studentScores($ucid) {
		//Function to return examName, examQuestions, questionScore, and overall score for the specified student
		$con = mysqli_connect("sql.njit.edu","rjb57", PASSWORD);
		if (!$con) {
			die('Could not connect: ' . mysqli_error($con));	
		}
		
		mysqli_select_db($con,"rjb57");
		$sql = "SELECT * FROM rjb57.CS490 WHERE username='".$username."';";
		$result = mysqli_query($con,$sql);
		mysqli_close($con);
		if ($result){
			$rows = mysqli_fetch_all_alt($result);
		}
		$graded = "[";
		//** Create json array **
		$graded = $graded."]";
		$graded = str_replace(",]", "]", $graded);
		return $graded;
	}
	
	function mysqli_fetch_all_alt($result) {
		while($row = mysqli_fetch_assoc($result)){
			$rows[] = $row;
		}
		return $rows;
	}
?>
