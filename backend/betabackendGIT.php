<?php
	$con = mysqli_connect("sql.njit.edu","rjb57", PASSWORD);
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
			$loopkind = $_POST['looptype'];
			if($loopkind!="for" && $loopkind!="while" && $loopkind!="print"){
				$loopkind = "";
			}
			echo addQuestion($con,$question,$funcname,$params,$input,$output,$difficulty,$category,$loopkind);
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
		case "examScores":
			//Returns exam score results for certain exam
			$completedID = $_POST['completedExamID'];
			echo examScores($con, $completedID);
			break;
		case "submitExam":
			//Submits completed exam
			$examName = $_POST['examName'];
			$ucid = $_POST['ucid'];
			$answers = $_POST['answers'];
			echo submitExam($con, $examName, $ucid, $answers);
			break;
		case "gradingExam":
			//Returns fullQuestion,funcName,params with specified questionID
			$questionId = $_POST['questionID'];
			$examName = $_POST['examName'];
			echo gradingExam($con,$questionId,$examName);
			break;
		case "storeComment":
			$completedExamID = $_POST['completedExamID'];
			$questionID = $_POST['questionID'];
			$ucid = $_POST['ucid'];
			$pointsReceived = $_POST['pointsReceived'];
			$reasons = $_POST['reasons'];
			storeComment($con, $completedExamID, $questionID, $ucid, $pointsReceived, $reasons);
			break;
		case "storeGrade":
			$grade = $_POST['grade'];
			$ucid = $_POST['ucid'];
			storeGrade($con, $grade, $ucid);
			break;
		case "reviseScore":
			$comletedID = $_POST['completedExamID'];
			echo reviewScore($con, $comletedID);
			break;
		case "revisedScores":
			$profComments = $_POST['comments'];
			$newScore = $_POST['revisedScore'];
			$completedExamID = $_POST['completedExamID'];
			$reasons = $_POST['reasons'];
			professorComment($con, $completedExamID, $reasons, $newScore, $profComments);
			break;
		default:
			echo json_encode(array('database'=>false,'log'=>"Incorrect postType: '$postType'"));
			break;
	}
	
	function login($con,$user,$pass) {
		//Function to log into DB
		$hpwd = hash("sha256", $pass);
			$user = mysqli_real_escape_string($con,$user);
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
	
	function addQuestion($con,$question,$funcname,$params,$input,$output,$difficulty,$category,$looptype) {
		//Function to add question to test
		$question = mysqli_real_escape_string($con,$question);
		$funcname = mysqli_real_escape_string($con,$funcname);
		$params = mysqli_real_escape_string($con,$params);
		$input = mysqli_real_escape_string($con,$input);
		$output = mysqli_real_escape_string($con,$output);
		$difficulty = mysqli_real_escape_string($con,$difficulty);
		$category = mysqli_real_escape_string($con,$category);
		$looptype = mysqli_real_escape_string($con,$looptype);
		$sql = "INSERT INTO rjb57.CS490_QuestionBank (fullQuestion,funcName,params,input,output,difficulty,category,looptype) VALUES ('";
		$sql = $sql.$question."','$funcname','$params','$input','$output','$difficulty','$category','$looptype');";
		mysqli_query($con,$sql);
		if ($result){
			return json_encode(array('database'=>'success','log'=>"Added question with function name $funcname."));
		}else {
			return json_encode(array('database'=>'failure','log'=>'Something went wrong.','result'=>mysqli_error($con),'sql'=>$sql));
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
				$bank = $bank."\"looptype\":\"".$row['looptype']."\"}";
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
			$sql = "SELECT qb.questionID,qb.fullQuestion,qb.funcName,qb.params,e.pointValues FROM rjb57.CS490_QuestionBank qb,rjb57.CS490_Exams e WHERE questionID in ($questions)
			AND examName='$examName';";
			$result = mysqli_query($con,$sql);
			while ($row = mysqli_fetch_assoc($result)) {
				$examQuestions[] = array('questionID'=>$row['questionID'],'examQuestion'=>$row['fullQuestion'], 
										'funcName'=>$row['funcName'], 'params'=>$row['params'], 'pointValues'=>$row['pointValues']);
			}
			$examQuestions = json_encode($examQuestions);
			return $examQuestions;
		}
	function scores($con) {
			//Function to return all exam scores
			$sql = "SELECT ge.*,ce.examName,ce.answer FROM rjb57.CS490_GradedExams ge inner join CS490_CompletedExams ce on completedExamID=completedID;";
			$result = mysqli_query($con,$sql);
			if(mysqli_num_rows($result)>0){
				while($row = mysqli_fetch_assoc($result)){
					$graded[] = array('gradedID'=>$row['gradedID'],'questionID'=>$row['questionID'], 'completedExamID'=>$row['completedExamID'],
									'ucid'=>$row['ucid'], 'answer'=>$row['answer'], 'pointsReceived'=>$row['pointsReceived'], 'reasons'=>$row['reasons'],
									'professorComments'=>$row['professorComments'],'examName'=>$row['examName']);
				}
			}else {
				$graded=[];
			}
			$graded = json_encode($graded);
			return $graded;
		}
	function examScores($con,$completedID) {
		//Function to return certain exam exam scores
		$sql = "SELECT ge.*,ce.examName,ce.answer FROM rjb57.CS490_GradedExams ge inner join CS490_CompletedExams ce on completedExamID=completedID where completedExamID=$completedID;";
		$result = mysqli_query($con,$sql);
		if(mysqli_num_rows($result)>0){
			while($row = mysqli_fetch_assoc($result)){
				$graded[] = array('gradedID'=>$row['gradedID'],'questionID'=>$row['questionID'], 'completedExamID'=>$row['completedExamID'],
								'ucid'=>$row['ucid'], 'answer'=>$row['answer'], 'pointsReceived'=>$row['pointsReceived'], 'reasons'=>$row['reasons'],
								'professorComments'=>$row['professorComments'],'examName'=>$row['examName']);
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
		$sql = "SELECT ge.*,ce.examName,ce.answer FROM rjb57.CS490_GradedExams ge inner join CS490_CompletedExams ce on completedExamID=completedID WHERE ge.ucid='$ucid' and ge.released=1;";
		$result = mysqli_query($con,$sql);
		if(mysqli_num_rows($result)>0){
			while($row = mysqli_fetch_assoc($result)){
				$graded[] = array('gradedID'=>$row['gradedID'],'questionID'=>$row['questionID'], 'completedExamID'=>$row['completedExamID'],
								'ucid'=>$row['ucid'], 'answer'=>$row['answer'], 'pointsReceived'=>$row['pointsReceived'], 'reasons'=>$row['reasons'],
								'professorComments'=>$row['professorComments'],'examName'=>$row['examName']);
			}
		}else {
				$graded=array('log'=>'No values returned.','sql'=>$sql);
			}
		$graded = json_encode($graded);
		return $graded;
	}
	function submitExam($con,$examName,$ucid,$answer) {
		$examName = mysqli_real_escape_string($con,$examName);
		$ucid = mysqli_real_escape_string($con,$ucid);
		$answer = mysqli_real_escape_string($con,$answer);
		$sql = "INSERT INTO rjb57.CS490_CompletedExams (examName,ucid,answer) VALUES ('$examName','$ucid','$answer');";
		$result = mysqli_query($con,$sql);
		$getIDSQL = "SELECT MAX(completedID) FROM rjb57.CS490_CompletedExams;";
		$maxID = mysqli_fetch_assoc(mysqli_query($con,$getIDSQL))['MAX(completedID)'];
		if($result){
			return json_encode(array('database'=>'success','completedID'=>$maxID,'log'=>"Successfully submitted $examName by $ucid",'sql'=>$sql));
		}else {
			return json_encode(array('database'=>'failure','completedID'=>$maxID,'log'=>"Could not insert exam: $examName taken by $ucid",'sql'=>$sql));
		}
	}
	
	function gradingExam($con,$questionID,$examName) {
		$questionID = mysqli_real_escape_string($con,$questionID);
		$examName = mysqli_real_escape_string($con,$examName);
		$sql = "SELECT qb.*,e.examName,e.pointValues FROM rjb57.CS490_QuestionBank qb
		INNER JOIN rjb57.CS490_Exams e ON qb.questionID=qb.questionID
		WHERE qb.questionID=$questionID and e.examName='$examName';";
		$result = mysqli_query($con,$sql);
		if(mysqli_num_rows($result)>0){
			$row = mysqli_fetch_assoc($result);
			return json_encode(array('question'=>$row['fullQuestion'],
									'funcName'=>$row['funcName'],
									'params'=>$row['params'],
									'input'=>$row['input'],
									'output'=>$row['output'],
									'pointValues'=>$row['pointValues'],
									//'examName'=>$row['examName'],
									//'completedID'=>$row['completedID'],
									'looptype'=>$row['looptype'],
									'sql'=>$sql));
		}else {
			return json_encode(array('sql'=>$sql));
		}
	}
	//storeComment($con, '1', '1', 'rjb57', '30', 'Fake reasons');
	function storeComment($con,$completedExamID,$questionID,$ucid,$pointsReceived,$reasons) {
		$examName = mysqli_real_escape_string($con,$examName);
		$questionID = mysqli_real_escape_string($con,$questionID);
		$ucid = mysqli_real_escape_string($con,$ucid);
		$pointsReceived = mysqli_real_escape_string($con,$pointsReceived);
		$reasons = mysqli_real_escape_string($con,$reasons);
		$sql = "INSERT INTO rjb57.CS490_GradedExams (completedExamID, questionID, ucid, pointsReceived, reasons) VALUES ";
		$sql .= "('$completedExamID','$questionID','$ucid','$pointsReceived','$reasons');";
		$result = mysqli_query($con,$sql);
		echo json_encode(array('result'=>mysqli_error($con),'sql'=>$sql));
	}
	
	function storeGrade($con,$grade,$ucid) {
		$grade = mysqli_real_escape_string($con,$grade);
		$ucid = mysqli_real_escape_string($con,$ucid);
		$sql = "UPDATE rjb57.CS490 SET grade='$grade' WHERE username='$ucid';";
		mysqli_query($con,$sql);
		echo json_encode(array('result'=>mysqli_error($con),'sql'=>$sql));
	}
	
	function reviewScore($con,$comID) {
		$comID = mysqli_real_escape_string($con,$comID);
		$sql = "SELECT ge.*,ce.examName,ce.answer FROM rjb57.CS490_GradedExams ge inner join CS490_CompletedExams ce on completedExamID=completedID WHERE ge.completedExamID=$comID;";
		$result = mysqli_query($con,$sql);
		if(mysqli_num_rows($result)>0){
			while($row = mysqli_fetch_assoc($result)){
				$graded[] = array('gradedID'=>$row['gradedID'],'questionID'=>$row['questionID'],'answer'=>$row['answer'], 'completedExamID'=>$row['completedExamID'],
								'ucid'=>$row['ucid'], 'pointsReceived'=>$row['pointsReceived'], 'reasons'=>$row['reasons'],
								'professorComments'=>$row['professorComments'],'examName'=>$row['examName'],'sql'=>$sql);
			}
		}else {
				$graded=array('error'=>"completedExamID:'$comletedID' not specified or not the right format.",'sql'=>$sql);
			}
		$graded = json_encode($graded);
		return $graded;
	}
	
	function professorComment($con,$completedExamID,$reasons,$newScore,$profComments) {
		$gradedID = mysqli_real_escape_string($con,$gradedID);
		$profComments = mysqli_real_escape_string($con,$profComments);
		$newScore = mysqli_real_escape_string($con,$newScore);
		$sql = "UPDATE rjb57.CS490_GradedExams SET professorComments='$profComments' and reasons='$reasons' and pointsReceived=$newScore and released=1 WHERE completedExamID=$completedExamID;";
		echo json_encode(array('result'=>mysqli_error($con),'sql'=>$sql));
	}
	
	mysqli_close($con);
	/*$graded[] = array('gradedID'=>$row['gradedID'], 'completedExamID'=>$row['completedExamID'],
					'ucid'=>$row['ucid'], 'pointsReceived'=>$row['pointsReceived'], 'reasons'=>$row['reasons'],
					'professorComments'=>$row['professorComments'],'examName'=>$row['examName'],'questionID'=>$row['questionID']);*/
?>