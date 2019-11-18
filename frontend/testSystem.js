function login() {
	var request;
	try {
		request = new XMLHttpRequest();
	}
	catch{
		request = new ActiveXObject("Microsoft.XMLHTTP");
	}

	request.onreadystatechange = function () {

		if (request.readyState === 4 && request.status == 200) {
			var responseData = JSON.parse(request.responseText);
			var output = "";

			//response data should be changed to teacher/student/not recognized
			if (responseData.database == true) {
				if (responseData.role == "instructor") {
					window.location.href = '/~anm52/CS490/newQuestion.html';
				}
				else {
					window.location.href = '/~anm52/CS490/exams.html';
				}
				//output += "<center><h2><font color='blue'>" + responseData.database + "</font></h2></center>";
				
			}
			else {
				output += "<center><h2><font color='red'>" + responseData.database + "</font></h2></center>";
			}

			// output += "<br>";

			// if (responseData.njit == "Can login to NJIT") {
			// 	output += "<center><h2><font color='blue'>" + responseData.njit + "</font></h2></center>";
			// }
			// else {
			// 	output += "<center><h2><font color='red'>" + responseData.njit + "</font></h2></center>";
			// }

			document.getElementById("response").innerHTML = output;
		}
	}

	var user = document.getElementById("ucid").value;
	var pswd = document.getElementById("pass").value;
	window.localStorage.setItem('ucid', user);

	request.open("POST", "https://web.njit.edu/~anm52/CS490/posts.php", true)
	request.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	request.send( "postType=login"+"&ucid="+encodeURIComponent(user)+"&pwd="+encodeURIComponent(pswd));
}

function submitNewQuestion(){
	var request;
	try {
		request = new XMLHttpRequest();
	}
	catch{
		request = new ActiveXObject("Microsoft.XMLHTTP");
	}

	request.onreadystatechange = function () {

		if (request.readyState === 4 && request.status == 200) {
			var responseData = JSON.parse(request.responseText);
			var output = "";

			if (responseData.database == "success") {
				output += "<center><h2><font color='green'>" + responseData.database + "</font></h2></center>";
			}
			else {
				output += "<center><h2><font color='red'> Something went wrong </font></h2></center>";
			}

			output += "<br>";
			document.getElementById("response").innerHTML = output;
		}
	}

	var question = document.getElementById("question").value;
	var funcName = document.getElementById("funcName").value;
	var params = document.getElementById("params").value;
	var input = document.getElementById("input").value;
	var output = document.getElementById("output").value;
	var difficulty = document.getElementById("difficulty").value;
	var category = document.getElementById("category").value;
	var constraint = document.getElementById("constraint")

	var questionData = "postType=addQuestion" + "&question=" + question + "&funcName=" + funcName + "&params=" + params + 
	"&input=" + input + "&output=" + output + "&difficulty=" + difficulty + "&category=" + category + "&looptype=" + constraint;

	request.open("POST", "https://web.njit.edu/~anm52/CS490/posts.php", true)
	request.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	request.send(questionData);
}


//creates a new exam
function createExam(){
	var request;
	try {
		request = new XMLHttpRequest();
	}
	catch{
		request = new ActiveXObject("Microsoft.XMLHTTP");
	}

	request.onreadystatechange = function () {

		if (request.readyState === 4 && request.status == 200) {
			var responseData = JSON.parse(request.responseText);
			var output = "";

			if (responseData.database == "success") {
				output += "<center><h2><font color='green'>" + responseData.database + "</font></h2></center>";
			}
			else {
				output += "<center><h2><font color='red'> Something went wrong </font></h2></center>";
			}

			output += "<br>";
			document.getElementById("response").innerHTML = output;
		}
	}
	
	var examQuestions = getSelectedQuestions();
	var pointValues = getPointValues();
	var examName = document.getElementById('testName').value;

	var examData="postType=createExam" + "&examName=" + examName + "&questions=" + examQuestions + "&pointValues=" + pointValues;

	request.open("POST", "https://web.njit.edu/~anm52/CS490/posts.php", true)
	request.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	request.send(examData);
}

function getSelectedQuestions(){
	var selected = document.querySelectorAll('input[name=cb]:checked');
	var examQuestions = "";

	for (var i = 0; i < selected.length; i++){
		if (i<selected.length-1){
			examQuestions += selected[i].value + ',';
		}
		else {
			examQuestions += selected[i].value;
		}
	}
	return examQuestions;
}

function getTotalPoints() {
	var values = document.querySelectorAll('input[name=points]');
	total = 0;
	for (var i = 0; i < values.length; i++){
		total+= Number(values[i].value);
	}
	document.getElementById("totalPoints").innerHTML = "Total Points: " + total;
	
}

function getPointValues(){
	var values = document.querySelectorAll('input[name=points]');
	var points = "";
	for (var i = 0; i < values.length; i++){
		if (values[i].value > 0){
			if (i<values.length-1){
				points += values[i].value + ',';
			}
			else {
				points += values[i].value;
			}
		}
	}

	if ( points.substring(points.length -1 ) == ",") {
		points = points.substring(0, points.length - 1);
	}
}

function takeExam(values){
	examName = values.id
	window.localStorage.setItem('examName', examName);
}

function reviewScore(values){
	//given ucid and examName should return the exam questions, score for each question, any comments for each question, and total score
	var ucid = values.id;
	var examId = values.name;

	localStorage.setItem('gradedID', examId);
	localStorage.setItem('ucid', ucid);

	window.location.href = '/~anm52/CS490/reviewScore.html';
  }


  function submitScore() {
	  //should save the revised score
	  var request;
	  try {
		  request = new XMLHttpRequest();
	  }
	  catch{
		  request = new ActiveXObject("Microsoft.XMLHTTP");
	  }
  
	  request.onreadystatechange = function () {
  
		  if (request.readyState === 4 && request.status == 200) {
			  var responseData = JSON.parse(request.responseText);
			  var output = "";
  
			  if (responseData.database == "success") {
				  output += "<center><h2><font color='green'>" + responseData.database + "</font></h2></center>";
			  }
			  else {
				  output += "<center><h2><font color='red'> Something went wrong </font></h2></center>";
			  }
  
			  output += "<br>";
			  document.getElementById("response").innerHTML = output;
		  }
	  }
	  
	  var examQuestions = getSelectedQuestions();
	  var pointValues = getPointValues();
	  var examName = localStorage.getItem('examName');
	  var ucid = localStorage.getItem('ucid'); //get from local storage
  
	  var data="postType=revisedScores" + "&ucid=" + ucid + "&examName=" + examName + "&questions=" + examQuestions + "&revisedScores=" + pointValues;
  
	  request.open("POST", "https://web.njit.edu/~anm52/CS490/posts.php", true)
	  request.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	  request.send(data);
  }

  function submitExam(){
	var request;
	try {
		request = new XMLHttpRequest();
	}
	catch{
		request = new ActiveXObject("Microsoft.XMLHTTP");
	}

	request.onreadystatechange = function () {

		if (request.readyState === 4 && request.status == 200) {
			var responseData = JSON.parse(request.responseText);
			var output = "";
			if (responseData.database == "success") {
				output += "<center><h2><font color='green'>" + responseData.database + "</font></h2></center>";
			}
			else {
				output += "<center><h2><font color='red'> Something went wrong </font></h2></center>";
			}

			output += "<br>";
			document.getElementById("response").innerHTML = output;
		}
    }
    
    
	var user = localStorage.getItem('ucid');
	var examName = localStorage.getItem('examName');
	var responses = "";
	var questionIDs = localStorage.getItem('questionIDs');
	localStorage.removeItem('questionIDs');
	localStorage.removeItem('examName');
    
    var ids = questionIDs.split(',');
    for (var i = 0; i < ids.length; i++){
        if (i< ids.length-1){
            responses += document.getElementById(ids[i]).value + '~';
        }
        else{
            responses += document.getElementById(ids[i]).value;
        }
    }

	var submission = "postType=submitExam" + "&ucid=" + user + "&examName=" + examName + "&questions=" + questionIDs + "&answers=" + encodeURIComponent(responses);

	request.open("POST", "https://web.njit.edu/~anm52/CS490/posts.php", true)
	request.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	request.send(submission);
}

function filter(){
	var inputCat = document.getElementById("filterCategory");
	var filterCat = inputCat.value.toUpperCase();

	var inputDif = document.getElementById("filterDifficulty");
	var filterDif = inputDif.value.toUpperCase();

	var inputSearch = document.getElementById("searchInput");
	var filterSearch = inputSearch.value.toUpperCase();

	var table = document.getElementById("questionBank");
	var tr = table.getElementsByTagName("tr");

	for (i = 0; i < tr.length; i++) {
		tdSearch = tr[i].getElementsByTagName("td")[0];
		tdDif = tr[i].getElementsByTagName("td")[1];
		tdCat = tr[i].getElementsByTagName("td")[2];

		if (tdSearch || tdDif || tdCat) {
		  searchVal = tdSearch.textContent || tdSearch.innerText;
		  difVal = tdDif.textContent || tdDif.innerText;
		  catVal = tdCat.textContent || tdCat.innerText;

		  if (searchVal.toUpperCase().indexOf(filterSearch) > -1 && difVal.toUpperCase().indexOf(filterDif) > -1 && catVal.toUpperCase().indexOf(filterCat) > -1) {
			tr[i].style.display = "";
		  } 
		  else {
			tr[i].style.display = "none";
		  }
		}
	  }
}

