function login(){
    var request;
    try{
        request = new XMLHttpRequest();
    }
    catch{
        request = new ActiveXObject("Microsoft.XMLHTTP");
    }

    request.onreadystatechange = function(){
        if (request.readyState == 4){
            document.getElementById("response").innerHTML = request.responseText;
        }
    }

    var user = document.getElementById("ucid").value;
    var pswd = document.getElementById("pass").value;
    credentials = {"username": user, "password": pswd}

    request.open("POST", "https://web.njit.edu/~mo27/CS490/alphamiddle.php", true)
    request.send(JSON.stringify(credentials));
}
