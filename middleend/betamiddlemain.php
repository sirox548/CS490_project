<?php
$username=$_POST['ucid'];
$password=$_POST['pwd'];


  $stringdata =  array('username'=> $username,
                       'password'=> $password);
                       
	$infoback = curl_init();
 	curl_setopt($infoback, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($infoback, CURLOPT_POST, 1);
	curl_setopt($infoback, CURLOPT_POSTFIELDS, http_build_query($stringdata));
	curl_setopt($infoback, CURLOPT_URL,"https://web.njit.edu/~rjb57/CS490/betabackenddata.php");
  $stringrcvd = curl_exec($infoback);
	curl_close ($infoback);
	return $stringrcvd;
?> 
