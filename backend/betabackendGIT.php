<?php
	$con = mysqli_connect("sql.njit.edu","rjb57", PASSWORD");
	if (!$con) {
		die('Could not connect: ' . mysqli_error($con));	
	}
	mysqli_select_db($con,"rjb57");
	
	$postType = $_POST["postType"];
	switch ($postType) {
		case "login":
			//Login to database
			$username = $_POST['ucid'];
			$password = $_POST['pwd'];
			login($con,$username, $password);
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
			echo addQuestion($con,$question,$funcname,$params,$input,$output,$difficulty,$category);
			break;
		case "questionBank":
			//Request for Bank
			echo questionBank($con);
			break;
		case "createExam":
			//Create an exam
			$examName = $_POST['examName'];
			$examQuestions = $_POST['questions'];
			$pointValues = $_POST['pointValues'];
			echo createExam($con,$examName,$examQuestions,$pointValues);
			break;
		case "scores":
			//Returns student name, exam name, score for saved exam score
			echo scores($con);
			break;
		case "exams":
			//Returns exam names and ids
			echo exams($con);
			break;
		case "takeExam":
			//Returns only the questions of a specified exam
			$testName = $_POST['examName'];
			echo takeExam($con,$testName);
			break;
		case "studentScores":
			//Returns all exams that student has taken with score
			$ucid = $_POST['ucid'];
			echo studentScores($con,$ucid);
			break;
		case "submitExam":
			//Submits completed exam
			$examName = $_POST['examName'];
			$ucid = $_POST['ucid'];
			$answer = $_POST['answer'];
			echo submitExam($con, $examName, $ucid, $answer);
			break;
		default:
			echo json_encode(array('database'=>false,'log'=>"Incorrect postType: '$postType'"));
			break;
	}
	
	function login($con,$user,$pass) {
		//Function to log into DB
		$hpwd = hash("sha256", $pass);
			$username = mysqli_real_escape_string($con,$username);
			$sql = "SELECT * FROM rjb57.CS490 WHERE username='".$user."';";
			$result = mysqli_query($con,$sql);
			$rows = mysqli_fetch_array($result);
			if($rows['password']==$hpwd){
				$role = $rows['role'];
				echo json_encode(array('database'=>true,'role'=>$role));
			}
			else {
				echo json_encode(array('database'=>false));
			}
	}
	
	function addQuestion($con,$question,$funcname,$params,$input,$output,$difficulty,$category) {
		//Function to add question to test
		$question = mysqli_real_escape_string($con,$question);
		$funcname = mysqli_real_escape_string($con,$funcname);
		$params = mysqli_real_escape_string($con,$params);
		$input = mysqli_real_escape_string($con,$input);
		$output = mysqli_real_escape_string($con,$output);
		$difficulty = mysqli_real_escape_string($con,$difficulty);
		$category = mysqli_real_escape_string($con,$category);
		$sql = "INSERT INTO rjb57.CS490_QuestionBank (fullQuestion,funcName,params,input,output,difficulty,category) VALUES ('";
		$sql = $sql.$question."','$funcname','$params','$input','$output','$difficulty','$category');";
		mysqli_query($con,$sql);
		if ($result){
			return json_encode(array('database'=>'success','log'=>"Added question with function name $funcname."));
		}
	}
	
	function questionBank($con) {
			//Function to retrieve question bank from DB
			$sql = "SELECT * FROM rjb57.CS490_QuestionBank;";
			$result = mysqli_query($con,$sql);
			while($row = mysqli_fetch_assoc($result)){
				$graded[] = array('questionID'=>$row['questionID'], 'question'=>$row['fullQuestion'],
								'difficulty'=>$row['difficulty'], 'category'=>$row['category']);
			}
			$graded = json_encode($graded);
			return $graded;
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
	
	function createExam($con,$examName,$examQuestions,$pointVals) {
		//Function to add an exam to the DB
		$examName = mysqli_real_escape_string($con,$examName);
		$examQuestions = mysqli_real_escape_string($con,$examQuestions);
		$pointVals = mysqli_real_escape_string($con,$pointVals);
		$sql = "INSERT INTO rjb57.CS490_Exams (examName,questions,pointValues) VALUES ('$examName','$examQuestions','$pointVals');";
		$result = mysqli_query($con,$sql);
		if ($result){
			echo json_encode(array('database'=>'success','log'=>"Successfully created '$examName'"));
		}else{echo json_encode(array('database'=>'failure','log'=>"Failed creating '$examName'",'sql'=>$sql));}
	}
	
	function exams($con) {
		//Function to return exam names
		$sql = "SELECT * FROM rjb57.CS490_Exams;";
		$result = mysqli_query($con,$sql);
		if(mysqli_num_rows($result)>0){
			while($row=mysqli_fetch_assoc($result)) {
				$examJSON[] = array('examName'=>$row['examName'],'questions'=>$row['questions'],'points'=>$row['pointValues']);
			}
		}else {
				$examJSON=[];
			}
		return json_encode($examJSON);
	}
	function takeExam($con,$examName) {
			//Function to return questions from specified exam
			$examName = mysqli_real_escape_string($con,$examName);
			$sql = "SELECT * FROM rjb57.CS490_Exams WHERE examName='".$examName."';";
			$result = mysqli_query($con,$sql);
			$row = mysqli_fetch_assoc($result);
			$questions = $row['questions'];
			$sql = "SELECT fullQuestion FROM rjb57.CS490_QuestionBank WHERE questionID in ($questions);";
			$result = mysqli_query($con,$sql);
			while ($row = mysqli_fetch_assoc($result)) {
				$examQuestions[] = array('examQuestion'=>$row['fullQuestion']);
			}
			$examQuestions = json_encode($examQuestions);
			return $examQuestions;
		}
	function scores($con) {
			//Function to return all exam scores
			$sql = "SELECT * FROM rjb57.CS490_GradedExams;";
			$result = mysqli_query($con,$sql);
			if(mysqli_num_rows($result)>0){
				while($row = mysqli_fetch_assoc($result)){
					$graded[] = array('gradedID'=>$row['gradedID'], 'completedExamID'=>$row['completedExamID'],
									'ucid'=>$row['ucid'], 'pointsReceived'=>$row['pointsReceived'], 'reasons'=>$row['reasons'],
									'professorComments'=>$row['professorComments']);
				}
			}else {
				$graded=[];
			}
			$graded = json_encode($graded);
			return $graded;
		}
	
	function studentScores($con,$ucid) {
		//Function to return examName, examQuestions, questionScore, and overall score for the specified student
		$ucid = mysqli_real_escape_string($con,$ucid);
		$sql = "SELECT * FROM rjb57.CS490_GradedExams WHERE ucid='$ucid';";
		$result = mysqli_query($con,$sql);
		if(mysqli_num_rows($result)>0){
			while($row = mysqli_fetch_assoc($result)){
				$graded[] = array('gradedID'=>$row['gradedID'], 'completedExamID'=>$row['completedExamID'],
								'ucid'=>$row['ucid'], 'pointsReceived'=>$row['pointsReceived'], 'reasons'=>$row['reasons'],
								'professorComments'=>$row['professorComments']);
			}
		}else {
				$graded=[];
			}
		$graded = json_encode($graded);
		return $graded;
	}
	
	function submitExam($con,$examName,$ucid,$answer) {
		
		return json_encode(array('database'=>'success','log'=>"Successfully submitted $examName by $ucid"));
	}
	mysqli_close($con);
?>