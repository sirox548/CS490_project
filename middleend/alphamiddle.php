<?php
//**************CS490 Project ************
//**************Marian Oleksy ************
/*$input_json = file_get_contents('php://input');
$stringrcvd = json_decode($input_json, true);*/
$username="none";
$password="none";

if ( isset($_POST['ucid']))
	{ $username=$_POST['ucid'];
		}
if ( isset($_POST['pwd']))
	{ $password=$_POST['pwd'];
		}
$login_to_NJIT_website = ",\"njit\":\"".loginnjitscript($username,$password)."\"}";
$login_to_database = loginscript($username,$password);
$output = str_replace("}", $login_to_NJIT_website, $login_to_database);

echo $output;

// function for login to any NJIT site
function loginnjitscript($username, $password)
{	$stringdata =  array("user"=> $username, "pass" => $password, "uuid" => "0xACA021");
	$cookie = "cookie.txt";
	$infoback = curl_init();
	curl_setopt($infoback, CURLOPT_URL, "https://aevitepr2.njit.edu/MyHousing/login.html");
	curl_setopt($infoback, CURLOPT_HEADER, 0);
	curl_setopt($infoback, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($infoback, CURLOPT_POSTFIELDS, http_build_query($stringdata));
	curl_setopt($infoback, CURLOPT_COOKIEJAR, realpath($cookie));
	curl_setopt($infoback, CURLOPT_COOKIEFILE, realpath($cookie));
	curl_setopt($infoback, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($infoback, CURLOPT_REFERER, "https://aevitepr2.njit.edu/MyHousing/login.html"); 
	$stringrcvd = curl_exec($infoback);
	curl_close ($infoback);	
//	if (strpos($stringrcvd,"Cannot login - error")==false) return "Can login to NJIT";
//	return "Cannot login to NJIT";
	if (strpos($stringrcvd,"Please Select a System to Sign Into") === false)
		{ return "Cannot login to NJIT";
	}
	else
		{ return "Can login to NJIT";
	}
}
// function for login to database
function loginscript($username, $password)
{ $stringdata =  array('username'=> $username, 'password' => $password);
	$infoback = curl_init();
	curl_setopt($infoback, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($infoback, CURLOPT_POSTFIELDS, http_build_query($stringdata));
	curl_setopt($infoback, CURLOPT_URL,"https://web.njit.edu/~rjb57/CS490/validate.php");
	curl_setopt($infoback, CURLOPT_COOKIEJAR, realpath($cookie));
	curl_setopt($infoback, CURLOPT_COOKIEFILE, realpath($cookie));
	curl_setopt($infoback, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($infoback, CURLOPT_REFERER, "https://web.njit.edu/~rjb57/CS490/validate.php");
	$stringrcvd = curl_exec($infoback);
	curl_close ($infoback);
	return $stringrcvd;
}
?>
