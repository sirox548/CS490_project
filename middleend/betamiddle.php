<?php
$username="none";
$password="none";
if ( isset($_POST['ucid']), isset($_POST['pwd']))
	{ $username=$_POST['ucid'];
	  $password=$_POST['pwd'];
	}
$login_to_database = loginscript($username,$password);
$output = $login_to_database;
echo $output;

function loginscript($username, $password)
{ $stringdata =  array('username'=> $username,
					   'password' => $password);
  $infoback = curl_init();
  curl_setopt($infoback, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($infoback, CURLOPT_POST, 1);
  curl_setopt($infoback, CURLOPT_POSTFIELDS, http_build_query($stringdata));
  curl_setopt($infoback, CURLOPT_URL,"https://web.njit.edu/~rjb57/CS490/betabackendGIT.php");
  curl_setopt($infoback, CURLOPT_COOKIEJAR, realpath($cookie));
  curl_setopt($infoback, CURLOPT_COOKIEFILE, realpath($cookie));
  curl_setopt($infoback, CURLOPT_SSL_VERIFYPEER, false);
  curl_setopt($infoback, CURLOPT_REFERER, "https://web.njit.edu/~rjb57/CS490/validate.php");
  $stringrcvd = curl_exec($infoback);
  curl_close ($infoback);
  return $stringrcvd;
}
?>
