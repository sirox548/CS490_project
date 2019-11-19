<?php
$postType=$_POST['postType'];
$ucid=$_POST['ucid'];
$password=$_POST['pwd'];
$question=$_POST['question'];
$funcName=$_POST['funcName'];
$params=$_POST['params'];
$input=$_POST['input'];
$output=$_POST['output'];
$difficulty=$_POST['difficulty'];
$category=$_POST['category'];

$examName=$_POST['examName'];
$examQuestions=$_POST['questions'];
$pointValues=$_POST['pointValues'];

$questionID = $_POST['questionID'];
$answers = $_POST['answers'];

$gradedID = $_POST['gradedID'];

$newQuestions=$_POST['newQuestions'];
$paramsValues=$_POST['paramsValues'];
$testcase=$_POST['testcase'];
$exampoints=$_POST['exampoints'];
$studentanswer=$_POST['answers'];
$questionref=$_POST['questionref'];

$gradepointsreceived=$_POST['gradepointsreceived'];

$validfuncnames=$_POST['validfuncnames'];

$i=0;
$j=0;
$k=0;
$studentanswer= rawurldecode($studentanswer);

$stringdata =  array(  'postType'=> $postType,
                       'ucid'=> $ucid,
                       'pwd'=> $password,
                       'question'=> $question,
                       'funcName'=> $funcName,
                       'params'=> $params,
                       'input'=> $input,
                       'output'=> $output,
                       'difficulty'=> $difficulty,
                       'category'=> $category,
                       'examName'=> $examName,
                       'questions'=> $examQuestions,
                       'pointValues' => $pointValues,
                       'questionID' => $questionID,
                       'answers'=> $answers,
                       'newQuestions'=> $newQuestions,
                       'paramsValues'=> $paramsValues,
                       'testcase'=> $testcase,
                       'exampoints'=> $exampoints,
                       'studentanswer'=> $studentanswer,
                       'questionref'=> $questionref,
                       'gradepointsreceived'=> $gradepointsreceived,                       
                       'validfuncnames'=> $validfuncnames,
                       'gradedID'=>$gradedID );
$infoback = curl_init();
curl_setopt($infoback, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($infoback, CURLOPT_POST, 1);
curl_setopt($infoback, CURLOPT_POSTFIELDS, http_build_query($stringdata));
curl_setopt($infoback, CURLOPT_URL,"https://web.njit.edu/~rjb57/CS490/betabackend.php");
$stringrcvd = curl_exec($infoback);
curl_close ($infoback);
echo $stringrcvd;

///***************************    GRADING STARTS *****************************//

if($postType=='submitExam')
  {   
      $postType = 'gradingExam';
      $questionNumber = explode(",", $examQuestions);
      $howmanyquestions = count($questionNumber);
      $jsoned = json_decode($stringrcvd);
      $completedID = $jsoned->completedID;

      for($i; $i < $howmanyquestions; $i++)
          { $singlequestion = $questionNumber[$i];
            $stringdata1 = array('postType'=>$postType,
                                  'questionID'=>$singlequestion,
                                  'examName'=>$examName);
            $questionback = curl_init();
            curl_setopt($questionback, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($questionback, CURLOPT_POST, 1);
            curl_setopt($questionback, CURLOPT_POSTFIELDS, http_build_query($stringdata1));
            curl_setopt($questionback, CURLOPT_URL, "https://web.njit.edu/~rjb57/CS490/betabackend.php");
            $stringquestionback = curl_exec($questionback);
            curl_close($questionback);
            $questionbackarray[$i] = $stringquestionback;
          } 
      $howmanyquestions1 = count($questionbackarray);
      $studentanswer = explode("~", $studentanswer);
      $questionpoints = explode(",", json_decode($questionbackarray[0])->{'pointValues'});
      
      //print_r($questionbackarray);
      
      for($j; $j < $howmanyquestions1; $j++)
          { $parse = $questionbackarray[$j];
            $details = json_decode($parse);
            $parameternames = $details->{'params'};
            $functionname = $details->{'funcName'};
            $questID = $questionNumber[$j];
            $case_output = $details->{'output'};
            $case_input = $details->{'input'};
            $answer = $studentanswer[$j];
            //$exampoitscalc = $details->{'pointValues'};
            $currentPoints = $questionpoints[$j];
            $examnameid = $examName;//$details->{'examName'};
            $validfuncname = $details->{'looptype'};
            $completedExamID = $completedID;
        //main grading funciton to be called    
            $gradecalc1 = gradingfunc($answer, $functionname, $parameternames, $case_input, $case_output, $ucid, $currentPoints, $validfuncname);
        //end of main function
            $gradewithcomment = explode("^", $gradecalc1);
            $gradecomments = $gradewithcomment[0];
            $gradewocomment = $gradewithcomment[1];
            $gradeanswer += $gradewithcomment[1]; 
            $questionref = $questionnumber[$j];
            $totalpoints += $currentPoints;
          
            $stringdata2 = array('postType'=>'storeComment', 
                                  'reasons'=>$gradecomments, 
                                  'pointsReceived'=>$gradewocomment,
                                  'ucid'=>$ucid, 
                                  'questionID'=>$questID, 
                                  'completedExamID'=>$completedExamID);
            $questionback2 = curl_init();
            curl_setopt($questionback2, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($questionback2, CURLOPT_POST, 1);
            curl_setopt($questionback2, CURLOPT_POSTFIELDS, http_build_query($stringdata2));
            curl_setopt($questionback2, CURLOPT_URL, "https://web.njit.edu/~rjb57/CS490/betabackend.php");  
            $stringquestionback2 = curl_exec($questionback2);
            curl_close($questionback2);    
            //print_r($stringdata2);
          }
      $gradeanswer = round((($gradeanswer/$totalpoints)*100), 0);

      $stringdata3 = array('postType'=>'storeGrade',
                           'grade'=>$gradeanswer, 
                           'ucid'=>$ucid);
      $sendgrade = curl_init();
      curl_setopt($sendgrade, CURLOPT_RETURNTRANSFER, 1);
      curl_setopt($sendgrade, CURLOPT_POST, 1);
      curl_setopt($sendgrade, CURLOPT_POSTFIELDS, http_build_query($stringdata3));
      curl_setopt($sendgrade, CURLOPT_URL, "https://web.njit.edu/~rjb57/CS490/betabackend.php");
      $stringsendgrade = curl_exec($sendgrade);
      curl_close($sendgrade);
      //echo "\n\n".$stringsendgrade."\n\n";  
}
//       gradingfunc($answer, $functionname, $parameternames, $case_input, $case_output, $ucid, $currentPoints, $validfuncname);
//function grading exam
function gradingfunc($studentresp, $truefuncName, $trueargs, $testcasein, $testcaseout, $studentname, $exampointsvalue, $validfuncnames)
  { $grade = $exampointsvalue;   // current points that exam should be worth
    $totalgrade = 0;
    $gradecomments = "";
    $carrot = "^";
    $newparams = $testcasein;
    $file = "tests/$studentname.py";
    
    //print_r(array('response'=>$studentresp,'truefuncname'=>$truefuncName,'trueargs'=>$trueargs,'testcaseIN'=>$testcasein, 'testcaseOUT'=>$testcaseout, 'studentname'=>$studentname, 'exampointsvalue'=>$exampointsvalue, 'validfuncname'=>$validfuncnames));
    
    //Check and fix colon at the end of lines when needed
    if (preg_match("/\bdef\b|\bfor\b|\bif\b|\belse\b|\bwhile\b|\belif\b/", $studentresp))
        { $dividersep = "\r\n";
          $line = strtok($studentresp, $dividersep);
          $line1 = "";
          while ($line !== false) 
            { if(preg_match("/\bdef\b|\bfor\b|\bif\b|\belse\b|\bwhile\b|\belif\b/", $line))
              {if (preg_match('/:$/', $line))
                {$line1 .= $line . $dividersep;
                 $gradecomments .= "Not missing a colon at the end of the line. 0 points deducted.~";}
              else
                { $grade--;
                  $line1 .= $line . ":" . $dividersep;
                  $gradecomments .= "Wrong, missing colon at the end. -1 point~\n";
                }
              }
              else { $line1 .= $line . $dividersep;}
              $line = strtok( $dividersep );
            }
          $studentresp = $line1;
        }
    
    //Check and replace the function name
      $funcpattern = "/[a-zA-Z0-9]+ *(?=\()/";
      $divideanswer = preg_match($funcpattern, $studentresp,$funcName); 
      if($divideanswer){
        $studentfuncName = $funcName[0];
        if($studentfuncName!=$truefuncName){
          $gradecomments .= "Incorrect function name. Function is called $studentfuncName instead of $truefuncName. -5 points~";
          $studentresp = preg_replace($funcpattern, $truefuncName, $studentresp);
          $grade-=5;
        }else {
          $gradecomments .= "Correct function name. 0 points deducted.~";
        }
      }else {
        $gradecomments .= "Incorrect function formatting. -5 points~";
        $grade-=5;
      }
      
      //Check and replace the arguments
      $argpattern = "/(?<=\() *\w* *(, *\w)* *(?=\))/";
      $divideanswer = preg_match($argpattern, $studentresp,$args);
      $studentargs = str_replace(' ', '', $args[0]);
      $trueargs = str_replace(" ", '', $trueargs);
      if($divideanswer){
        if($studentargs!=$trueargs){
          $gradecomments .= "Incorrect arguments. -1 point~";
          $studentresp = preg_replace($argpattern, $trueargs, $studentresp);
          $grade-=1;
        }else {
          $gradecomments .= "Correct arguments. 0 points deducted.~";
        }
      }else {
        $gradecomments .= "Incorrect argument formatting. -1 point~";
        $grade-=1;
      }
      
    //Check for the constraints constraints
    switch ($validfuncnames) 
      { case "for":
          if(preg_match("/\bfor\b/", $studentresp)){break;}
          else
          {$gradecomments .= "Function Name incorrect. for loop not used, -1 point~"; $grade-=1; $totalgrade++; break;}
          break;
        case "while":
          if(preg_match("/\bwhile\b/", $studentresp)){break;}
          else
          {$gradecomments .= "While loop not used, -1 point~"; $grade-=1; break;}
          break;
        case "print":
          if(preg_match("/\bprint\b/", $studentresp)){break;}
          else
          {$gradecomments .= "Print not used, -1 point~"; $grade-=1; break;}
          break;
        default:
          //echo "There are no valid constrains names ";
          break;
      }
      
    //Check if student used print instead of return
    if($validfuncnames!="print"){
      if (preg_match("/\bprint\b/", $studentresp))
          { $studentresp = preg_replace("/\bprint\b/", "return", $studentresp);
            $grade-=1;
            $gradecomments .= "Used print instead of return. -1 point~";
      }else {
        $gradecomments .= "Correctly used return. 0 points deducted.~";
      }
    }
    
    //Show corrections in the comments
    if(strlen($gradecomments)>0){
      $gradecomments .= "Corrected exam: ".$studentresp."~";
    }
    
    
    //Run test cases
    $temp = explode(",", $testcasein); 
      $testcasenumber = count($temp);
      $testcaseout = explode(",", $testcaseout);
      //$remainingGrade = $exampointsvalue - $totalgrade;
      $testinggradevalue = round(($grade/$testcasenumber), 0);

      for($k=0; $k < $testcasenumber; $k++)
        { $newparams = $temp[$k];
          $testcaseout1 = $testcaseout[$k];
          $execPythonScript = $studentresp. "\n" . "print($truefuncName($newparams))";
          file_put_contents($file, $execPythonScript);
          $runpython = exec("python $file");
//          echo "\nEXEC:\n".$execPythonScript."\n\n";
//          $runpython = rtrim(shell_exec("python -c '$execPythonScript'"));
          if ($runpython == $testcaseout1)
            { $gradecomments .= "Test case for $truefuncName($newparams) passed.~";}
          else{ if($runpython == "")
                { $grade-=$testinggradevalue;
                  $gradecomments .= "Testcase failed for:$truefuncName($newparams),\noutput suppose to be: $testcaseout1, where student output has error. - $testinggradevalue point~";}
              else
                { $testinggradevalue = round($testinggradevalue, 0);
                  $grade-=$testinggradevalue;
                  $gradecomments .= "Testcase failed for:$truefuncName($newparams),\noutput suppose to be: $testcaseout1, where student output was $runpython. - $testinggradevalue point~";
                }
              }
        }
    
    $grade = round($grade, 0);
    if($grade<0){$grade=0;}
    $grade = $gradecomments .= $carrot .= $grade;
    return $grade;
  }

?> 