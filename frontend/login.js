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

	request.open("POST", "https://web.njit.edu/~mo27/CS490/alphamiddle.php", true);
	request.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	request.send("ucid="+encodeURIComponent(user)+"&pwd="+encodeURIComponent(pswd));
}
