<?php
	$username = "none";
	$password = "none";
	if ( isset($_POST['ucid']))
		{ $username=$_POST['ucid'];
			}
	if ( isset($_POST['pwd']))
		{ $password=$_POST['pwd'];
			}
			
	$stringdata =  array('ucid'=> $username, 'pwd' => $password);
	$infoback = curl_init();
	curl_setopt($infoback, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($infoback, CURLOPT_POSTFIELDS, http_build_query($stringdata));
	curl_setopt($infoback, CURLOPT_URL,"https://web.njit.edu/~mo27/CS490/alphamiddle.php");
	curl_setopt($infoback, CURLOPT_COOKIEJAR, realpath($cookie));
	$stringrcvd = curl_exec($infoback);
	curl_close ($infoback);
	echo $stringrcvd;
?>