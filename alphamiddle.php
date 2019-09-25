<?php
//**************CS490 Project ************
//**************Marian Oleksy ************
$input_json = file_get_contents('php://input');
$stringrcvd = json_decode($input_json, true);
$username="none";
$password="none";

if ( isset($stringrcvd['username']))
	{ $username=$stringrcvd['username'];
    }
if ( isset($stringrcvd['password']))
	{ $password=$stringrcvd['password'];
    }
$login_to_NJIT_website=loginnjitscript($username,$password);
$login_to_database=loginscript($username,$password);
print "<center><h2>".$login_to_database.'  '.$login_to_NJIT_website."</h2></center>";

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
	if ($info['http_code'] == 200)
    { return "Can login to NJIT";
      }
	else
    { return "Cannot login to NJIT";
      }
}
// function for login to database
function loginscript($username, $password)
{ $stringdata =  array('username'=> $username, 'password' => $password);
  $infoback = curl_init();
  curl_setopt($infoback, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($infoback, CURLOPT_POSTFIELDS, json_encode($stringdata));
  curl_setopt($infoback, CURLOPT_URL,"http://web.njit.edu/~rjb57/CS490/validate.php");
  curl_setopt($infoback, CURLOPT_COOKIEJAR, realpath($cookie));
  curl_setopt($infoback, CURLOPT_COOKIEFILE, realpath($cookie));
  curl_setopt($infoback, CURLOPT_SSL_VERIFYPEER, false);
  curl_setopt($infoback, CURLOPT_REFERER, "http://web.njit.edu/~rjb57/CS490/validate.php");
  $stringrcvd = curl_exec($infoback);
  curl_close ($infoback);
  return $stringrcvd;
}
?>
