<?php
$postType = "login";
$username="none";
$password="none";
if ( $_POST['postType']=='login' )
	{ $postType=$_POST['postType'];
    $username=$_POST['ucid'];
    $password=$_POST['pwd'];
	}
$output = loginscript($username,$password);
echo $output;

function loginscript($username, $password)
{ $stringdata =  array('postType'=> $postType,
                      'username'=> $username,
					            'password' => $password);
  $infoback = curl_init();
  curl_setopt($infoback, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($infoback, CURLOPT_POST, 1);
  curl_setopt($infoback, CURLOPT_POSTFIELDS, http_build_query($stringdata));
  curl_setopt($infoback, CURLOPT_URL,"https://web.njit.edu/~rjb57/CS490/betabackend.php");
  curl_setopt($infoback, CURLOPT_COOKIEJAR, realpath($cookie));
  curl_setopt($infoback, CURLOPT_COOKIEFILE, realpath($cookie));
  curl_setopt($infoback, CURLOPT_SSL_VERIFYPEER, false);
  curl_setopt($infoback, CURLOPT_REFERER, "https://web.njit.edu/~rjb57/CS490/validate.php");
  $stringrcvd = curl_exec($infoback);
  curl_close ($infoback);
  return $stringrcvd;
}
?>
