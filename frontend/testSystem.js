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

			if (responseData.database == "Can login to database") {
				output += "<center><h2><font color='blue'>" + responseData.database + "</font></h2></center>";
			}
			else {
				output += "<center><h2><font color='red'>" + responseData.database + "</font></h2></center>";
			}

			output += "<br>";

			if (responseData.njit == "Can login to NJIT") {
				output += "<center><h2><font color='blue'>" + responseData.njit + "</font></h2></center>";
			}
			else {
				output += "<center><h2><font color='red'>" + responseData.njit + "</font></h2></center>";
			}

			document.getElementById("response").innerHTML = output;
		}
	}

	var user = document.getElementById("ucid").value;
	var pswd = document.getElementById("pass").value;

	request.open("POST", "https://web.njit.edu/~anm52/CS490/login.php", true)
	request.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	request.send("ucid="+encodeURIComponent(user)+"&pwd="+encodeURIComponent(pswd));
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

			if (responseData.database == "Question added succesfully") {
				output += "<center><h2><font color='blue'>" + responseData.database + "</font></h2></center>";
			}
			else {
				output += "<center><h2><font color='red'>" + responseData.database + "</font></h2></center>";
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

	var questionData = "question=" + question + "&funcName=" + funcName + "&params=" + params + 
	"&input=" + input + "&output=" + output + + "&difficulty=" + difficulty + "&category=" + category;

	request.open("POST", "https://web.njit.edu/~anm52/CS490/newQuestion.php", true)
	request.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	request.send(questionData);
}
