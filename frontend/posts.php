<?php
	$postType = $_POST ["postType"];

	$username = "none";
	$password = "none";
	$question = "none";
	$funcName = "none";
	$params = "none";
	$input = "none";
	$output = "none";
	$difficulty = "none";
	$category = "none";
	$examName = "none";
	$examQuestions = "none";
	$studentName = "none";
	
	if ( isset($_POST['ucid'])){ $username=$_POST['ucid'];}
	if ( isset($_POST['pwd'])){ $password=$_POST['pwd'];}    
	if ( isset($_POST['question'])){ $question=$_POST['question'];}
	if ( isset($_POST['funcName'])){ $funcName=$_POST['funcName'];}
	if ( isset($_POST['params'])){ $params=$_POST['params'];}
	if ( isset($_POST['input'])){ $input=$_POST['input'];}
	if ( isset($_POST['output'])){ $output=$_POST['output'];}
	if ( isset($_POST['difficulty'])){ $difficulty=$_POST['difficulty'];}
	if ( isset($_POST['category'])){ $category=$_POST['category'];}
	if ( isset($_POST['examName'])){ $difficulty=$_POST['examName'];}
	if ( isset($_POST['examQuestions'])){ $category=$_POST['examQuestions'];}

	//post type must be implemented on back and middle 
	// if ($postType == "login"){
	// 	$stringdata =  array('postType'=> $postType, 'ucid'=> $username, 'pwd' => $password);
	// }
	if ($postType == "login"){
		$stringdata =  array('ucid'=> $username, 'pwd' => $password);
	}
	elseif ($postType == "addQuestion") {
		//Should add a wustion in the database
		$stringdata =  array('postType'=> $postType, 'question'=> $question, 'funcName' => $funcName, 'params'=> $params, 'input' => $input,'output' => $output, 'difficulty' => $difficulty, 'category' => $category );
	}
	elseif ($postType == "questionBank") {
		//should return all questions saved in the databade
		$stringdata =  array('postType'=> $postType);
	}
	elseif ($postType == "createExam") {
		//Should save the newly created exam
		$stringdata =  array('postType'=> $postType, 'examName' => $examName, 'examQuestions' => $examQuestions);
	}
	elseif ($postType == "scores") {
		//Should return student name, exam name, and score for all saved exam scores
		$stringdata =  array('postType'=> $postType);
	}
	elseif ($postType == "exams") {
		//Should return exam names
		$stringdata =  array('postType'=> $postType);
	}
	elseif ($postType == "studentScores") {
		//Should return examName, examQuestions, questionScore, and overall score for the specified student
		$stringdata =  array('postType'=> $postType, 'ucid' => $username);
	}
	
	$infoback = curl_init();
	curl_setopt($infoback, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($infoback, CURLOPT_POSTFIELDS, http_build_query($stringdata));
	curl_setopt($infoback, CURLOPT_URL,"https://web.njit.edu/~mo27/CS490/betamiddle.php");
	curl_setopt($infoback, CURLOPT_COOKIEJAR, realpath($cookie));
	$stringrcvd = curl_exec($infoback);
	curl_close ($infoback);
	echo $stringrcvd;
?>
