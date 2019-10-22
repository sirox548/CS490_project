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
			if (responseData.database == "true") {
				if (responseData.role == "instructor") {
					window.location.href = '/~anm52/CS490/home.html';
				}
				else {
					window.location.href = '/~anm52/CS490/studentHome.html';
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

	var questionData = "postType=addQuestion" + "&question=" + question + "&funcName=" + funcName + "&params=" + params + 
	"&input=" + input + "&output=" + output + + "&difficulty=" + difficulty + "&category=" + category;

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
	var examName = document.getElementById('testName').value;

	examData="postType=createExam" + "&examName=" + examName + "&examQuestions=" + examQuestions;

	request.open("POST", "https://web.njit.edu/~anm52/CS490/posts.php", true)
	request.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	request.send(examData);
}

function getSelectedQuestions(){
	var selected = document.querySelectorAll('input[name=cb]:checked');
	var examQuestions = "";

	for (var i = 0; i < selected.length; i++){
		examQuestions += selected[i].value + ',';
	}

	return examQuestions;
}

