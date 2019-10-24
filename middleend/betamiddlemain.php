<?php
$postType=$_POST['postType'];
$username=$_POST['ucid'];
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

//echo $examName." ".$examQuestions." ".$pointValues." ";

$questionID = $_POST['questionID'];
$answers = $_POST['answers'];

$newQuestions=$_POST['newQuestions'];
$paramsValues=$_POST['paramsValues'];
$testcase=$_POST['testcase'];
$exampoints=$_POST['exampoints'];
$studentanswer=$_POST['answers'];
$questionref=$_POST['questionref'];
$i=0;
$j=0;
$k=0;
$studentanswer= rawurldecode($studentanswer);
$gradepointsreceived=$_POST['gradepointsreceived'];

$validfuncnames=$_POST['validfuncnames'];

$stringdata =  array(  'postType'=> $postType,
                       'ucid'=> $username,
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
                       'newQuestions'=> $newQuestions,
                       'paramsValues'=> $paramsValues,
                       'testcase'=> $testcase,
                       'exampoints'=> $exampoints,
                       'studentanswer'=> $studentanswer,
                       'questionref'=> $questionref,
                       'gradepointsreceived'=> $gradepointsreceived,
                       'answers'=> $answers,
                       'validfuncnames'=> $validfuncnames );
$infoback = curl_init();
curl_setopt($infoback, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($infoback, CURLOPT_POST, 1);
curl_setopt($infoback, CURLOPT_POSTFIELDS, http_build_query($stringdata));
curl_setopt($infoback, CURLOPT_URL,"https://web.njit.edu/~rjb57/CS490/betabackend.php");
$stringrcvd = curl_exec($infoback);
curl_close ($infoback);
echo $stringrcvd;

/*if(isset($_POST['UCID'],$_POST['examQuestions'],$_POST['studentanswer']))
    { $postType = 'gradingexam';
      $questionnumber = explode(",", $examQuestions);
      $howmanyquestions = count($questionnumber);
    }*/

if($postType=='submitExam')
  {
      $postType = 'gradingExam';
      $questionNumber = explode(",", $examQuestions);
      $howmanyquestions = count($questionNumber);

      $questionbackarray = [];
      for($i; $i < $howmanyquestions; $i++)
          { $singlequestion = $questionNumber[$i];
            $stringdata1 = array('postType'=>$postType,
                                  'questionID'=>$singlequesiton,
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
      
      
      
      for($j; $j < $howmanyquestions1; $j++)
          { $parse = $questionbackarray[$j];
            $details = json_decode($parse);
            $parameternames = $details->{'params'};
            $functionname = $details->{'funcName'};
            $questID = $details->{'questionID'};
            $testcaseoutputs = $details->{'output'};
            $case_input = $details->{'input'};
            $answer = $answer[$j];
            //$exampoitscalc = $details->{'pointValues'};
            $currentPoints = $questionpoints[$j];
            $examnameid = $details->{'examName'};
            $validfuncname = 'for';//$details->{'validfuncnames'};
            $completedExamID = $details->{'completedID'};
        //main grading funciton to be called
            $gradecalc1 = gradingfunc($answer, $functionname, $parameternames, $case_input, $testcaseoutputs, $ucid, $currentPoints, $validfuncname);
        //end of main function
            $gradewithcomment = explode("^", $gradecalc1);
            $gradecomments = $gradewithcomment[0];
            $gradewocomment = $gradewithcomment[1];
            $gradeanswer += $gradewithcomment[1]; 
            $questionref = $questionnumber[$j];
            $totalpoints += $currentPoints;
          
            if($postType == 'gradingexam')
              { $stringdata2 = array('pointsReceived'=>$gradewocomment, 
                                      'reasons'=>$gradecomments, 
                                      'postType'=>'storeComment', 
                                      'ucid'=>$ucid, 
                                      'questionID'=>$questionref, 
                                      'completedExamID'=>$completedExamID);
                $questionback2 = curl_init();
                curl_setopt($questionback2, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($questionback2, CURLOPT_POST, 1);
                curl_setopt($questionback2, CURLOPT_POSTFIELDS, http_build_query($stringdata2));
                curl_setopt($questionback2, CURLOPT_URL, "https://web.njit.edu/~rjb57/CS490/betabackend.php");  
                $stringquestionback2 = curl_exec($questionback2);  
                curl_close($questionback2);
              }
          }
      //$gradeanswer = round((($gradeanswer/$totalpoints)*100), 0);

      if($postType == 'gradingexam')
        { $stringdata3 = array('grade'=>$gradeanswer, 'postType'=>'storeGrade','ucid'=>$ucid);
          $sendgrade = curl_init();
          curl_setopt($sendgrade, CURLOPT_RETURNTRANSFER, 1);
          curl_setopt($sendgrade, CURLOPT_POST, 1);
          curl_setopt($sendgrade, CURLOPT_POSTFIELDS, http_build_query($stringdata3));
          curl_setopt($sendgrade, CURLOPT_URL, "https://web.njit.edu/~rjb57/CS490/betabackend.php");
          $stringsendgrade = curl_exec($sendgrade);
          curl_close($sendgrade);
        }
}
//       gradingfunc($answer, $functionname, $parameternames, $case_input, $testcaseoutputs, $ucid, $currentPoints, $validfuncname);
//function grading exam
function gradingfunc($studentresp, $funcname, $params, $testcasein, $testcaseout, $studentname, $exampointsvalue, $validfuncnames)
  { $grade = $exampointsvalue;
    $totalgrade = 0;
    $gradecomments = "";
    $carrot = "^";
    $newparams = $testcasein;
    $file = "$studentname.py";

  //$studentresp= ltrim($studentresp); //trimming white space from beginning - not sure if this is needed??
    $divideanswer = preg_split("/\s+|\(|:/", $studentresp);
    $start = $divideanswer[0]; 
    $studentfunctionname = $divideanswer[1];
    if ($start != "start")
        {$gradecomments += "Function not declared at all, -5 points\n";$totalgrade++;}
    else
        {$gradecomments += "Function comments:\n";$grade++;$totalgrade++;}
    if ($studentfunctionname != $funcname)
        {$gradecomments .= "Wrong Function Name.\n Function supposed to be $funcname, but student has $studentfunctionname, - 5 points~";
        $grade-=5;$totalgrade+=5;}
    else
        {$gradecomments .= "Points not deducted for correct function name~";$totalgrade+=5;}
  
    $divideanswerparams = explode(")", $studentresp); 
    $temp = $divideanswerparams[0]; 
    $divideanswerparamsagain = explode("(", $temp); 
    $studentparams = $divideanswerparamsagain[1];
    $studentparams = preg_replace("/\s/","", $studentparams);
    $params = preg_replace("/\s/","", $params);
    if (strcmp($studentparams, $params) == 0)
        { $gradecomments .= "Verified Parameter Names~";$totalgrade++;}
    else
        { $gradecomments .= "Wrong Parameter Names. Parameter Suppose to be $params ,but student has $studentparams, -1 point~";
          $grade--;$totalgrade++;
        } 
  
    switch ($validfuncnames) 
      { case "for":
          if(preg_match("/\bfor\b/", $studentresp)){break;}
          else
          {$gradecomments .= "Function Name incorrect. for loop not used, -1 point~"; $grade--; $totalgrade++; break;}
          break;
        default:
          //echo "There are no valid constrains names ";
      } 
    if (preg_match("/\bprint\b/", $studentresp))
        { $studentresp = preg_replace("/\bprint\b/", "return", $studentresp);
          $grade--; $totalgrade++; $gradecomments .= "Wrong return of funciton, -1 point~";
        }  
    if (preg_match("/\bdef\b|\bfor\b|\bif\b|\belse\b|\bwhile\b/", $studentresp))
        { $dividersep = "\r\n";
          $line = strtok($studentresp, $dividersep);
          $line1 = "";
          while ($line !== false) 
            { if(preg_match("/\bdef\b|\bfor\b|\bif\b|\belse\b|\bwhile\b|\belif\b/", $line))
              {if (preg_match('/:$/', $line))
                {$line1 .= $line . $dividersep;}
              else
                { $grade--; $totalgrade++; 
                  $line1 .= $line . ":" . $dividersep;
                  $gradecomments .= "Wrong, missing colon at the end,-1 point~";
                }
              }
              else { $line1 .= $line . $dividersep;}
              $line = strtok( $dividersep );
            }
          $studentresp = $line1;
          echo $studentresp;
        }    
    $temp = explode(" ", $newparams); 
    $testcasenumber = count($temp);
    $testcaseout = explode(" ", $testcaseout);
    $remainingGrade = $exampointsvalue - $totalgrade;
    $testinggradevalue = round(($remainingGrade/$testcasenumber), 0);

    for($k; $k < $testcasenumber; $k++)
      { $newparams = $temp[$k];
        $testcaseout1 = $testcaseout[$k];
        file_put_contents($file, $studentresp. "\n" . "print($studentfunctionname($newparams))");
        $runpython = exec("python $studentname.py");
        if ($runpython == $testcaseout1)
          { $gradecomments .= "Verificaiton Testing done correctly~";}
        else{ if($runpython == "")
              { $grade-=$testinggradevalue;
                $gradecomments .= "Testcase failed for:$funcname($newparams),\n output suppose to be: $testcaseout1, where student output has error. - $testinggradevalue point~";}
            else
              { $testinggradevalue = round($testinggradevalue, 0);
                $grade-=$testinggradevalue;
                $gradecomments .= "Testcase failed for:$funcname($newparams),\n output suppose to be: $testcaseout1, where student output was $runpython. - $testinggradevalue point~";
              }
            }
      }
    
    $grade = round($grade, 0);
    $grade = $gradecomments .= $carrot .= $grade;
    return $grade;
  }

?> 
