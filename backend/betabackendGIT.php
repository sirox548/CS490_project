<?php
	$postType = $_POST["postType"];
	
	switch ($postType) {
		case "login":
			//Login to database
			$username = $_POST['ucid'];
			$password = $_POST['pwd'];
			break;
		case "addQuestion":
			//Add question to test
			$question = $_POST['question'];
			$funcname = $_POST['funcName'];
			$params = $_POST['params'];
			$input = $_POST['input'];
			$output = $_POST['output'];
			$difficulty = $_POST['difficulty'];
			$category = $_POST['category'];
			break;
		case "questionBank":
			//Request for Bank
			break;
		default:
			break;
	}
	
	function login($username,$password) {
		//Function to log into DB
		$hpwd = hash("sha256", $hpwd);
			$con = mysqli_connect("sql.njit.edu","rjb57", PASSWORD);
			if (!$con) {
				die('Could not connect: ' . mysqli_error($con));	
			}
			
			mysqli_select_db($con,"rjb57");
			$sql = "SELECT * FROM rjb57.CS490 WHERE username='".$username."';";
			$result = mysqli_query($con,$sql);
			$rows = mysqli_fetch_array($result);
			if($rows['password']==$hpwd){
				$role = $rows['role'];
				echo "{\"database\":\"Can login to database\",\"role\":\"".$role."\"}";
			}
			else {
				echo "{\"database\":\"Cannot login to database\"}";
			}
	}
	
	function addQuestion() {
		//Function to add question to test
		
	}
	
	function questionBank() {
		//Function to retrieve question bank from DB
		
	}
	
?>