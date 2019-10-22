<?php
  $postType = "none";
  $user = "none";
  $pass = "none";
  if (isset($_POST['postType'])){$postType=$_POST['postType'];}
  if (isset($_POST['ucid'])){$user=$_POST['ucid'];}
  if (isset($_POST['pwd'])){$pass=$_POST['pwd'];}
    $output = loginscript($postType,$user,$pass);
    echo $output;

    function loginscript($type, $username, $password)
      { $stringdata =  array('postType'=>$type,'ucid'=> $username,
                  'pwd' => $password);
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
